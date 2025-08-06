<?php

namespace ElementorWcMeta\Foundation;

use ElementorWcMeta\Providers\ServiceProvider;
use ElementorWcMeta\Providers\I18nServiceProvider;
use ElementorWcMeta\Providers\ElementorServiceProvider;
use ElementorWcMeta\Providers\WooCommerceServiceProvider;
use ElementorWcMeta\Providers\AssetServiceProvider;
use ElementorWcMeta\Providers\HposCompatibilityServiceProvider;

/**
 * Main Application class - similar to Laravel's Application
 */
class Application
{
    private static ?self $instance = null;
    private array $providers = [];
    private bool $booted = false;

    /**
     * Service providers to register
     */
    private array $serviceProviders = [
        I18nServiceProvider::class,
        HposCompatibilityServiceProvider::class,
        ElementorServiceProvider::class,
        WooCommerceServiceProvider::class,
        AssetServiceProvider::class,
    ];

    /**
     * Get singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Boot the application
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        // Check dependencies
        if (!$this->checkDependencies()) {
            return;
        }

        // Register service providers
        $this->registerProviders();

        // Boot service providers
        $this->bootProviders();

        $this->booted = true;
    }

    /**
     * Check if required dependencies are active
     */
    private function checkDependencies(): bool
    {
        $missing = [];

        // Check if Elementor is active
        if (!did_action('elementor/loaded')) {
            $missing[] = 'Elementor';
        }

        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            $missing[] = 'WooCommerce';
        }

        if (!empty($missing)) {
            add_action('admin_notices', function() use ($missing) {
                echo '<div class="notice notice-error"><p>';
                printf(
                    __('Elementor WC Meta requires the following plugins to be active: %s', 'elementor-wc-meta'),
                    implode(', ', $missing)
                );
                echo '</p></div>';
            });
            return false;
        }

        return true;
    }

    /**
     * Register all service providers
     */
    private function registerProviders(): void
    {
        foreach ($this->serviceProviders as $providerClass) {
            $provider = new $providerClass($this);
            $this->providers[] = $provider;
            $provider->register();
        }
    }

    /**
     * Boot all service providers
     */
    private function bootProviders(): void
    {
        foreach ($this->providers as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }
    }

    /**
     * Plugin activation
     */
    public function activate(): void
    {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate(): void
    {
        // Cleanup if needed
        flush_rewrite_rules();
    }

    /**
     * Get plugin version
     */
    public function version(): string
    {
        return ELEMENTOR_WC_META_VERSION;
    }

    /**
     * Get plugin path
     */
    public function path(string $path = ''): string
    {
        return ELEMENTOR_WC_META_PLUGIN_DIR . ltrim($path, '/');
    }

    /**
     * Get plugin URL
     */
    public function url(string $path = ''): string
    {
        return ELEMENTOR_WC_META_PLUGIN_URL . ltrim($path, '/');
    }

    /**
     * Get plugin file path
     */
    public function getPluginFile(): string
    {
        return ELEMENTOR_WC_META_PLUGIN_FILE;
    }
}
