<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

wp_nonce_field('unifi_controller_nonce', 'unifi_controller_nonce_field');

$multiMeta = new MultiPostMeta();
$multiMeta->setId($post_id)

    ->prefix(Plugin::prefix())
    ->setFields([
        'controller_name',
        'controller_username',
        'controller_password',
        'controller_url',
    ]);

$controller_values = $multiMeta->read() ?? [];

$fields = [
    'controller_name' => [
        'label' => 'Controller Name',
        'type' => 'text',
        'placeholder' => 'Add a descriptive name for the controller',
        'required' => true,
        'default' => ''
    ],
    'controller_username' => [
        'label' => 'Username',
        'type' => 'text',
        'placeholder' => 'Enter the username for API access',
        'required' => true,
        'default' => ''
    ],
    'controller_password' => [
        'label' => 'Password',
        'type' => 'password',
        'placeholder' => 'Enter the password for API access',
        'required' => true,
        'default' => ''
    ],
    'controller_url' => [
        'label' => 'Controller URL',
        'type' => 'url',
        'placeholder' => 'Add the full URL of the controller (e.g., https://50.xxx.xxx.xx)',
        'required' => true,
        'default' => ''
    ],
];

?>

<style>
    .unifi-fields-container {
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        gap: 15px;
    }

    .unifi-field {
        flex: 1;
        min-width: 250px;
    }

    .unifi-field label {
        display: block;
        margin-bottom: 5px;
    }

    .unifi-field input {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .unifi-required {
        color: #ff007f;
        margin-left: 5px;
    }
</style>

<div class="unifi-fields-container">

    <?php foreach ($fields as $field_name => $field): ?>

        <div class="unifi-field">
            <label for="<?php echo esc_attr($field_name); ?>">
                <?php echo esc_html($field['label']); ?>
                <?php if (!empty($field['required'])): ?>
                    <span class="unifi-required">*</span>
                <?php endif; ?>
            </label>
            <input
                type="<?php echo esc_attr($field['type']); ?>"
                id="<?php echo esc_attr($field_name); ?>"
                name="<?php echo esc_attr($field_name); ?>"
                value="<?php echo esc_attr($controller_values[$field_name] ?? $field['default']); ?>"
                placeholder="<?php echo esc_attr($field['placeholder'] ?? ''); ?>"
                <?php echo !empty($field['required']) ? 'required' : ''; ?> />
        </div>

    <?php endforeach; ?>

</div>
