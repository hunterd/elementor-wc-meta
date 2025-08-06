<?php
/**
 * Installation and validation script
 * Run this to validate the plugin installation
 */

// Check if we're in WordPress environment
if (!defined('ABSPATH')) {
    die('This script must be run from within WordPress.');
}

/**
 * Plugin Installation Validator
 */
class ElementorWcMetaValidator
{
    private array $errors = [];
    private array $warnings = [];
    private array $success = [];

    public function validate(): array
    {
        $this->checkPHPVersion();
        $this->checkWordPressVersion();
        $this->checkDependencies();
        $this->checkFileStructure();
        $this->checkAssets();
        $this->checkAutoloader();

        return [
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'success' => $this->success,
        ];
    }

    private function checkPHPVersion(): void
    {
        if (version_compare(PHP_VERSION, '8.0', '<')) {
            $this->errors[] = 'PHP 8.0 or higher is required. Current version: ' . PHP_VERSION;
        } else {
            $this->success[] = 'PHP version check passed: ' . PHP_VERSION;
        }
    }

    private function checkWordPressVersion(): void
    {
        global $wp_version;
        
        if (version_compare($wp_version, '6.0', '<')) {
            $this->errors[] = 'WordPress 6.0 or higher is required. Current version: ' . $wp_version;
        } else {
            $this->success[] = 'WordPress version check passed: ' . $wp_version;
        }
    }

    private function checkDependencies(): void
    {
        // Check WooCommerce
        if (!class_exists('WooCommerce')) {
            $this->errors[] = 'WooCommerce plugin is required but not active.';
        } else {
            $this->success[] = 'WooCommerce is active.';
        }

        // Check Elementor
        if (!did_action('elementor/loaded')) {
            $this->errors[] = 'Elementor plugin is required but not active.';
        } else {
            $this->success[] = 'Elementor is active.';
        }
    }

    private function checkFileStructure(): void
    {
        $requiredFiles = [
            'elementor-wc-meta.php',
            'composer.json',
            'package.json',
            'vite.config.js',
            'app/Foundation/Application.php',
            'app/Providers/ServiceProvider.php',
            'app/Elementor/WidgetManager.php',
            'app/WooCommerce/MetaFieldsManager.php',
            'resources/js/admin.js',
            'resources/js/frontend.js',
            'resources/scss/style.scss',
        ];

        foreach ($requiredFiles as $file) {
            $filePath = ELEMENTOR_WC_META_PLUGIN_DIR . $file;
            if (!file_exists($filePath)) {
                $this->errors[] = "Required file missing: {$file}";
            }
        }

        if (empty($this->errors)) {
            $this->success[] = 'All required files are present.';
        }
    }

    private function checkAssets(): void
    {
        $distPath = ELEMENTOR_WC_META_PLUGIN_DIR . 'public/dist/';
        
        if (!is_dir($distPath)) {
            $this->warnings[] = 'Assets not built. Run "npm run build" to compile assets.';
        } else {
            $this->success[] = 'Asset directory exists.';
        }
    }

    private function checkAutoloader(): void
    {
        $autoloadFile = ELEMENTOR_WC_META_PLUGIN_DIR . 'vendor/autoload.php';
        
        if (!file_exists($autoloadFile)) {
            $this->errors[] = 'Composer autoloader not found. Run "composer install".';
        } else {
            $this->success[] = 'Composer autoloader is available.';
        }
    }

    public function displayResults(): void
    {
        echo '<div class="wrap">';
        echo '<h1>Elementor WC Meta - Installation Validation</h1>';

        if (!empty($this->errors)) {
            echo '<div class="notice notice-error"><h3>Errors:</h3><ul>';
            foreach ($this->errors as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul></div>';
        }

        if (!empty($this->warnings)) {
            echo '<div class="notice notice-warning"><h3>Warnings:</h3><ul>';
            foreach ($this->warnings as $warning) {
                echo '<li>' . esc_html($warning) . '</li>';
            }
            echo '</ul></div>';
        }

        if (!empty($this->success)) {
            echo '<div class="notice notice-success"><h3>Success:</h3><ul>';
            foreach ($this->success as $success) {
                echo '<li>' . esc_html($success) . '</li>';
            }
            echo '</ul></div>';
        }

        if (empty($this->errors)) {
            echo '<div class="notice notice-success">';
            echo '<h3>✅ Plugin is ready to use!</h3>';
            echo '<p>The Elementor WC Meta plugin has been installed successfully and is ready for use.</p>';
            echo '</div>';
        }

        echo '</div>';
    }
}

// Add admin page for validation (only for administrators)
add_action('admin_menu', function() {
    if (current_user_can('administrator')) {
        add_submenu_page(
            'tools.php',
            'Elementor WC Meta Validator',
            'WC Meta Validator',
            'administrator',
            'elementor-wc-meta-validator',
            function() {
                $validator = new ElementorWcMetaValidator();
                $validator->validate();
                $validator->displayResults();
            }
        );
    }
});
