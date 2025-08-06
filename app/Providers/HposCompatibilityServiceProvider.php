<?php

namespace ElementorWcMeta\Providers;

/**
 * HPOS Compatibility Service Provider
 * Handles WooCommerce High-Performance Order Storage compatibility
 */
class HposCompatibilityServiceProvider extends ServiceProvider
{
    /**
     * Register HPOS compatibility services
     */
    public function register(): void
    {
        // Declare HPOS compatibility early
        add_action('before_woocommerce_init', [$this, 'declareHposCompatibility']);
        
        // Add admin notices for HPOS status
        add_action('admin_notices', [$this, 'displayHposNotices']);
    }

    /**
     * Boot HPOS compatibility services
     */
    public function boot(): void
    {
        // Additional HPOS-specific initialization if needed
        $this->initializeHposSupport();
    }

    /**
     * Declare HPOS compatibility with WooCommerce
     */
    public function declareHposCompatibility(): void
    {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            // Declare compatibility with Custom Order Tables (HPOS)
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'custom_order_tables',
                ELEMENTOR_WC_META_PLUGIN_FILE,
                true
            );

            // Declare compatibility with Cart & Checkout Blocks
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'cart_checkout_blocks',
                ELEMENTOR_WC_META_PLUGIN_FILE,
                true
            );

            // Declare compatibility with Product Block Editor
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'product_block_editor',
                ELEMENTOR_WC_META_PLUGIN_FILE,
                true
            );
        }
    }

    /**
     * Display admin notices about HPOS compatibility
     */
    public function displayHposNotices(): void
    {
        // Only show on plugin pages and WooCommerce pages
        $screen = get_current_screen();
        if (!$screen || !in_array($screen->id, ['plugins', 'woocommerce_page_wc-settings'])) {
            return;
        }

        // Check if HPOS is enabled
        if ($this->isHposEnabled()) {
            $this->showHposActiveNotice();
        }
    }

    /**
     * Initialize HPOS-specific support
     */
    private function initializeHposSupport(): void
    {
        if (!$this->isHposEnabled()) {
            return;
        }

        // Add any HPOS-specific hooks or modifications here
        // For this plugin, we mainly work with product meta, so no major changes needed
        
        // Log HPOS compatibility status
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[Elementor WC Meta] HPOS compatibility enabled and working.');
        }
    }

    /**
     * Check if HPOS is enabled
     */
    private function isHposEnabled(): bool
    {
        if (!class_exists('\Automattic\WooCommerce\Utilities\OrderUtil')) {
            return false;
        }

        return \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
    }

    /**
     * Show notice when HPOS is active
     */
    private function showHposActiveNotice(): void
    {
        // Only show once per session
        if (get_transient('elementor_wc_meta_hpos_notice_shown')) {
            return;
        }

        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>' . __('Elementor WC Meta', 'elementor-wc-meta') . '</strong>: ';
        echo __('Plugin is fully compatible with WooCommerce High-Performance Order Storage (HPOS).', 'elementor-wc-meta');
        echo '</p>';
        echo '</div>';

        // Set transient to avoid showing repeatedly
        set_transient('elementor_wc_meta_hpos_notice_shown', true, DAY_IN_SECONDS);
    }

    /**
     * Get HPOS compatibility status for debugging
     */
    public function getCompatibilityStatus(): array
    {
        return [
            'hpos_available' => class_exists('\Automattic\WooCommerce\Utilities\OrderUtil'),
            'hpos_enabled' => $this->isHposEnabled(),
            'features_util_available' => class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil'),
            'plugin_compatible' => true, // Our plugin is compatible
        ];
    }
}
