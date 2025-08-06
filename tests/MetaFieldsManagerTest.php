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
}
