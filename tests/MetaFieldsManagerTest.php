<?php

namespace ElementorWcMeta\Tests;

use ElementorWcMeta\WooCommerce\MetaFieldsManager;
use PHPUnit\Framework\TestCase;

/**
 * Test class for MetaFieldsManager
 */
class MetaFieldsManagerTest extends TestCase
{
    private MetaFieldsManager $metaFieldsManager;

    protected function setUp(): void
    {
        $this->metaFieldsManager = new MetaFieldsManager();
    }

    public function testGetMetaFields(): void
    {
        $fields = $this->metaFieldsManager->getMetaFields();
        
        $this->assertIsArray($fields);
        $this->assertNotEmpty($fields);
        $this->assertArrayHasKey('product_categories', $fields);
        $this->assertArrayHasKey('product_price', $fields);
    }

    public function testGetMetaField(): void
    {
        $field = $this->metaFieldsManager->getMetaField('product_categories');
        
        $this->assertIsArray($field);
        $this->assertArrayHasKey('label', $field);
        $this->assertArrayHasKey('type', $field);
        $this->assertEquals('taxonomy', $field['type']);
    }

    public function testGetNonExistentMetaField(): void
    {
        $field = $this->metaFieldsManager->getMetaField('non_existent_field');
        
        $this->assertNull($field);
    }

    public function testCustomAttributeFieldExists(): void
    {
        $field = $this->metaFieldsManager->getMetaField('custom_attribute');
        
        $this->assertIsArray($field);
        $this->assertArrayHasKey('label', $field);
        $this->assertArrayHasKey('type', $field);
        $this->assertEquals('custom_attribute', $field['type']);
        $this->assertTrue($field['supports_custom_key']);
    }

    public function testGetAvailableProductAttributes(): void
    {
        $attributes = MetaFieldsManager::getAvailableProductAttributes();
        
        $this->assertIsArray($attributes);
        // Note: This will be empty in test environment since WooCommerce functions are not available
    }

    public function testGetCommonCustomMetaFields(): void
    {
        $metaFields = MetaFieldsManager::getCommonCustomMetaFields();
        
        $this->assertIsArray($metaFields);
        $this->assertArrayHasKey('_custom_field_1', $metaFields);
        $this->assertArrayHasKey('_product_url', $metaFields);
    }
}
