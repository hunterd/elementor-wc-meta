<?php
/**
 * Plugin Name: Elementor WooCommerce Meta
 * Plugin URI: https://github.com/hunterd/elementor-wc-meta
 * Description: Add WooCommerce meta fields to Elementor widgets with granular control
 * Version: 1.0.0
 * Author: Hunter
 * Author URI: https://github.com/hunterd
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: elementor-wc-meta
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.6
 * Requires PHP: 8.0
 * WC requires at least: 8.0
 * WC tested up to: 9.0
 * Elementor tested up to: 3.23
 * Elementor Pro tested up to: 3.23
 */

// Prevent direct access
defined('ABSPATH') || exit;

// Define plugin constants
define('ELEMENTOR_WC_META_VERSION', '1.0.0');
define('ELEMENTOR_WC_META_PLUGIN_FILE', __FILE__);
define('ELEMENTOR_WC_META_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ELEMENTOR_WC_META_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ELEMENTOR_WC_META_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Load Composer autoloader
if (file_exists(ELEMENTOR_WC_META_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once ELEMENTOR_WC_META_PLUGIN_DIR . 'vendor/autoload.php';
}

// Initialize the plugin
add_action('plugins_loaded', function() {
    ElementorWcMeta\Foundation\Application::getInstance()->boot();
});

// Activation hook
register_activation_hook(__FILE__, function() {
    ElementorWcMeta\Foundation\Application::getInstance()->activate();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    ElementorWcMeta\Foundation\Application::getInstance()->deactivate();
});
