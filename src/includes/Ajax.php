<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Class Ajax
 *
 * Handles AJAX requests, sanitizes and filters POST data, and provides nonce/CSRF protection.
 */
class Ajax
{
    private static $_postData = [];
    private static $_sanitizedPostData = [];

    /**
     * Handles incoming AJAX requests and includes the appropriate file.
     *
     * @param string $file The file to include for processing the AJAX request.
     * @return void
     */
    public static function handleRequest(string $file)
    {
        self::verifyRequest();

        $filePath = UNI_AJAX_PATH . $file;

        if (is_readable($filePath)):

            ob_start();

            extract([
                'postData' => self::getPostDataRaw(),
                'sanitizedPostData' => self::getPostData(),
                'self' => self::class,
            ]);

            include $filePath;

            self::sendJson([
                'status' => 'success',
                'content' => ob_get_clean(),
            ]);

        else:

            self::sendJson([
                'status' => 'error',
                'message' => sprintf(__('File not found: %s', UNI_PLUGIN_SLUG), esc_html($filePath)),
            ], 404);

        endif;
    }

    /**
     * Sanitizes a value.
     *
     * @param mixed $value The value to sanitize.
     * @return mixed The sanitized value.
     */
    private static function sanitizeValue($value)
    {
        return is_array($value)
            ? array_map([self::class, 'sanitizeValue'], $value)
            : WPSanitize::text_field($value);
    }

    /**
     * Retrieves the raw POST data.
     *
     * @return array The raw POST data.
     */
    public static function getPostDataRaw()
    {
        if (empty(self::$_postData)) {
            self::$_postData = WPUtils::unslash($_POST);
        }
        return self::$_postData;
    }

    /**
     * Sanitizes the POST data and caches the result.
     *
     * @return array The sanitized POST data.
     */
    public static function getPostData()
    {
        if (empty(self::$_sanitizedPostData)) {
            foreach (self::getPostDataRaw() as $key => $value) {
                self::$_sanitizedPostData[$key] = self::sanitizeValue($value);
            }
        }
        return self::$_sanitizedPostData;
    }

    /**
     * Retrieves a specific field from the raw POST data.
     *
     * @param string $key The key of the field to retrieve.
     * @return mixed|null The value of the field, or null if not found.
     */
    public static function get_param_raw(string $key)
    {
        return self::getPostDataRaw()[$key] ?? null;
    }

    /**
     * Retrieves a specific field from the sanitized POST data.
     *
     * @param string $key The key of the field to retrieve.
     * @return mixed|null The value of the field, or null if not found.
     */
    public static function get_param(string $key)
    {
        return self::getPostData()[$key] ?? null;
    }

    /**
     * Returns the AJAX URL.
     *
     * @return string The AJAX URL.
     */
    public static function url()
    {
        return Plugin::ajax_url();
    }

    /**
     * Generates a nonce for AJAX requests.
     *
     * @return string The generated nonce.
     */
    public static function generateNonce()
    {
        return Plugin::ajax_nonce();
    }

    /**
     * Verifies the request, ensuring nonce and referer validation.
     *
     * @return void
     */
    public static function verifyRequest()
    {
        if (!isset($_SERVER['HTTP_REFERER']) || strpos(WPUtils::unslash($_SERVER['HTTP_REFERER']), Plugin::site_url()) !== 0) {

            self::sendJson([
                'status' => 'error',
                'message' => __('Invalid request origin.', UNI_PLUGIN_SLUG),
            ], 403);
        }

        if (!isset($_POST['security_nonce']) || !check_ajax_referer(UNI_PLUGIN_SLUG . 'ajax', 'security_nonce', false)) {
            self::sendJson([
                'status' => 'error',
                'message' => __('Invalid nonce or missing nonce.', UNI_PLUGIN_SLUG),
            ], 403);
        }
    }

    /**
     * Sends a JSON response with the given data and HTTP status code.
     *
     * @param mixed $data The data to include in the response.
     * @param int $httpCode The HTTP status code for the response.
     * @return void
     */
    public static function sendJson($data, int $httpCode = 200)
    {
        wp_send_json($data, $httpCode);
    }

    /**
     * Outputs debug information as a JSON response.
     *
     * @param array $data Optional additional data to include in the debug output.
     * @return void
     */
    public static function debug(array $data = [])
    {
        self::sendJson([
            'post' => $_POST,
            'postData' => self::getPostDataRaw(),
            'sanitizedPostData' => self::getPostData(),
            'data' => $data,
        ]);
    }
}
