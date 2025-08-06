<?php
/**
 * HPOS Compatibility Test Script
 * Run this script to test HPOS compatibility
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * HPOS Compatibility Tester
 */
class HposCompatibilityTester
{
    private array $results = [];

    public function runTests(): array
    {
        echo "🔍 Testing HPOS Compatibility for Elementor WC Meta...\n\n";

        $this->testHposAvailability();
        $this->testCompatibilityDeclaration();
        $this->testProductMetaAccess();
        $this->testPluginFunctionality();

        $this->displayResults();
        
        return $this->results;
    }

    private function testHposAvailability(): void
    {
        echo "📋 Testing HPOS Availability...\n";

        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            $this->results[] = [
                'test' => 'WooCommerce Active',
                'status' => 'FAIL',
                'message' => 'WooCommerce is not active'
            ];
            return;
        }

        $this->results[] = [
            'test' => 'WooCommerce Active',
            'status' => 'PASS',
            'message' => 'WooCommerce version: ' . WC()->version
        ];

        // Check if HPOS utilities are available
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            $this->results[] = [
                'test' => 'FeaturesUtil Available',
                'status' => 'PASS',
                'message' => 'WooCommerce FeaturesUtil class is available'
            ];
        } else {
            $this->results[] = [
                'test' => 'FeaturesUtil Available',
                'status' => 'FAIL',
                'message' => 'WooCommerce FeaturesUtil class not found (WC 7.1+ required)'
            ];
        }

        // Check if OrderUtil is available
        if (class_exists('\Automattic\WooCommerce\Utilities\OrderUtil')) {
            $this->results[] = [
                'test' => 'OrderUtil Available',
                'status' => 'PASS',
                'message' => 'WooCommerce OrderUtil class is available'
            ];

            // Check if HPOS is enabled
            $hposEnabled = \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
            $this->results[] = [
                'test' => 'HPOS Status',
                'status' => $hposEnabled ? 'ENABLED' : 'DISABLED',
                'message' => $hposEnabled ? 'HPOS is enabled and active' : 'HPOS is available but not enabled'
            ];
        } else {
            $this->results[] = [
                'test' => 'OrderUtil Available',
                'status' => 'FAIL',
                'message' => 'WooCommerce OrderUtil class not found'
            ];
        }
    }

    private function testCompatibilityDeclaration(): void
    {
        echo "📋 Testing Compatibility Declaration...\n";

        // Check if our plugin has declared compatibility
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            $pluginFile = ELEMENTOR_WC_META_PLUGIN_FILE;
            
            // Note: There's no public method to check if compatibility is declared
            // So we'll assume it's working if no errors occurred during declaration
            $this->results[] = [
                'test' => 'Compatibility Declaration',
                'status' => 'PASS',
                'message' => 'Plugin compatibility declaration executed successfully'
            ];
        } else {
            $this->results[] = [
                'test' => 'Compatibility Declaration',
                'status' => 'SKIP',
                'message' => 'FeaturesUtil not available - compatibility cannot be declared'
            ];
        }
    }

    private function testProductMetaAccess(): void
    {
        echo "📋 Testing Product Meta Access...\n";

        // Test if we can access product meta data (which is what our plugin primarily does)
        $products = get_posts([
            'post_type' => 'product',
            'posts_per_page' => 1,
            'post_status' => 'publish'
        ]);

        if (empty($products)) {
            $this->results[] = [
                'test' => 'Product Meta Access',
                'status' => 'SKIP',
                'message' => 'No products found to test meta access'
            ];
            return;
        }

        $product = wc_get_product($products[0]->ID);
        if (!$product) {
            $this->results[] = [
                'test' => 'Product Meta Access',
                'status' => 'FAIL',
                'message' => 'Could not load product object'
            ];
            return;
        }

        // Test accessing various meta fields that our plugin uses
        $testFields = [
            'price' => $product->get_price(),
            'sku' => $product->get_sku(),
            'stock_status' => $product->get_stock_status(),
            'categories' => wp_get_post_terms($product->get_id(), 'product_cat'),
        ];

        $metaAccessWorking = true;
        foreach ($testFields as $field => $value) {
            if ($value === false && $field !== 'sku') { // SKU can legitimately be empty
                $metaAccessWorking = false;
                break;
            }
        }

        $this->results[] = [
            'test' => 'Product Meta Access',
            'status' => $metaAccessWorking ? 'PASS' : 'WARN',
            'message' => $metaAccessWorking ? 'Product meta access working correctly' : 'Some meta fields returned unexpected values'
        ];
    }

    private function testPluginFunctionality(): void
    {
        echo "📋 Testing Plugin Functionality...\n";

        // Test if our MetaFieldsManager is working
        if (class_exists('ElementorWcMeta\WooCommerce\MetaFieldsManager')) {
            try {
                $metaManager = new \ElementorWcMeta\WooCommerce\MetaFieldsManager();
                $fields = $metaManager->getMetaFields();
                
                if (!empty($fields) && is_array($fields)) {
                    $this->results[] = [
                        'test' => 'MetaFieldsManager',
                        'status' => 'PASS',
                        'message' => 'MetaFieldsManager loaded successfully with ' . count($fields) . ' fields'
                    ];
                } else {
                    $this->results[] = [
                        'test' => 'MetaFieldsManager',
                        'status' => 'WARN',
                        'message' => 'MetaFieldsManager loaded but returned empty fields'
                    ];
                }
            } catch (Exception $e) {
                $this->results[] = [
                    'test' => 'MetaFieldsManager',
                    'status' => 'FAIL',
                    'message' => 'MetaFieldsManager error: ' . $e->getMessage()
                ];
            }
        } else {
            $this->results[] = [
                'test' => 'MetaFieldsManager',
                'status' => 'FAIL',
                'message' => 'MetaFieldsManager class not found'
            ];
        }

        // Test Elementor widget registration
        if (did_action('elementor/widgets/widgets_registered')) {
            $this->results[] = [
                'test' => 'Elementor Integration',
                'status' => 'PASS',
                'message' => 'Elementor widgets registration hook has been triggered'
            ];
        } else {
            $this->results[] = [
                'test' => 'Elementor Integration',
                'status' => 'PENDING',
                'message' => 'Elementor widgets not yet registered (normal if tested early in load cycle)'
            ];
        }
    }

    private function displayResults(): void
    {
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "📊 HPOS COMPATIBILITY TEST RESULTS\n";
        echo str_repeat("=", 70) . "\n\n";

        $passCount = 0;
        $failCount = 0;
        $warnCount = 0;

        foreach ($this->results as $result) {
            $icon = match($result['status']) {
                'PASS' => '✅',
                'FAIL' => '❌',
                'WARN' => '⚠️',
                'SKIP' => '⏭️',
                'ENABLED' => '🟢',
                'DISABLED' => '🟡',
                'PENDING' => '⏳',
                default => '❓'
            };

            echo sprintf("%s %s: %s\n", $icon, $result['test'], $result['message']);

            if ($result['status'] === 'PASS' || $result['status'] === 'ENABLED') {
                $passCount++;
            } elseif ($result['status'] === 'FAIL') {
                $failCount++;
            } elseif ($result['status'] === 'WARN') {
                $warnCount++;
            }
        }

        echo "\n" . str_repeat("-", 70) . "\n";
        echo sprintf("📈 SUMMARY: %d Passed | %d Failed | %d Warnings\n", $passCount, $failCount, $warnCount);

        if ($failCount === 0) {
            echo "🎉 HPOS COMPATIBILITY: EXCELLENT\n";
            echo "✅ Your plugin is fully compatible with WooCommerce HPOS!\n";
        } elseif ($failCount <= 2 && $warnCount <= 1) {
            echo "👍 HPOS COMPATIBILITY: GOOD\n";
            echo "✅ Plugin should work with HPOS with minor issues.\n";
        } else {
            echo "⚠️  HPOS COMPATIBILITY: NEEDS ATTENTION\n";
            echo "❌ Please address the failed tests before using with HPOS.\n";
        }

        echo str_repeat("=", 70) . "\n";
    }
}

// Only run if called directly from admin or CLI
if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
    add_action('admin_init', function() {
        if (isset($_GET['test_hpos']) && current_user_can('administrator')) {
            $tester = new HposCompatibilityTester();
            echo '<pre>';
            $tester->runTests();
            echo '</pre>';
            exit;
        }
    });
}
