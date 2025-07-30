<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * WordPress Font Loader
 *
 * Handles font loading and font-face CSS generation for WordPress with:
 * - Transient caching for font styles
 * - Progressive font loading strategy
 * - Performance monitoring
 * - Enhanced security validation
 * - Comprehensive debug logging
 */
class FontLoader
{
    /** @var array Font configuration */
    private array $fonts = [];

    /** @var string Asset context (frontend|backend) */
    private string $type = 'frontend';

    /** @var array Allowed pages for asset loading */
    private array $pages = [];

    /** @var string|null WordPress enqueue handle */
    private ?string $enqueueHandle = null;

    /** @var bool Enable debug output */
    private bool $debug = false;

    /** @var array Cache settings */
    private const CACHE_SETTINGS = [
        'key_prefix' => 'font_styles_',
        'expiry' => DAY_IN_SECONDS
    ];

    /** @var array Performance metrics */
    private array $metrics = [];

    /**
     * Set fonts configuration with validation
     */
    public function setFonts(array $fonts): self
    {
        foreach ($fonts as $key => $font) {
            if (!$this->validateFont($font)) {
                unset($fonts[$key]);
                $this->debugLog("Invalid font configuration removed: $key");
            }
        }

        $this->fonts = $fonts;
        return $this;
    }

    /**
     * Set asset context type
     */
    public function type(string $type): self
    {
        if (!in_array($type, ['frontend', 'backend'])) {
            $this->debugLog("Invalid type: $type, defaulting to frontend");
            $type = 'frontend';
        }

        $this->type = $type;
        return $this;
    }

    /**
     * Set allowed pages for asset loading
     */
    public function page(array $pages): self
    {
        $this->pages = array_filter($pages, 'is_string');
        return $this;
    }

    /**
     * Set WordPress enqueue handle
     */
    public function setEnqueueHandle(string $handle): self
    {
        $this->enqueueHandle = sanitize_key($handle);
        return $this;
    }

    /**
     * Enable debug mode
     */
    public function debug(bool $enable = false): self
    {
        $this->debug = $enable;
        return $this;
    }

    /**
     * Register font assets
     */
    public function register(): void
    {
        if (!$this->shouldLoadOnPage()) {
            return;
        }

        $this->debugLog('Registering fonts');
        $hook = $this->type === 'backend' ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

        add_action($hook, function () {
            $start = microtime(true);
            $this->enqueueFonts();
            $this->metrics['enqueue_time'] = microtime(true) - $start;
            $this->debugLog('Fonts enqueued', $this->metrics);
        }, 20);
    }

    /**
     * Check if assets should load on current page
     */
    private function shouldLoadOnPage(): bool
    {
        if (empty($this->pages)) {
            return true;
        }

        $current_url = $_SERVER['REQUEST_URI'] ?? '';

        foreach ($this->pages as $page) {
            if (strpos($current_url, "/$page") === 0) {
                $this->debugLog("Font loading allowed for page: $page");
                return true;
            }
        }

        $this->debugLog('Fonts not loaded - URL not in allowed pages');
        return false;
    }

    /**
     * Validate font configuration
     */
    private function validateFont(array $font): bool
    {
        if (empty($font['family']) || !is_string($font['family'])) {
            return false;
        }

        if (empty($font['variants']) || !is_array($font['variants'])) {
            return false;
        }

        foreach ($font['variants'] as $variant) {
            if (!$this->validateVariant($variant)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate font variant configuration
     */
    private function validateVariant(array $variant): bool
    {
        if (!isset($variant['weight']) || !is_numeric($variant['weight'])) {
            return false;
        }

        if (empty($variant['file']) || !preg_match('/^[\w-]+\.woff2$/', $variant['file'])) {
            return false;
        }

        return true;
    }

    /**
     * Enqueue font styles with performance tracking
     */
    private function enqueueFonts(): void
    {
        if (empty($this->enqueueHandle) || empty($this->fonts)) {
            $this->debugLog('Missing required configuration');
            return;
        }

        $start = microtime(true);
        $fontOutput = $this->generateFontFaces();
        $this->metrics['generation_time'] = microtime(true) - $start;

        if (empty($fontOutput['styles'])) {
            $this->debugLog('No font-face styles generated');
            return;
        }

        $fontHandle = $this->enqueueHandle . '-fonts';
        wp_register_style($fontHandle, false);
        wp_enqueue_style($fontHandle);

        wp_add_inline_style($fontHandle, $fontOutput['styles']);
        wp_add_inline_style($fontHandle, $this->getFontLoadingStrategy());

        add_action('wp_head', function () use ($fontOutput) {
            echo $fontOutput['preloads'] . "\n";
        }, 1);

        if (wp_script_is($this->enqueueHandle, 'enqueued')) {
            wp_add_inline_script($this->enqueueHandle, $this->getFontLoadingScript());
        }

        $this->debugLog('Font styles and preloads generated');
    }

    /**
     * Generate font-face CSS and preload tags with caching
     */
    private function generateFontFaces(): array
    {
        $cache_key = self::CACHE_SETTINGS['key_prefix'] . md5(serialize($this->fonts));
        $cached = get_transient($cache_key);

        if ($cached) {
            $this->debugLog('Using cached font styles');
            return $cached;
        }

        $styles = [];
        $preloads = [];

        foreach ($this->fonts as $font) {
            foreach ($font['variants'] as $variant) {
                $fontUrl = Plugin::fonts_url($variant['file']);
                $this->debugLog("Font URL generated: {$fontUrl}");

                $styles[] = sprintf(
                    "@font-face {
                        font-family: '%s';
                        src: url('%s') format('woff2');
                        font-weight: %d;
                        font-style: normal;
                        font-display: swap;
                    }",
                    esc_attr($font['family']),
                    esc_url($fontUrl),
                    intval($variant['weight'])
                );

                $preloads[] = sprintf(
                    '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>',
                    esc_url($fontUrl)
                );
            }
        }

        $styles[] = "
        html {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        code, pre {
            font-family: 'JetBrains Mono', monospace;
        }";

        $output = [
            'styles' => implode("\n", $styles),
            'preloads' => implode("\n", $preloads)
        ];

        set_transient($cache_key, $output, self::CACHE_SETTINGS['expiry']);

        return $output;
    }

    /**
     * Progressive font loading strategy
     */
    private function getFontLoadingStrategy(): string
    {
        return "
        .fonts-stage-1 {
            font-family: system-ui, -apple-system, sans-serif;
        }

        .fonts-stage-2 {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .fonts-stage-1 code,
        .fonts-stage-1 pre {
            font-family: monospace;
        }

        .fonts-stage-2 code,
        .fonts-stage-2 pre {
            font-family: 'JetBrains Mono', monospace;
        }";
    }

    /**
     * Progressive font loading script with performance monitoring
     */
    private function getFontLoadingScript(): string
    {
        return "(() => {
        const html = document.documentElement;
        const startTime = performance.now();

        html.classList.add('fonts-stage-1');

        Promise.all([
            document.fonts.load('400 1em Inter'),
            document.fonts.load('500 1em Inter')
        ])
        .then(() => {
            html.classList.add('fonts-stage-2');

            return Promise.all([
                document.fonts.load('600 1em Inter'),
                document.fonts.load('700 1em Inter'),
                document.fonts.load('400 1em JetBrains Mono'),
                document.fonts.load('500 1em JetBrains Mono'),
                document.fonts.load('700 1em JetBrains Mono')
            ]);
        })
        .then(() => {
            const loadTime = performance.now() - startTime;
            console.log('Fonts loaded in ' + Math.round(loadTime) + 'ms');
        });
    })();";
    }

    /**
     * Enhanced debug logging with performance metrics
     */
    private function debugLog(string $message, array $metrics = []): void
    {
        if (!$this->debug) {
            return;
        }

        $log = ["FontLoader: $message"];

        if (!empty($metrics)) {
            $log[] = 'Metrics:';
            foreach ($metrics as $key => $value) {
                $log[] = sprintf("  %s: %.4f seconds", $key, $value);
            }
        }

        pr(implode("\n", $log));
    }
}
