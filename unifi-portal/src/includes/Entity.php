<?php

namespace Sfx\UnifiPortal;

use WPDB;

if (!defined("UNI_PLUGIN_PATH")) {
    exit;
}

/**
 * Class Entity
 *
 * A base class for interacting with the WordPress database using the wpdb class.
 */
class Entity
{
    /** @var WPDB $wpdb WordPress Database object */
    protected $wpdb;

    /** @var string $dbPrefix WordPress database table prefix */
    protected $dbPrefix;

    /** @var string $charsetCollate Character set and collation for database tables */
    protected $charsetCollate;

    /** @var string $engine Storage engine for database tables */
    protected $engine;

    /** @var string $tableName Table name for the entity */
    protected $tableName;

    /** @var string $schema SQL schema for table creation */
    protected $schema;

    /**
     * Entity constructor.
     * Initializes WordPress database object and other related properties.
     */
    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->dbPrefix = $wpdb->prefix;
        $this->charsetCollate = $wpdb->has_cap('collation') ? $wpdb->get_charset_collate() : 'DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci';
        $this->engine = 'ENGINE = INNODB';
    }

    /**
     * Fetch the full table name with prefix.
     *
     * @return string Full table name.
     */
    public function fetchTableName(): string
    {
        return $this->dbPrefix . $this->tableName;
    }

    /**
     * Fetch the schema for the table creation.
     *
     * @return string Table schema SQL.
     */
    protected function fetchSchema(): string
    {
        return $this->schema;
    }

    /**
     * Create the table in the database if it doesn't exist.
     *
     * @return void
     */
    public function createTable(): void
    {
        $createTableQuery = $this->schema;

        if ($this->wpdb->get_var($this->wpdb->prepare("SHOW TABLES LIKE %s", $this->fetchTableName())) !== $this->fetchTableName()) {
            $result = $this->executeQuery($createTableQuery);
            if ($result === false) {
                $errorMessage = $this->fetchLastError();
                if (defined('is_debugging') && is_debugging) {
                    pr("Error creating table {$this->fetchTableName()}: $errorMessage");
                }
            }
        }
    }

    /**
     * Drop the table from the database (only in non-production environments).
     *
     * @return void
     */
    public function dropTable(): void
    {
        if (defined('UNI_PLUGIN_ENV_MODE') && UNI_PLUGIN_ENV_MODE === 'PROD') :
            return;
        endif;

        if ($this->wpdb->get_var($this->wpdb->prepare("SHOW TABLES LIKE %s", $this->fetchTableName())) === $this->fetchTableName()) {
            $result = $this->executeQuery($this->wpdb->prepare("DROP TABLE IF EXISTS %s", $this->fetchTableName()));
            if ($result === false) {
                $errorMessage = $this->fetchLastError();
                if (defined('is_debugging') && is_debugging) {
                    pr("Error dropping table {$this->fetchTableName()}: $errorMessage");
                }
            }
        }
    }

    /**
     * Insert data into the table.
     *
     * @param array $insertData Associative array of column-value pairs.
     * @return int|false Number of rows affected or false on failure.
     */
    protected function insertRow(array $insertData)
    {
        return $this->wpdb->insert($this->fetchTableName(), $insertData);
    }

    /**
     * Update data in the table.
     *
     * @param array $updateData Associative array of column-value pairs to update.
     * @param array $where Conditions for the update (associative array).
     * @return int|false Number of rows affected or false on failure.
     */
    protected function updateRow(array $updateData, array $where)
    {
        return $this->wpdb->update($this->fetchTableName(), $updateData, $where);
    }

    /**
     * Delete data from the table.
     *
     * @param array $where Conditions for deletion (associative array).
     * @return int|false Number of rows affected or false on failure.
     */
    protected function deleteRow(array $where)
    {
        return $this->wpdb->delete($this->fetchTableName(), $where);
    }

    /**
     * Fetch a single count result from a query.
     *
     * @param string $query SQL query.
     * @param mixed ...$args Query arguments.
     * @return mixed Query result.
     */
    protected function fetchCount(string $query, ...$args)
    {
        return $this->wpdb->get_var($this->wpdb->prepare($query, ...$args));
    }

    /**
     * Fetch a single row from the table.
     *
     * @param string $query SQL query.
     * @param string $output Format (ARRAY_A, ARRAY_N, OBJECT).
     * @param mixed ...$args Query arguments.
     * @return mixed Fetched row.
     */
    protected function fetchRow(string $query, string $output = 'ARRAY_A', ...$args)
    {
        return $this->wpdb->get_row($this->wpdb->prepare($query, ...$args), $output);
    }

    /**
     * Fetch a single column result from a query.
     *
     * @param string $query SQL query.
     * @param mixed ...$args Query arguments.
     * @return array Query results.
     */
    protected function fetchColumn(string $query, ...$args): array
    {
        return $this->wpdb->get_col($this->wpdb->prepare($query, ...$args));
    }

    /**
     * Fetch multiple rows from the table.
     *
     * @param string $query SQL query.
     * @param string $output Format (ARRAY_A, ARRAY_N, OBJECT).
     * @param mixed ...$args Query arguments.
     * @return array Query results.
     */
    protected function fetchResults(string $query, string $output = 'ARRAY_A', ...$args): array
    {
        return $this->wpdb->get_results($this->wpdb->prepare($query, ...$args), $output);
    }

    /**
     * Execute a query in the database.
     *
     * @param string $query SQL query.
     * @param mixed ...$args Query arguments.
     * @return bool|int Number of affected rows or false on failure.
     */
    protected function executeQuery(string $query, ...$args)
    {
        return $this->wpdb->query($this->wpdb->prepare($query, ...$args));
    }

    /**
     * Fetch the last executed query.
     *
     * @return string Last query.
     */
    protected function fetchLastQuery(): string
    {
        return $this->wpdb->last_query;
    }

    /**
     * Fetch the last database error message.
     *
     * @return string Last error message.
     */
    protected function fetchLastError(): string
    {
        return $this->wpdb->last_error;
    }
}
