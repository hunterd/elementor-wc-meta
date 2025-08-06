<?php

namespace ElementorWcMeta\Utils;

/**
 * Helper utilities for the plugin
 */
class Helpers
{
    /**
     * Check if WooCommerce is active
     */
    public static function isWooCommerceActive(): bool
    {
        return class_exists('WooCommerce');
    }

    /**
     * Check if Elementor is active
     */
    public static function isElementorActive(): bool
    {
        return did_action('elementor/loaded') > 0;
    }

    /**
     * Check if we're in Elementor editor mode
     */
    public static function isElementorEditor(): bool
    {
        return self::isElementorActive() && \Elementor\Plugin::$instance->editor->is_edit_mode();
    }

    /**
     * Check if current page is a product page
     */
    public static function isProductPage(): bool
    {
        return function_exists('is_product') && is_product();
    }

    /**
     * Check if current page is a WooCommerce page
     */
    public static function isWooCommercePage(): bool
    {
        if (!self::isWooCommerceActive()) {
            return false;
        }

        return function_exists('is_woocommerce') && is_woocommerce();
    }

    /**
     * Get current product ID from various contexts
     */
    public static function getCurrentProductId(): ?int
    {
        global $product, $post;

        // Try to get product from global $product first (loop context)
        if ($product && is_a($product, 'WC_Product')) {
            return $product->get_id();
        }

        // Try to get product from post
        if ($post && $post->post_type === 'product') {
            return $post->ID;
        }

        // Try to get from query vars (single product page)
        if (self::isProductPage()) {
            return get_the_ID();
        }

        return null;
    }

    /**
     * Sanitize HTML classes
     */
    public static function sanitizeHtmlClass(string $class): string
    {
        return sanitize_html_class($class);
    }

    /**
     * Format price with WooCommerce formatting
     */
    public static function formatPrice($price): string
    {
        if (!self::isWooCommerceActive() || !$price) {
            return '';
        }

        return wc_price($price);
    }

    /**
     * Get WooCommerce currency symbol
     */
    public static function getCurrencySymbol(): string
    {
        if (!self::isWooCommerceActive()) {
            return '$';
        }

        return get_woocommerce_currency_symbol();
    }

    /**
     * Debug log helper
     */
    public static function log(string $message, string $level = 'info'): void
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf('[Elementor WC Meta] [%s] %s', strtoupper($level), $message));
        }
    }

    /**
     * Get plugin version
     */
    public static function getVersion(): string
    {
        return ELEMENTOR_WC_META_VERSION;
    }

    /**
     * Get plugin path
     */
    public static function getPluginPath(string $path = ''): string
    {
        return ELEMENTOR_WC_META_PLUGIN_DIR . ltrim($path, '/');
    }

    /**
     * Get plugin URL
     */
    public static function getPluginUrl(string $path = ''): string
    {
        return ELEMENTOR_WC_META_PLUGIN_URL . ltrim($path, '/');
    }

    /**
     * Truncate text with ellipsis
     */
    public static function truncateText(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Escape text for HTML output
     */
    public static function escapeText(string $text): string
    {
        return esc_html($text);
    }

    /**
     * Escape URL for output
     */
    public static function escapeUrl(string $url): string
    {
        return esc_url($url);
    }

    /**
     * Check if string is empty or whitespace only
     */
    public static function isEmptyString(?string $str): bool
    {
        return empty(trim($str ?? ''));
    }
}
