<?php

namespace ElementorWcMeta\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * WooCommerce Meta Field Widget for Elementor
 */
class WcMetaFieldWidget extends Widget_Base
{
    /**
     * Get widget name
     */
    public function get_name(): string
    {
        return 'wc-meta-field';
    }

    /**
     * Get widget title
     */
    public function get_title(): string
    {
        return __('WC Meta Field', 'elementor-wc-meta');
    }

    /**
     * Get widget icon
     */
    public function get_icon(): string
    {
        return 'eicon-woocommerce';
    }

    /**
     * Get widget categories
     */
    public function get_categories(): array
    {
        return ['woocommerce-elements'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords(): array
    {
        return ['woocommerce', 'product', 'meta', 'field', 'data'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls(): void
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'elementor-wc-meta'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Product Selection
        $this->add_control(
            'product_source',
            [
                'label' => __('Product Source', 'elementor-wc-meta'),
                'type' => Controls_Manager::SELECT,
                'default' => 'current',
                'options' => [
                    'current' => __('Current Product', 'elementor-wc-meta'),
                    'custom' => __('Custom Product', 'elementor-wc-meta'),
                ],
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => __('Select Product', 'elementor-wc-meta'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_products_list(),
                'condition' => [
                    'product_source' => 'custom',
                ],
            ]
        );

        // Meta Field Selection
        $this->add_control(
            'meta_field',
            [
                'label' => __('Meta Field', 'elementor-wc-meta'),
                'type' => Controls_Manager::SELECT,
                'default' => 'price',
                'options' => [
                    'price' => __('Price', 'elementor-wc-meta'),
                    'sale_price' => __('Sale Price', 'elementor-wc-meta'),
                    'regular_price' => __('Regular Price', 'elementor-wc-meta'),
                    'sku' => __('SKU', 'elementor-wc-meta'),
                    'stock_quantity' => __('Stock Quantity', 'elementor-wc-meta'),
                    'stock_status' => __('Stock Status', 'elementor-wc-meta'),
                    'weight' => __('Weight', 'elementor-wc-meta'),
                    'dimensions' => __('Dimensions', 'elementor-wc-meta'),
                    'categories' => __('Categories', 'elementor-wc-meta'),
                    'tags' => __('Tags', 'elementor-wc-meta'),
                    'short_description' => __('Short Description', 'elementor-wc-meta'),
                    'rating' => __('Average Rating', 'elementor-wc-meta'),
                    'review_count' => __('Review Count', 'elementor-wc-meta'),
                ],
            ]
        );

        // Display Options
        $this->add_control(
            'display_label',
            [
                'label' => __('Show Label', 'elementor-wc-meta'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'elementor-wc-meta'),
                'label_off' => __('Hide', 'elementor-wc-meta'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'custom_label',
            [
                'label' => __('Custom Label', 'elementor-wc-meta'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Enter custom label', 'elementor-wc-meta'),
                'condition' => [
                    'display_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __('HTML Tag', 'elementor-wc-meta'),
                'type' => Controls_Manager::SELECT,
                'default' => 'div',
                'options' => [
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                    'h1' => 'h1',
                    'h2' => 'h2',
                    'h3' => 'h3',
                    'h4' => 'h4',
                    'h5' => 'h5',
                    'h6' => 'h6',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'elementor-wc-meta'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .wc-meta-field',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-wc-meta'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wc-meta-field' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'elementor-wc-meta'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wc-meta-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'display_label' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => __('Alignment', 'elementor-wc-meta'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'elementor-wc-meta'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'elementor-wc-meta'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'elementor-wc-meta'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wc-meta-field' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend
     */
    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        
        // Get product ID
        $product_id = $this->get_product_id($settings);
        if (!$product_id) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . 
                     __('Please select a product or view this widget on a product page.', 'elementor-wc-meta') . 
                     '</div>';
            }
            return;
        }

        // Get product
        $product = wc_get_product($product_id);
        if (!$product) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . 
                     __('Product not found.', 'elementor-wc-meta') . 
                     '</div>';
            }
            return;
        }

        // Get meta value
        $meta_value = $this->get_meta_value($product, $settings['meta_field']);
        
        if (empty($meta_value) && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        // Output
        $tag = $settings['html_tag'];
        $label = $this->get_field_label($settings);
        
        echo "<{$tag} class='wc-meta-field'>";
        
        if ($settings['display_label'] === 'yes' && !empty($label)) {
            echo "<span class='wc-meta-label'>{$label}: </span>";
        }
        
        echo "<span class='wc-meta-value'>{$meta_value}</span>";
        echo "</{$tag}>";
    }

    /**
     * Get product ID based on settings
     */
    private function get_product_id(array $settings): ?int
    {
        if ($settings['product_source'] === 'custom' && !empty($settings['product_id'])) {
            return (int) $settings['product_id'];
        }

        // Try to get current product ID
        global $product;
        if (is_product() && $product instanceof \WC_Product) {
            return $product->get_id();
        }

        // Fallback for editor mode
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $products = wc_get_products(['limit' => 1]);
            return $products ? $products[0]->get_id() : null;
        }

        return null;
    }

    /**
     * Get meta value for a specific field
     */
    private function get_meta_value(\WC_Product $product, string $field): string
    {
        switch ($field) {
            case 'price':
                return $product->get_price_html();
            
            case 'sale_price':
                $sale_price = $product->get_sale_price();
                return $sale_price ? wc_price($sale_price) : '';
            
            case 'regular_price':
                $regular_price = $product->get_regular_price();
                return $regular_price ? wc_price($regular_price) : '';
            
            case 'sku':
                return $product->get_sku() ?: '';
            
            case 'stock_quantity':
                $stock = $product->get_stock_quantity();
                return $stock !== null ? (string) $stock : '';
            
            case 'stock_status':
                return ucfirst($product->get_stock_status());
            
            case 'weight':
                $weight = $product->get_weight();
                return $weight ? $weight . ' ' . get_option('woocommerce_weight_unit') : '';
            
            case 'dimensions':
                return $product->get_dimensions(false);
            
            case 'categories':
                $categories = wp_get_post_terms($product->get_id(), 'product_cat');
                return implode(', ', wp_list_pluck($categories, 'name'));
            
            case 'tags':
                $tags = wp_get_post_terms($product->get_id(), 'product_tag');
                return implode(', ', wp_list_pluck($tags, 'name'));
            
            case 'short_description':
                return $product->get_short_description();
            
            case 'rating':
                return (string) $product->get_average_rating();
            
            case 'review_count':
                return (string) $product->get_review_count();
            
            default:
                return '';
        }
    }

    /**
     * Get field label
     */
    private function get_field_label(array $settings): string
    {
        if (!empty($settings['custom_label'])) {
            return $settings['custom_label'];
        }

        $labels = [
            'price' => __('Price', 'elementor-wc-meta'),
            'sale_price' => __('Sale Price', 'elementor-wc-meta'),
            'regular_price' => __('Regular Price', 'elementor-wc-meta'),
            'sku' => __('SKU', 'elementor-wc-meta'),
            'stock_quantity' => __('Stock', 'elementor-wc-meta'),
            'stock_status' => __('Status', 'elementor-wc-meta'),
            'weight' => __('Weight', 'elementor-wc-meta'),
            'dimensions' => __('Dimensions', 'elementor-wc-meta'),
            'categories' => __('Categories', 'elementor-wc-meta'),
            'tags' => __('Tags', 'elementor-wc-meta'),
            'short_description' => __('Description', 'elementor-wc-meta'),
            'rating' => __('Rating', 'elementor-wc-meta'),
            'review_count' => __('Reviews', 'elementor-wc-meta'),
        ];

        return $labels[$settings['meta_field']] ?? '';
    }

    /**
     * Get products list for select control
     */
    private function get_products_list(): array
    {
        $products = wc_get_products([
            'limit' => 50,
            'status' => 'publish',
        ]);

        $options = [];
        foreach ($products as $product) {
            $options[$product->get_id()] = $product->get_name();
        }

        return $options;
    }
}
