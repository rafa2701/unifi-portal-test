<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Template Class
 *
 * Handles the inclusion and rendering of template files for various contexts
 * such as admin, frontend, shortcodes, and other plugin sections.
 *
 */
class Template
{
    /**
     * Predefined template file paths for different contexts.
     *
     * Provides a centralized mapping of template paths to their respective
     * context directories within the plugin structure.
     *
     * @var array
     */
    private const TEMPLATE_PATHS = [
        'admin' => UNI_PLUGIN_SRC_PATH . 'admin/templates/',
        'api' => UNI_PLUGIN_SRC_PATH . 'api/templates/',
        'emails' => UNI_PLUGIN_SRC_PATH . 'emails/templates/',
        'frontend' => UNI_PLUGIN_SRC_PATH . 'frontend/templates/',
        'posts' => UNI_PLUGIN_SRC_PATH . 'posts/templates/',
        'shortcodes' => UNI_PLUGIN_SRC_PATH . 'shortcodes/templates/',
        'services' => UNI_PLUGIN_SRC_PATH . 'services/templates/',
    ];

    /**
     * Includes a template file and returns the rendered output.
     *
     * This method locates and renders a specified template file, allowing
     * for dynamic inclusion of templates across different plugin contexts.
     * It supports passing variables to the template and handles potential
     * errors gracefully.
     *
     * @param array $args {
     *     Configuration parameters for template rendering.
     *
     *     @type string $file Required. The name of the template file to render.
     *     @type string $path Required. The context/directory of the template.
     *     @type array  $data Optional. Variables to be extracted and made available in the template.
     * }
     *
     * @return string Rendered template content or an error message if rendering fails.
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

        $basePath = self::TEMPLATE_PATHS[$path] ?? null;
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
