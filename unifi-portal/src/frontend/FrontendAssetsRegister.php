<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Frontend Asset Registration Handler
 */
class FrontendAssetsRegister
{
    /** @var array Font configuration */
    private static array $fonts = [
        'inter' => [
            'family' => 'Inter',
            'variants' => [
                ['weight' => 400, 'file' => 'Inter-Regular.woff2'],
                ['weight' => 500, 'file' => 'Inter-Medium.woff2'],
                ['weight' => 600, 'file' => 'Inter-SemiBold.woff2'],
                ['weight' => 700, 'file' => 'Inter-Bold.woff2']
            ]
        ],
        'jetbrains-mono' => [
            'family' => 'JetBrains Mono',
            'variants' => [
                ['weight' => 400, 'file' => 'JetBrainsMono-Regular.woff2'],
                ['weight' => 500, 'file' => 'JetBrainsMono-Medium.woff2'],
                ['weight' => 700, 'file' => 'JetBrainsMono-Bold.woff2']
            ]
        ]
    ];

    /**
     * Initialize asset registration
     */
    public static function init(): void
    {

        $script_data = [
            'ajax' => [
                'url' => Ajax::url(),
                'nonce' => Ajax::generateNonce(),
            ],
            'rest' => [
                'url' => Plugin::rest_url(),
                'nonce' => Plugin::rest_nonce(),
            ],
            'site' => [
                'url' => Plugin::site_url(),
            ],
            'image' => [
                'url' => Plugin::image_url(),
            ],
            'api' => self::getUnifiApiData()
        ];

        $vite = new ViteFrontend();
        $vite->debug(false);

        $vite->page(['unifi-portal', 'unifi-browser'])
            ->setEnqueueHandle('unifi-portal-frontend')
            ->setEntryFile('unifi-portal/unifi-portal.js')
            ->setLocalizeData('UnifiPortalData', $script_data)
            ->register();

        $fonts = new FontLoader();
        $fonts->debug(false);

        $fonts->type('frontend')
            ->page(['unifi-portal', 'unifi-browser'])
            ->setEnqueueHandle('unifi-portal-frontend')
            ->setFonts(self::$fonts)
            ->register();
    }

    /**
     * Fetch UniFi API data from database
     *
     * @return array API data organized by controller ID
     */
    private static function getUnifiApiData(): array
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'unifi_api_data';
        $results = $wpdb->get_results(
            "SELECT controller_id,
                    controller_name,
                    dashboard_data,
                    self_data,
                    devices_data,
                    clients_data,
                    health_data
             FROM {$table_name}
             ORDER BY created_at DESC",
            ARRAY_A
        );

        $api_data = [];
        foreach ($results as $row) {
            $api_data[$row['controller_id']] = [
                'name' => $row['controller_name'],
                'dashboard' => json_decode($row['dashboard_data'], true),
                'self' => json_decode($row['self_data'], true),
                'devices' => json_decode($row['devices_data'], true),
                'clients' => json_decode($row['clients_data'], true),
                'health' => json_decode($row['health_data'], true)
            ];
        }

        return $api_data;
    }
}
