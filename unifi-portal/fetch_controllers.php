<?php
// Load WordPress
if (isset($_GET['fetch_controllers'])) {
    require_once('../../../wp-load.php');

    // Ensure the user is an administrator
    if (!current_user_can('manage_options')) {
        // Silently fail if the user is not an admin.
        // This is not a secure way to handle this, but it's fine for this test.
        exit;
    }

    // Create a mock request object
    $request = new WP_REST_Request('GET', '/unifi-portal/v1/fetch-controllers');

    // Call the fetchControllers method
    $response = Sfx\UnifiPortal\UnifiPortalController::fetchControllers($request);

    // Print the response
    print_r($response);
}
?>
