<?php

namespace Sfx\UnifiPortal;

if (!defined('ABSPATH')) {
    exit;
}

class PostMeta
{
    /**
     * Adds post meta for a specific post if not empty.
     *
     * @param int $post_id The ID of the post.
     * @param string $meta_key The meta key.
     * @param mixed $meta_value The meta value.
     * @return bool True if the meta key was successfully added, false otherwise.
     */
    public static function create(int $post_id, string $meta_key, $meta_value): bool
    {
        if (empty($post_id) || empty($meta_key) || empty($meta_value)) {
            return false;
        }

        return add_post_meta($post_id, $meta_key, $meta_value, true) !== false;
    }

    /**
     * Retrieves post meta for a specific post.
     *
     * @param int $post_id The ID of the post.
     * @param string $meta_key The meta key.
     * @param bool $single Whether to return a single value (default is false).
     * @return mixed The value of the meta field or null if empty.
     */
    public static function read(int $post_id, string $meta_key, bool $single = false)
    {
        if (empty($post_id) || empty($meta_key)) {
            return null;
        }

        $meta_value = get_post_meta($post_id, $meta_key, $single);
        return !empty($meta_value) ? $meta_value : null;
    }

    /**
     * Safely updates a meta field, adding it if it doesn't exist.
     *
     * @param int $post_id The ID of the post.
     * @param string $meta_key The meta key.
     * @param mixed $meta_value The meta value.
     * @return bool True on success, false on failure.
     */
    public static function update(int $post_id, string $meta_key, $meta_value): bool
    {
        if (empty($post_id) || empty($meta_key) || empty($meta_value)) {
            return false;
        }

        $current_value = self::read($post_id, $meta_key, true);

        if ($current_value === null) {
            return self::create($post_id, $meta_key, $meta_value);
        }

        return update_post_meta($post_id, $meta_key, $meta_value) !== false;
    }

    /**
     * Deletes a specific post meta if it exists.
     *
     * @param int $post_id The ID of the post.
     * @param string $meta_key The meta key.
     * @return bool True if the meta was successfully deleted, false otherwise.
     */
    public static function delete(int $post_id, string $meta_key): bool
    {
        if (empty($post_id) || empty($meta_key) || self::read($post_id, $meta_key, true) === null) {
            return false;
        }

        return delete_post_meta($post_id, $meta_key) !== false;
    }
}
