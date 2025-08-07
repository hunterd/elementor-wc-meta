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
        // Defer meta fields initialization until init hook when translations are available
        add_action('init', [$this, 'initializeMetaFields'], 5);
        add_action('init', [$this, 'registerMetaFields'], 10);
    }

    /**
     * Initialize available meta fields
     * Called at init hook when translations are available
     */
    public function initializeMetaFields(): void
    {
        // Prevent double initialization
        if (!empty($this->metaFields)) {
            return;
        }

        // Ensure translation function is available
        if (!function_exists('__')) {
            // Fallback labels if translation function is not available
            $this->initializeMetaFieldsWithoutTranslation();
            return;
        }

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
            'custom_attribute' => [
                'label' => __('Custom Attribute', 'elementor-wc-meta'),
                'type' => 'custom_attribute',
                'supports_limit' => false,
                'supports_label' => true,
                'supports_custom_key' => true,
            ],
        ];

        // Allow filtering of meta fields
        $this->metaFields = apply_filters('elementor_wc_meta_fields', $this->metaFields);
    }

    /**
     * Initialize meta fields without translation (fallback)
     */
    private function initializeMetaFieldsWithoutTranslation(): void
    {
        $this->metaFields = [
            'product_categories' => [
                'label' => 'Product Categories',
                'type' => 'taxonomy',
                'taxonomy' => 'product_cat',
                'supports_limit' => true,
                'supports_label' => true,
            ],
            'product_tags' => [
                'label' => 'Product Tags',
                'type' => 'taxonomy',
                'taxonomy' => 'product_tag',
                'supports_limit' => true,
                'supports_label' => true,
            ],
            'product_attributes' => [
                'label' => 'Product Attributes',
                'type' => 'attributes',
                'supports_limit' => true,
                'supports_label' => true,
            ],
            'product_price' => [
                'label' => 'Product Price',
                'type' => 'meta',
                'meta_key' => '_price',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_regular_price' => [
                'label' => 'Product Regular Price',
                'type' => 'meta',
                'meta_key' => '_regular_price',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_sale_price' => [
                'label' => 'Product Sale Price',
                'type' => 'meta',
                'meta_key' => '_sale_price',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_sku' => [
                'label' => 'Product SKU',
                'type' => 'meta',
                'meta_key' => '_sku',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_stock_status' => [
                'label' => 'Stock Status',
                'type' => 'meta',
                'meta_key' => '_stock_status',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_weight' => [
                'label' => 'Product Weight',
                'type' => 'meta',
                'meta_key' => '_weight',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'product_dimensions' => [
                'label' => 'Product Dimensions',
                'type' => 'dimensions',
                'supports_limit' => false,
                'supports_label' => true,
            ],
            'custom_attribute' => [
                'label' => 'Custom Attribute',
                'type' => 'custom_attribute',
                'supports_limit' => false,
                'supports_label' => true,
                'supports_custom_key' => true,
            ],
        ];

        // Apply filters if available
        if (function_exists('apply_filters')) {
            $this->metaFields = apply_filters('elementor_wc_meta_fields', $this->metaFields);
        }
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
        // Force initialization if fields are empty (e.g., in Elementor editor context)
        if (empty($this->metaFields)) {
            $this->initializeMetaFields();
        }
        
        return $this->metaFields;
    }

    /**
     * Get meta field configuration by key
     */
    public function getMetaField(string $key): ?array
    {
        // Force initialization if fields are empty (e.g., in Elementor editor context)
        if (empty($this->metaFields)) {
            $this->initializeMetaFields();
        }
        
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
        $customKey = $options['custom_key'] ?? '';

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
            
            case 'custom_attribute':
                $value = $this->getCustomAttributeValue($product, $customKey);
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

    /**
     * Get custom attribute value
     */
    private function getCustomAttributeValue(\WC_Product $product, string $attributeKey): string
    {
        if (empty($attributeKey)) {
            return '';
        }

        // Check if it's a product attribute (pa_attribute_name format)
        if (strpos($attributeKey, 'pa_') === 0) {
            return $this->getTaxonomyAttributeValue($product, $attributeKey);
        }

        // Check if it's a regular meta field
        $metaValue = $product->get_meta($attributeKey);
        if (!empty($metaValue)) {
            return (string) $metaValue;
        }

        // Check if it's a product attribute (non-taxonomy)
        $attributes = $product->get_attributes();
        foreach ($attributes as $attribute) {
            if ($attribute->get_name() === $attributeKey) {
                if ($attribute->is_taxonomy()) {
                    return $this->getTaxonomyAttributeValue($product, $attributeKey);
                } else {
                    $values = $attribute->get_options();
                    return is_array($values) ? implode(', ', $values) : (string) $values;
                }
            }
        }

        return '';
    }

    /**
     * Get taxonomy attribute value
     */
    private function getTaxonomyAttributeValue(\WC_Product $product, string $taxonomy): string
    {
        $terms = get_the_terms($product->get_id(), $taxonomy);
        
        if (!$terms || is_wp_error($terms)) {
            return '';
        }

        $termNames = array_map(function($term) {
            return $term->name;
        }, $terms);

        return implode(', ', $termNames);
    }

    /**
     * Get available product attributes (for reference)
     */
    public static function getAvailableProductAttributes(): array
    {
        // Get global product attributes
        $attributes = [];
        
        if (function_exists('wc_get_attribute_taxonomies')) {
            $attributeTaxonomies = wc_get_attribute_taxonomies();
            foreach ($attributeTaxonomies as $attribute) {
                $attributes['pa_' . $attribute->attribute_name] = $attribute->attribute_label;
            }
        }
        
        return $attributes;
    }

    /**
     * Get common custom meta fields (for reference)
     */
    public static function getCommonCustomMetaFields(): array
    {
        return [
            '_custom_field_1' => __('Custom Field 1', 'elementor-wc-meta'),
            '_custom_field_2' => __('Custom Field 2', 'elementor-wc-meta'),
            '_product_url' => __('Product URL', 'elementor-wc-meta'),
            '_button_text' => __('Button Text', 'elementor-wc-meta'),
            '_download_limit' => __('Download Limit', 'elementor-wc-meta'),
            '_download_expiry' => __('Download Expiry', 'elementor-wc-meta'),
        ];
    }
}
