<?php
require_once('../../../wp-load.php');
global $wpdb;

// Post data
$post_data = array(
    'post_author'           => 1,
    'post_date'             => current_time('mysql'),
    'post_date_gmt'         => current_time('mysql', 1),
    'post_content'          => '',
    'post_title'            => 'Test Controller',
    'post_excerpt'          => '',
    'post_status'           => 'publish',
    'comment_status'        => 'closed',
    'ping_status'           => 'closed',
    'post_password'         => '',
    'post_name'             => 'test-controller',
    'to_ping'               => '',
    'pinged'                => '',
    'post_modified'         => current_time('mysql'),
    'post_modified_gmt'     => current_time('mysql', 1),
    'post_content_filtered' => '',
    'post_parent'           => 0,
    'guid'                  => '',
    'menu_order'            => 0,
    'post_type'             => 'unifi-controller',
    'post_mime_type'        => '',
    'comment_count'         => 0,
);

// Insert the post
$wpdb->insert($wpdb->posts, $post_data);
$post_id = $wpdb->insert_id;

if ($post_id) {
    // Insert meta data
    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => 'sfx_unifi_portal_controller_name', 'meta_value' => 'Test Controller'));
    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => 'sfx_unifi_portal_controller_username', 'meta_value' => 'testuser'));
    $wpdb->insert($wp_postmeta, array('post_id' => $post_id, 'meta_key' => 'sfx_unifi_portal_controller_password', 'meta_value' => 'testpass'));
    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => 'sfx_unifi_portal_controller_url', 'meta_value' => 'https://localhost:8443'));

    echo "Test controller created with ID: " . $post_id;
} else {
    echo "Error creating test controller.";
}
?>
