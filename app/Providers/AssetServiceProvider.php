<?php

namespace ElementorWcMeta\Providers;

use ElementorWcMeta\Assets\AssetManager;

/**
 * Asset Service Provider
 */
class AssetServiceProvider extends ServiceProvider
{
    /**
     * Register asset services
     */
    public function register(): void
    {
        $assetManager = new AssetManager();
        
        // Enqueue admin assets
        add_action('admin_enqueue_scripts', [$assetManager, 'enqueueAdminAssets']);
        
        // Enqueue frontend assets
        add_action('wp_enqueue_scripts', [$assetManager, 'enqueueFrontendAssets']);
        
        // Enqueue Elementor editor assets
        add_action('elementor/editor/after_enqueue_scripts', [$assetManager, 'enqueueEditorAssets']);
    }
}
