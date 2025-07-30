<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * UniFi Controller Base Class
 *
 * Provides core functionality for UniFi API communication including:
 * - Authentication and session management
 * - HTTP request handling with rate limiting
 * - Error tracking and debugging
 * - Cookie management
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
abstract class UnifiControllerBase
{
    /** @var bool Authentication status */
    protected bool $is_logged_in = false;

    /** @var bool Identifies if controller is running UniFi OS */
    protected bool $is_unifi_os = false;

    /** @var resource|null cURL handle for requests */
    protected $curl_handle = null;

    /** @var array Active session cookies */
    protected array $cookies = [];

    /** @var int Unix timestamp of cookie creation */
    protected int $cookies_created_at = 0;

    /** @var array Default HTTP request headers */
    protected array $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    /** @var array Controller connection parameters */
    protected array $controller_details = [];

    /** @var array Accumulated error messages */
    protected array $errors = [];

    /** @var bool Debug mode status */
    protected bool $debug_mode = false;

    /** @var string Current API endpoint URL */
    protected string $api_url = '';

    /** @var mixed Last API response data */
    protected $last_response = null;

    /** @var int Delay between API requests in seconds */
    protected const API_DELAY = 5;

    /** @var int Last API request timestamp */
    private int $last_request_time = 0;

    /**
     * Toggle debug mode for API operations
     *
     * @param bool $enable True to enable debugging
     * @return self
     */
    public function debug(bool $enable = true): self
    {
        $this->debug_mode = $enable;
        $this->debugLog('Debug mode ' . ($enable ? 'enabled' : 'disabled'));
        return $this;
    }

    /**
     * Check for presence of errors
     *
     * @return bool True if errors exist
     */
    public function isError(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get accumulated error messages
     *
     * @return array List of error messages
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set API endpoint URL for requests
     *
     * @param string $endpoint API endpoint path
     * @return self
     */
    public function setApiUrl(string $endpoint): self
    {
        $baseurl = rtrim($this->controller_details['baseurl'] ?? '', '/');
        $this->api_url = $baseurl . '/' . ltrim($endpoint, '/');
        return $this;
    }

    /**
     * Get API response in specified format
     *
     * @param string $format Response format (array|object)
     * @return mixed Formatted response data
     */
    public function getResponse(string $format = 'array'): mixed
    {
        if ($format === 'object') {
            return json_decode(json_encode($this->last_response));
        }
        return $this->last_response;
    }

    /**
     * Verify if current connection is valid
     *
     * @return bool True if connection is valid
     */
    public function isValidConnection(): bool
    {
        return $this->is_logged_in && !empty($this->cookies);
    }

    /**
     * Detect UniFi system type (OS vs Legacy)
     *
     * @return bool True if system is UniFi OS
     */
    public function detectUnifiSystem(): bool
    {
        if (!$this->initCurl()) {
            return false;
        }

        if (empty($this->controller_details['baseurl'])) {
            $this->addError('Controller URL not set');
            return false;
        }

        $base_url = rtrim($this->controller_details['baseurl'], '/');

        curl_setopt_array($this->curl_handle, [
            CURLOPT_URL => $base_url . '/',
            CURLOPT_NOBODY => true,
            CURLOPT_TIMEOUT => 10
        ]);

        $response = curl_exec($this->curl_handle);
        $http_code = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);

        curl_setopt($this->curl_handle, CURLOPT_NOBODY, false);

        $this->is_unifi_os = ($http_code === 200);

        return $this->is_unifi_os;
    }

    /**
     * Execute configured API request
     *
     * @return bool Success status
     */
    public function run(): bool
    {
        return $this->execute();
    }

    /**
     * Handle timing between API requests
     *
     * @return void
     */
    protected function handleApiDelay(): void
    {
        $time_since_last = time() - $this->last_request_time;
        if ($time_since_last < self::API_DELAY) {
            sleep(self::API_DELAY - $time_since_last);
        }
        $this->last_request_time = time();
    }

    /**
     * Execute an API request
     *
     * @param string $method HTTP method
     * @param mixed $payload Request payload
     * @return bool Success status
     */
    protected function execute(string $method = 'GET', mixed $payload = null): bool
    {
        if (!$this->is_logged_in) {
            $this->addError('Not logged in to UniFi Controller');
            return false;
        }

        if (empty($this->api_url)) {
            $this->addError('API URL not set');
            return false;
        }

        $this->handleApiDelay();

        $headers = $this->getAuthHeaders();

        curl_setopt_array($this->curl_handle, [
            CURLOPT_URL => $this->api_url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $payload ? json_encode($payload) : null,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_COOKIE => $this->formatCookies(),
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($this->curl_handle);
        $http_code = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $this->addError('cURL Error: ' . curl_error($this->curl_handle));
            return false;
        }

        if ($http_code === 429) {
            $this->addError("Rate limited. Please try again later.");
            return false;
        }

        $header_size = curl_getinfo($this->curl_handle, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);

        if ($http_code !== 200) {
            $this->addError("Failed to connect to UniFi controller");
            return false;
        }

        $decoded = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->addError('Invalid response received');
            return false;
        }

        $this->last_response = $decoded;
        return true;
    }

    /**
     * Attempt authentication with UniFi controller
     *
     * @return bool Success status
     */
    protected function login(): bool
    {
        if (!$this->initCurl()) {
            return false;
        }

        $this->handleApiDelay();

        $base_url = rtrim($this->controller_details['baseurl'], '/');

        curl_setopt($this->curl_handle, CURLOPT_URL, $base_url . '/');
        $response = curl_exec($this->curl_handle);
        $http_code = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);

        $this->is_unifi_os = $http_code === 200;

        $this->handleApiDelay();

        $login_url = $base_url . ($this->is_unifi_os ? '/api/auth/login' : '/api/login');

        $payload = json_encode([
            'username' => $this->controller_details['user'],
            'password' => $this->controller_details['password'],
            'remember' => false
        ]);

        curl_setopt_array($this->curl_handle, [
            CURLOPT_URL => $login_url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($this->curl_handle);
        $http_code = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);

        if ($http_code === 429) {
            $this->addError("Rate limited. Please try again later.");
            return false;
        }

        if ($http_code !== 200) {
            $this->addError("Failed to connect to UniFi controller");
            return false;
        }

        $this->is_logged_in = true;
        $this->cookies_created_at = time();
        $this->last_request_time = time();

        return true;
    }

    /**
     * Initialize cURL with default options
     *
     * @return bool Success status
     */
    protected function initCurl(): bool
    {
        if (!extension_loaded('curl')) {
            $this->addError('cURL extension is required');
            return false;
        }

        $this->curl_handle = curl_init();

        curl_setopt_array($this->curl_handle, [
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_HEADERFUNCTION => [$this, 'processResponseHeader']
        ]);

        return true;
    }

    /**
     * Process response headers and extract cookies
     *
     * @param resource $ch cURL handle
     * @param string $header Header line
     * @return int Header length
     */
    public function processResponseHeader($ch, $header): int
    {
        $length = strlen($header);
        $header = explode(':', $header, 2);

        if (count($header) < 2) {
            return $length;
        }

        if (strtolower(trim($header[0])) == 'set-cookie') {
            $cookies = explode(';', $header[1]);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie, 2);
                if (count($parts) == 2) {
                    $this->cookies[trim($parts[0])] = trim($parts[1]);
                }
            }
        }

        return $length;
    }

    /**
     * Get authentication headers
     *
     * @return array HTTP headers
     */
    protected function getAuthHeaders(): array
    {
        $headers = $this->headers;

        if ($this->is_unifi_os && !empty($this->cookies['TOKEN'])) {
            $jwt = explode('.', $this->cookies['TOKEN'])[1];
            $csrf_token = json_decode(base64_decode($jwt))->csrfToken;
            $headers[] = 'x-csrf-token: ' . $csrf_token;
        }

        return $headers;
    }

    /**
     * Format cookies for request header
     *
     * @return string Formatted cookie string
     */
    protected function formatCookies(): string
    {
        return implode('; ', array_map(
            fn($name, $value) => "$name=$value",
            array_keys($this->cookies),
            array_values($this->cookies)
        ));
    }

    /**
     * Add error message to stack
     *
     * @param string $message Error message
     * @return void
     */
    protected function addError(string $message): void
    {
        $this->errors[] = $message;
        $this->debugLog("Error: $message");
    }

    /**
     * Log debug information
     *
     * @param string $message Debug message
     * @param mixed $data Optional data to log
     * @return void
     */
    protected function debugLog(string $message, mixed $data = null): void
    {
        if ($this->debug_mode) {
            pr($message);
            if ($data !== null) {
                pr($data);
            }
        }
    }

    /**
     * Clean up resources on destruction
     */
    public function __destruct()
    {
        if ($this->curl_handle) {
            curl_close($this->curl_handle);
        }
    }
}
