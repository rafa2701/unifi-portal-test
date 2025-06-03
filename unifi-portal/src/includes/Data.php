<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Data Class
 *
 * Handles the inclusion of PHP files that return data across different contexts.
 */
class Data
{
    /**
     * Available paths for data files.
     *
     * This constant defines the valid paths where data files can be located.
     * Each key represents a context, and the value is the corresponding directory path.
     *
     * @var array
     */
    private const DATA_PATHS = [
        'admin' => UNI_PLUGIN_SRC_PATH . 'admin/data/',
        'api' => UNI_PLUGIN_SRC_PATH . 'api/data/',
        'emails' => UNI_PLUGIN_SRC_PATH . 'emails/data/',
        'frontend' => UNI_PLUGIN_SRC_PATH . 'frontend/data/',
        'posts' => UNI_PLUGIN_SRC_PATH . 'posts/data/',
        'shortcodes' => UNI_PLUGIN_SRC_PATH . 'shortcodes/data/',
        'services' => UNI_PLUGIN_SRC_PATH . 'services/data/',
    ];

    /**
     * Fetches and includes a data file.
     *
     * This method locates a PHP file based on the specified context and file name,
     * executes the file, and directly returns its output. It is intended for files
     * that return data (such as arrays, objects, or strings).
     *
     * @param array $args {
     *     Configuration parameters for fetching the data.
     *
     *     @type string $file Required. The name of the data file to include.
     *     @type string $path Required. The context/directory where the file is located.
     * }
     *
     * @return mixed The output of the included file, or an error message string if the inclusion fails.
     */
    public static function fetch(array $args): mixed
    {
        $file = $args['file'] ?? '';
        $path = $args['path'] ?? '';

        if (empty($file) || empty($path)) {
            return sprintf(
                __('Error: Data file or render context not specified for path "%s".', UNI_PLUGIN_SLUG),
                esc_html($path)
            );
        }

        $basePath = self::DATA_PATHS[$path] ?? null;
        if (!$basePath) {
            return sprintf(
                __('Error: Invalid data path specified: "%s".', UNI_PLUGIN_SLUG),
                esc_html($path)
            );
        }

        $dataFile = $basePath . $file;

        if (!is_readable($dataFile)) {
            return sprintf(
                __('Error: Unable to read data file: %s', UNI_PLUGIN_SLUG),
                esc_html($dataFile)
            );
        }

        return include $dataFile;
    }
}
