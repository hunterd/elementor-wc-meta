<?php

namespace ElementorWcMeta\WooCommerce;

/**
 * Meta Fields Manager - handles WooCommerce product meta fields
 */
class MetaFieldsManager
{
    private array $metaFields = [];

    public function __construct()
    {
        $this->initializeMetaFields();
        add_action('init', [$this, 'registerMetaFields']);
    }

    /**
     * Initialize available meta fields
     */
    private function initializeMetaFields(): void
    {
        $this->metaFields = [
            'product_categories' => [
                'label' => __('Product Categories', 'elementor-wc-meta'),
                'type' => 'taxonomy',
                'taxonomy' => 'product_cat',
                'supports_limit' => true,
                'supports_label' => true,
            ],
            'product_tags' => [
                'label' => __('Product Tags', 'elementor-wc-meta'),
                'type' => 'taxonomy',
                'taxonomy' => 'product_tag',
                'supports_limit' => true,
                'supports_label' => true,
            ],
            'product_attributes' => [
                'label' => __('Product Attributes', 'elementor-wc-meta'),
                'type' => 'attributes',
                'supports_limit' => true,
                'supports_label' => true,
            ],
            'product_price' => [
                'label' => __('Product Price', 'elementor-wc-meta'),
                'type' => 'meta',
                'meta_key' => '_price',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_regular_price' => [
                'label' => __('Product Regular Price', 'elementor-wc-meta'),
                'type' => 'meta',
                'meta_key' => '_regular_price',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_sale_price' => [
                'label' => __('Product Sale Price', 'elementor-wc-meta'),
                'type' => 'meta',
                'meta_key' => '_sale_price',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_sku' => [
                'label' => __('Product SKU', 'elementor-wc-meta'),
                'type' => 'meta',
                'meta_key' => '_sku',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_stock_status' => [
                'label' => __('Stock Status', 'elementor-wc-meta'),
                'type' => 'meta',
                'meta_key' => '_stock_status',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_weight' => [
                'label' => __('Product Weight', 'elementor-wc-meta'),
                'type' => 'meta',
                'meta_key' => '_weight',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_dimensions' => [
                'label' => __('Product Dimensions', 'elementor-wc-meta'),
                'type' => 'dimensions',
                'supports_limit' => false,
                'supports_label' => true,
            ],
        ];

        // Allow filtering of meta fields
        $this->metaFields = apply_filters('elementor_wc_meta_fields', $this->metaFields);
    }

    /**
     * Register meta fields for public access
     */
    public function registerMetaFields(): void
    {
        // Make meta fields available to other parts of the plugin
        add_filter('elementor_wc_meta_get_fields', [$this, 'getMetaFields']);
    }

    /**
     * Get all available meta fields
     */
    public function getMetaFields(): array
    {
        return $this->metaFields;
    }

    /**
     * Get meta field configuration by key
     */
    public function getMetaField(string $key): ?array
    {
        return $this->metaFields[$key] ?? null;
    }

    /**
     * Get formatted meta value for a product
     */
    public function getMetaValue(int $productId, string $metaKey, array $options = []): string
    {
        $product = wc_get_product($productId);
        if (!$product) {
            return '';
        }

        $metaField = $this->getMetaField($metaKey);
        if (!$metaField) {
            return '';
        }

        $showLabel = $options['show_label'] ?? true;
        $limit = $options['limit'] ?? 0;
        $separator = $options['separator'] ?? ', ';

        $value = '';
        $label = $showLabel ? $metaField['label'] . ': ' : '';

        switch ($metaField['type']) {
            case 'taxonomy':
                $value = $this->getTaxonomyValue($product, $metaField['taxonomy'], $limit, $separator);
                break;
            
            case 'attributes':
                $value = $this->getAttributesValue($product, $limit, $separator);
                break;
            
            case 'meta':
                $value = $this->getMetaFieldValue($product, $metaField['meta_key']);
                break;
            
            case 'dimensions':
                $value = $this->getDimensionsValue($product);
                break;
        }

        return $value ? $label . $value : '';
    }

    /**
     * Get taxonomy terms value
     */
    private function getTaxonomyValue(\WC_Product $product, string $taxonomy, int $limit, string $separator): string
    {
        $terms = get_the_terms($product->get_id(), $taxonomy);
        
        if (!$terms || is_wp_error($terms)) {
            return '';
        }

        $termNames = array_map(function($term) {
            return $term->name;
        }, $terms);

        if ($limit > 0) {
            $termNames = array_slice($termNames, 0, $limit);
        }

        return implode($separator, $termNames);
    }

    /**
     * Get product attributes value
     */
    private function getAttributesValue(\WC_Product $product, int $limit, string $separator): string
    {
        $attributes = $product->get_attributes();
        $attributeValues = [];

        foreach ($attributes as $attribute) {
            $values = [];
            
            if ($attribute->is_taxonomy()) {
                $terms = get_terms([
                    'taxonomy' => $attribute->get_name(),
                    'include' => $attribute->get_options(),
                ]);
                $values = array_map(function($term) {
                    return $term->name;
                }, $terms);
            } else {
                $values = $attribute->get_options();
            }

            if (!empty($values)) {
                $attributeValues[] = implode(', ', $values);
            }
        }

        if ($limit > 0) {
            $attributeValues = array_slice($attributeValues, 0, $limit);
        }

        return implode($separator, $attributeValues);
    }

    /**
     * Get meta field value
     */
    private function getMetaFieldValue(\WC_Product $product, string $metaKey): string
    {
        $value = $product->get_meta($metaKey);

        // Format specific meta fields
        switch ($metaKey) {
            case '_price':
            case '_regular_price':
            case '_sale_price':
                return $value ? wc_price($value) : '';
            
            case '_stock_status':
                return $value === 'instock' ? __('In Stock', 'elementor-wc-meta') : __('Out of Stock', 'elementor-wc-meta');
            
            case '_weight':
                return $value ? $value . ' ' . get_option('woocommerce_weight_unit') : '';
            
            default:
                return (string) $value;
        }
    }

    /**
     * Get product dimensions value
     */
    private function getDimensionsValue(\WC_Product $product): string
    {
        $length = $product->get_length();
        $width = $product->get_width();
        $height = $product->get_height();
        $unit = get_option('woocommerce_dimension_unit');

        if (!$length && !$width && !$height) {
            return '';
        }

        $dimensions = array_filter([$length, $width, $height]);
        return implode(' × ', $dimensions) . ' ' . $unit;
    }
}
