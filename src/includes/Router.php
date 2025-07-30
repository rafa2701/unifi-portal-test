<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Class Router
 *
 * A utility class for registering REST API routes in WordPress.
 */
class Router
{
    /**
     * The namespace for the REST API routes.
     *
     * @var string
     */
    private $namespace = "";

    /**
     * Router constructor.
     *
     * @param string $namespace The namespace for the REST API routes.
     */
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Registers a REST API route.
     *
     * @param string $method The HTTP method (e.g., GET, POST).
     * @param string $endpoint The API endpoint (e.g., '/items/{id}').
     * @param callable $callback The callback function to handle the request.
     * @param array|callable $permissions The permission callback or array of capabilities.
     * @return $this
     */
    public function route($method, $endpoint, $callback, $permissions = [])
    {
        $endpoint = str_replace("{id}", "(?P<id>[\d]+)", $endpoint);

        register_rest_route($this->namespace, $endpoint, [
            "methods" => $method,
            "callback" => function ($request) use ($callback) {
                $result = call_user_func($callback, $request);

                if (is_wp_error($result)) {
                    return new \WP_REST_Response(
                        [
                            "code" => $result->get_error_code(),
                            "message" => $result->get_error_message(),
                            "data" => $result->get_error_data(),
                        ],
                        422
                    );
                }

                ob_get_clean();

                return rest_ensure_response($result);
            },
            "permission_callback" => function ($request) use ($permissions) {
                if (is_array($permissions)) {
                    if (count($permissions)) {
                        foreach ($permissions as $permission) {
                            if (current_user_can($permission)) {
                                return true;
                            }
                        }
                        return false;
                    }
                    return true;
                }

                return call_user_func($permissions, $request);
            },
        ]);

        return $this;
    }

    /**
     * Registers a GET route.
     *
     * @param string $endpoint The API endpoint (e.g., '/items/{id}').
     * @param callable $callback The callback function to handle the request.
     * @param array|callable $permissions The permission callback or array of capabilities.
     * @return $this
     */
    public function get($endpoint, $callback, $permissions = [])
    {
        $this->route(
            \WP_REST_Server::READABLE,
            $endpoint,
            $callback,
            $permissions
        );
        return $this;
    }

    /**
     * Registers a POST route.
     *
     * @param string $endpoint The API endpoint (e.g., '/items').
     * @param callable $callback The callback function to handle the request.
     * @param array|callable $permissions The permission callback or array of capabilities.
     * @return $this
     */
    public function post($endpoint, $callback, $permissions = [])
    {
        $this->route(
            \WP_REST_Server::CREATABLE,
            $endpoint,
            $callback,
            $permissions
        );
        return $this;
    }
}
