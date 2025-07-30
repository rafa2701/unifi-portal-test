<?php
/**
 * Plugin Name: Test Loader
 * Description: A test plugin to create a Unifi Controller post.
 * Version: 1.0
 * Author: Jules
 */

function create_test_unifi_controller() {
    // Create a new post
    $post_data = array(
        'post_title'    => 'Test Controller',
        'post_name'     => 'test-controller',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'unifi-controller',
    );

    // Insert the post into the database
    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Add the meta data
        add_post_meta($post_id, 'sfx_unifi_portal_controller_name', 'Test Controller');
        add_post_meta($post_id, 'sfx_unifi_portal_controller_username', 'testuser');
        add_post_meta($post_id, 'sfx_unifi_portal_controller_password', 'testpass');
        add_post_meta($post_id, 'sfx_unifi_portal_controller_url', 'https://localhost:8443');
    }
}

register_activation_hook(__FILE__, 'create_test_unifi_controller');
?>
