<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Frontend Template Builder
 *
 * Generates optimized frontend templates for the Unifi Portal
 * with core WordPress assets and Vue.js integration points
 */
class FrontendTemplateBuilder
{
    /** @var array Bricks theme assets to remove */
    protected const BRICKS_ASSETS = [
        'styles' => [
            'bricks-frontend',
            'bricks-animations',
            'bricks-element-accordion',
            'bricks-element-alert',
            'bricks-element-audio',
            'bricks-element-button',
            'bricks-element-carousel',
            'bricks-element-container',
            'bricks-element-counter',
            'bricks-element-form',
            'bricks-element-icon',
            'bricks-element-image',
            'bricks-element-list',
            'bricks-element-map',
            'bricks-element-nav-menu',
            'bricks-element-pagination',
            'bricks-element-pricing-tables',
            'bricks-element-progress-bar',
            'bricks-element-search',
            'bricks-element-sidebar',
            'bricks-element-slider',
            'bricks-element-social',
            'bricks-element-tabs',
            'bricks-element-team-members',
            'bricks-element-testimonials',
            'bricks-element-video',
            'bricks-tooltips'
        ],
        'scripts' => [
            'bricks-scripts',
            'bricks-frontend',
            'bricks-flatpickr',
            'bricks-photoswipe',
            'bricks-element-accordion',
            'bricks-element-carousel',
            'bricks-element-counter',
            'bricks-element-form',
            'bricks-element-map',
            'bricks-element-nav-menu',
            'bricks-element-slider',
            'bricks-element-tabs'
        ]
    ];

    /**
     * Generate complete HTML template
     */
    public static function html(string $content): string
    {
        $instance = new self();
        $instance->cleanupWordPress();

        ob_start();
        $instance->renderTemplate($content);
        return ob_get_clean();
    }

    /**
     * Remove unnecessary WordPress elements
     */
    protected function cleanupWordPress(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'cleanupAssets'], 999);
        add_filter('bricks_load_assets', '__return_false');
    }

    /**
     * Remove Bricks theme styles and scripts
     */
    public function cleanupAssets(): void
    {
        foreach (self::BRICKS_ASSETS['styles'] as $handle) {
            wp_deregister_style($handle);
            wp_dequeue_style($handle);
        }

        foreach (self::BRICKS_ASSETS['scripts'] as $handle) {
            wp_deregister_script($handle);
            wp_dequeue_script($handle);
        }
    }

    /**
     * Render the HTML template
     */
    protected function renderTemplate(string $content): void
    {
?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>

        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php wp_title('|', true, 'right'); ?></title>
            <?php wp_head(); ?>
        </head>

        <body class="unifi-portal-page">
            <?php echo $content; ?>
            <?php wp_footer(); ?>
        </body>

        </html>
<?php
    }
}
