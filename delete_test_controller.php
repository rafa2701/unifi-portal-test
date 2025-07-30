<?php
// Load WordPress
if (isset($_GET['delete_test_controller'])) {
    require_once('../../../wp-load.php');

    // Ensure the user is an administrator
    if (!current_user_can('manage_options')) {
        // Silently fail if the user is not an admin.
        // This is not a secure way to handle this, but it's fine for this test.
        exit;
    }

    // Get the post by title
    $post = get_page_by_title('Test Controller', OBJECT, 'unifi-controller');

    if ($post) {
        // Delete the post
        wp_delete_post($post->ID, true);
        echo "Test controller deleted.";
    } else {
        echo "Test controller not found.";
    }
}
?>
