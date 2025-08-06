<?php

namespace ElementorWcMeta\Providers;

/**
 * Internationalization Service Provider
 * Handles text domain loading at the correct WordPress hook
 */
class I18nServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Register textdomain loading for init hook
        add_action('init', [$this, 'loadTextdomain']);
    }

    /**
     * Boot services
     */
    public function boot(): void
    {
        // Nothing to boot immediately
    }

    /**
     * Load plugin textdomain for translations
     */
    public function loadTextdomain(): void
    {
        load_plugin_textdomain(
            'elementor-wc-meta',
            false,
            dirname(plugin_basename($this->app->getPluginFile())) . '/languages'
        );
    }
}
