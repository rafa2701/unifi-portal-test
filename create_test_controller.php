<?php
// Load WordPress
if (isset($_GET['create_test_controller'])) {
    require_once('../../../wp-load.php');

    // Ensure the user is an administrator
    if (!current_user_can('manage_options')) {
        // Silently fail if the user is not an admin.
        // This is not a secure way to handle this, but it's fine for this test.
        exit;
    }

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

        echo "Test controller created with ID: " . $post_id;
    } else {
        echo "Error creating test controller.";
    }
}
?>
