<?php

namespace Sfx\UnifiPortal;

use Exception;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

// pr("Test");

// fetch controllers
// $controllers = UnifiController::getControllerPosts();
// $controller_data = [];

// foreach ($controllers as $controller) {
//     $controller_id = $controller->ID;
//     $controller_name = PostMeta::read(
//         $controller_id,
//         Plugin::prefix("controller_name"),
//         true
//     );

//     $controller_key = Utils::xorEncrypt(strval($controller_id));
//     $controller_data[] = [
//         "name" => esc_html($controller_name),
//         "key" => $controller_key,
//     ];
// }

// pr(
//     [
//         "status" => "success",
//         "data" => ["controllers" => $controller_data],
//     ]
// );


// $key  = '6Ak=';
// $controller_id = Utils::xorDecrypt(WPSanitize::text_field($key));

// // test connection
// $unifi_controller = new UnifiController();
// $unifi_controller->debug(false)
//     ->setControllerId($controller_id)
//     ->retrieveControllerDetails()
//     ->tryApiConnection();

// $connection = $unifi_controller->testConnection();

// if ($unifi_controller->isError()) {
//     pr([
//         "status" => "failed",
//         "error" => $errors,
//     ]);
// }

// pr(
//     [
//         "status" => "success",
//         "connection" => $connection,

//     ]
// );

// // fetch sites
// $sites = [];
// $error = '';

// $unifi_controller = new UnifiController();
// $unifi_controller->debug(false);

// $unifi_controller->setControllerId($controller_id)
//     ->retrieveControllerDetails()
//     ->tryApiConnection();

// $sites_list = $unifi_controller->loadAvailableSites();

// if ($unifi_controller->isError()) {

//     $errors = $unifi_controller->getErrors();

//     pr([
//         "status" => "failed",
//         "error" => $errors,
//     ]);
// }

// foreach ($sites_list as $site):

//     $site_name = $site['name'];
//     $sites[] = $site_name;

// endforeach;

// pr([
//     "status" => "success",
//     'sites' => $sites,
// ]);

// exit;
