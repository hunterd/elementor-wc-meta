<?php

// Test simple pour vérifier que les méta-champs sont disponibles
require_once __DIR__ . '/vendor/autoload.php';

// Mock WordPress functions
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10) {
        // Mock function
    }
}

if (!function_exists('__')) {
    function __($text, $domain = '') {
        return $text; // Return text as-is for testing
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters($tag, $value) {
        return $value; // Return value as-is for testing
    }
}

use ElementorWcMeta\WooCommerce\MetaFieldsManager;

echo "Testing MetaFieldsManager...\n";

$manager = new MetaFieldsManager();

// Test getMetaFields
$fields = $manager->getMetaFields();
echo "Number of meta fields: " . count($fields) . "\n";

if (empty($fields)) {
    echo "ERROR: No meta fields found!\n";
    exit(1);
}

echo "Available meta fields:\n";
foreach ($fields as $key => $field) {
    echo "- $key: " . $field['label'] . " (type: " . $field['type'] . ")\n";
}

// Test getMetaField
$customAttributeField = $manager->getMetaField('custom_attribute');
if ($customAttributeField) {
    echo "\nCustom attribute field found:\n";
    echo "- Label: " . $customAttributeField['label'] . "\n";
    echo "- Type: " . $customAttributeField['type'] . "\n";
    echo "- Supports custom key: " . ($customAttributeField['supports_custom_key'] ? 'Yes' : 'No') . "\n";
} else {
    echo "\nERROR: Custom attribute field not found!\n";
    exit(1);
}

echo "\nTest completed successfully!\n";
