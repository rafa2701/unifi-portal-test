<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Admin Asset Registration Handler
 */
class AdminAssetsRegister
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
            'site' => [
                'url' => Plugin::site_url(),
            ],
            'admin' => [
                'url' => Plugin::admin_url(),
            ],
            'image' => [
                'url' => Plugin::image_url(),
            ],
            'ajax' => [
                'url' => Ajax::url(),
                'nonce' => Ajax::generateNonce(),
            ],
            'rest' => [
                'url' => Plugin::rest_url(),
                'nonce' => Plugin::rest_nonce(),
            ],
        ];

        $vite = new ViteAdmin();
        $vite->debug(false);

        $vite->page(['unifi-portal', 'unifi-browser'])
            ->setEnqueueHandle('unifi-portal-admin')
            ->setEntryFile('unifi-portal/unifi-portal.js')
            ->setLocalizeData('UnifiPortalData', $script_data)
            ->register();

        $fonts = new FontLoader();
        $fonts->debug(false);

        $fonts->type('backend')
            ->page(['unifi-portal', 'unifi-browser'])
            ->setEnqueueHandle('unifi-portal-admin')
            ->setFonts(self::$fonts)
            ->register();
    }
}
