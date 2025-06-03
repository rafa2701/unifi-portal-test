<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Partial Class
 *
 * Handles the inclusion and rendering of template partial files across different contexts
 *
 */
class Partial
{
    /**
     * Available render paths for template partials.
     *
     * This constant defines the valid paths where template partials can be located.
     * Each key represents a context, and the value is the corresponding directory path.
     *
     * @var array
     */
    private const RENDER_PATHS = [
        'admin' => UNI_PLUGIN_SRC_PATH . 'admin/partials/',
        'api' => UNI_PLUGIN_SRC_PATH . 'api/partials/',
        'emails' => UNI_PLUGIN_SRC_PATH . 'emails/partials/',
        'frontend' => UNI_PLUGIN_SRC_PATH . 'frontend/partials/',
        'posts' => UNI_PLUGIN_SRC_PATH . 'posts/partials/',
        'shortcodes' => UNI_PLUGIN_SRC_PATH . 'shortcodes/partials/',
        'services' => UNI_PLUGIN_SRC_PATH . 'services/partials/',
    ];

    /**
     * Includes and renders a template partial file.
     *
     * This method locates a template file based on the specified context and file name,
     * extracts any provided data variables, and returns the rendered template content.
     *
     * @param array $args {
     *     Configuration parameters for rendering the template.
     *
     *     @type string $file Required. The name of the template file to render.
     *     @type string $path Required. The context/directory where the template is located.
     *     @type array  $data Optional. An associative array of variables to pass to the template.
     * }
     *
     * @return string The rendered template content or an error message if rendering fails.
     */
    public static function include(array $args): string
    {
        $file = $args['file'] ?? '';
        $path = $args['path'] ?? '';
        $data = $args['data'] ?? [];

        if (empty($file) || empty($path)) {
            return sprintf(
                __('Error: Template file or render context not specified for path "%s".', UNI_PLUGIN_SLUG),
                esc_html($path)
            );
        }

        $basePath = self::RENDER_PATHS[$path] ?? null;
        if (!$basePath) {
            return sprintf(
                __('Error: Invalid render path specified: "%s".', UNI_PLUGIN_SLUG),
                esc_html($path)
            );
        }

        $templateFile = $basePath . $file;

        if (!is_readable($templateFile)) {
            return sprintf(
                __('Error: Unable to read template file: %s', UNI_PLUGIN_SLUG),
                esc_html($templateFile)
            );
        }

        ob_start();

        if (!empty($data) && is_array($data)) {
            extract($data, EXTR_SKIP);
        }

        include $templateFile;

        return ob_get_clean();
    }
}
