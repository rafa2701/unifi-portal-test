<?php

namespace Sfx\UnifiPortal;

if (!defined("UNI_PLUGIN_PATH")) {
    exit;
}

class MultiPostMeta
{
    /**
     * @var int $post_id The ID of the post to manage meta for
     */
    private $post_id;

    /**
     * @var string $prefix Optional prefix for meta keys
     */
    private $prefix = '';

    /**
     * @var array $fields Array of meta fields to manage
     */
    private $fields = [];

    /**
     * @var array $values Associative array of meta key-value pairs
     */
    private $values = [];

    /**
     * Set the post ID
     *
     * @param int $post_id The ID of the post
     * @return $this
     */
    public function setId(int $post_id): self
    {
        $this->post_id = $post_id;
        return $this;
    }

    /**
     * Set a prefix for meta keys
     *
     * @param string $prefix The prefix to use
     * @return $this
     */
    public function prefix(string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Set the fields to manage
     *
     * @param array $fields List of meta field names
     * @return $this
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Set values for the meta fields
     *
     * @param array $values Associative array of field values
     * @return $this
     */
    public function setValues(array $values): self
    {
        $this->values = $values;
        return $this;
    }

    /**
     * Create multiple meta fields
     *
     * @return bool True if all meta fields were created successfully, false otherwise
     */
    public function create(): bool
    {
        if (empty($this->post_id) || empty($this->fields)) {
            return false;
        }

        $success = true;
        foreach ($this->fields as $field) {
            $meta_key = $this->prefix . $field;
            $meta_value = $this->values[$field] ?? null;

            if ($meta_value !== null) {
                $result = PostMeta::create($this->post_id, $meta_key, $meta_value);
                $success = $success && $result;
            }
        }

        return $success;
    }

    /**
     * Read multiple meta fields
     *
     * @param bool $single Whether to return single values
     * @return array|null Associative array of meta values or null if no values found
     */
    public function read(bool $single = true): ?array
    {
        if (empty($this->post_id) || empty($this->fields)) {
            return null;
        }

        $meta_values = [];
        foreach ($this->fields as $field) {
            $meta_key = $this->prefix . $field;
            $value = PostMeta::read($this->post_id, $meta_key, $single);

            if ($value !== null) {
                $meta_values[$field] = $value;
            }
        }

        return empty($meta_values) ? null : $meta_values;
    }

    /**
     * Update multiple meta fields
     *
     * @return bool True if all meta fields were updated successfully, false otherwise
     */
    public function update(): bool
    {
        if (empty($this->post_id) || empty($this->fields) || empty($this->values)) {
            return false;
        }

        $success = true;
        foreach ($this->fields as $field) {
            $meta_key = $this->prefix . $field;
            $meta_value = $this->values[$field] ?? null;

            if ($meta_value !== null) {
                $result = PostMeta::update($this->post_id, $meta_key, $meta_value);
                $success = $success && $result;
            }
        }

        return $success;
    }

    /**
     * Delete multiple meta fields
     *
     * @return bool True if all meta fields were deleted successfully, false otherwise
     */
    public function delete(): bool
    {
        if (empty($this->post_id) || empty($this->fields)) {
            return false;
        }

        $success = true;
        foreach ($this->fields as $field) {
            $meta_key = $this->prefix . $field;
            $result = PostMeta::delete($this->post_id, $meta_key);
            $success = $success && $result;
        }

        return $success;
    }
}
