<?php

namespace ElementorWcMeta\Providers;

use ElementorWcMeta\WooCommerce\MetaFieldsManager;

/**
 * WooCommerce Service Provider
 */
class WooCommerceServiceProvider extends ServiceProvider
{
    /**
     * Register WooCommerce services
     */
    public function register(): void
    {
        // Initialize meta fields manager
        new MetaFieldsManager();
    }

    /**
     * Boot WooCommerce services
     */
    public function boot(): void
    {
        // Additional WooCommerce initialization
    }
}
