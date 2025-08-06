<?php

namespace ElementorWcMeta\Assets;

use ElementorWcMeta\Foundation\Application;

/**
 * Asset Manager - handles all CSS/JS loading with Vite integration
 */
class AssetManager
{
    private Application $app;
    private ?array $manifest = null;

    public function __construct()
    {
        $this->app = Application::getInstance();
        $this->loadManifest();
    }

    /**
     * Load Vite manifest file
     */
    private function loadManifest(): void
    {
        $manifestPath = $this->app->path('public/dist/.vite/manifest.json');
        
        if (file_exists($manifestPath)) {
            $this->manifest = json_decode(file_get_contents($manifestPath), true);
        }
    }

    /**
     * Get asset URL from manifest or fallback
     */
    private function getAssetUrl(string $entry): ?string
    {
        if ($this->manifest && isset($this->manifest[$entry])) {
            return $this->app->url('public/dist/' . $this->manifest[$entry]['file']);
        }

        // Fallback for development
        $fallbackPath = str_replace(['resources/', '.scss'], ['public/dist/', '.css'], $entry);
        return $this->app->url($fallbackPath);
    }

    /**
     * Enqueue admin assets
     */
    public function enqueueAdminAssets(): void
    {
        $screen = get_current_screen();
        
        if (!$screen || !in_array($screen->id, ['plugins', 'elementor_page_elementor-tools'])) {
            return;
        }

        // Admin CSS
        $adminCss = $this->getAssetUrl('resources/scss/style.scss');
        if ($adminCss) {
            wp_enqueue_style(
                'elementor-wc-meta-admin',
                $adminCss,
                [],
                $this->app->version()
            );
        }

        // Admin JS
        $adminJs = $this->getAssetUrl('resources/js/admin.js');
        if ($adminJs) {
            wp_enqueue_script(
                'elementor-wc-meta-admin',
                $adminJs,
                ['jquery'],
                $this->app->version(),
                true
            );
        }
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueueFrontendAssets(): void
    {
        // Only load on pages with Elementor content or WooCommerce pages
        if (!$this->shouldLoadFrontendAssets()) {
            return;
        }

        // Frontend CSS
        $frontendCss = $this->getAssetUrl('resources/scss/style.scss');
        if ($frontendCss) {
            wp_enqueue_style(
                'elementor-wc-meta-frontend',
                $frontendCss,
                [],
                $this->app->version()
            );
        }

        // Frontend JS
        $frontendJs = $this->getAssetUrl('resources/js/frontend.js');
        if ($frontendJs) {
            wp_enqueue_script(
                'elementor-wc-meta-frontend',
                $frontendJs,
                ['jquery'],
                $this->app->version(),
                true
            );
        }

        // Localize script with AJAX data
        wp_localize_script('elementor-wc-meta-frontend', 'elementorWcMeta', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('elementor_wc_meta_nonce'),
        ]);
    }

    /**
     * Enqueue Elementor editor assets
     */
    public function enqueueEditorAssets(): void
    {
        // Editor JS
        $editorJs = $this->getAssetUrl('resources/js/editor.js');
        if ($editorJs) {
            wp_enqueue_script(
                'elementor-wc-meta-editor',
                $editorJs,
                ['elementor-editor'],
                $this->app->version(),
                true
            );
        }
    }

    /**
     * Check if frontend assets should be loaded
     */
    private function shouldLoadFrontendAssets(): bool
    {
        global $post;

        // Check if it's a WooCommerce page
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            return true;
        }

        // Check if page has Elementor content
        if ($post && get_post_meta($post->ID, '_elementor_edit_mode', true)) {
            return true;
        }

        // Check if it's a loop template or archive
        if (is_archive() || is_home() || is_search()) {
            return true;
        }

        return false;
    }
}
