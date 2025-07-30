<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

// Sample Ajax Call Code ( Archived Code ) Not in usage !!!!

$key = $self::get_param('key');
$method = $self::get_param('method');

$controller_id = Utils::xorDecrypt($key);
$method_name = Utils::xorDecrypt($method);

$controller_name = PostMeta::read($controller_id, Plugin::prefix('controller_name'), true);
$controller_username = PostMeta::read($controller_id, Plugin::prefix('controller_username'), true);
$controller_password = PostMeta::read($controller_id, Plugin::prefix('controller_password'), true);
$controller_url = PostMeta::read($controller_id, Plugin::prefix('controller_url'), true);

$uni_reports = new UnifiController(
    $controller_username,
    $controller_password,
    $controller_url,
);

$uni_reports->connect();
$uni_reports->login();

$uni_connection = $uni_reports->get_connection();

if (method_exists($uni_connection, $method_name)) {

    $result = $uni_connection->$method_name();
} else {

    $result = "Method $method_name does not exist in the UniFi connection.";
}

$self::sendJson(
    [
        'status' => 'success',
        'post' => $_POST,
        'id' => $controller_id,
        'method' => $method_name,
        'data' => [
            'result' => $result,
        ],

    ]
);
