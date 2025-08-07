<?php

namespace ElementorWcMeta\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use ElementorWcMeta\WooCommerce\MetaFieldsManager;

/**
 * WooCommerce Meta Widget for Elementor
 */
class WcMetaWidget extends Widget_Base
{
    private MetaFieldsManager $metaFieldsManager;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        $this->metaFieldsManager = new MetaFieldsManager();
    }

    /**
     * Get widget name
     */
    public function get_name(): string
    {
        return 'wc-meta';
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
        return 'eicon-product-meta';
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
        return ['woocommerce', 'meta', 'product', 'field', 'custom'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls(): void
    {
        $this->registerContentControls();
        $this->registerStyleControls();
    }

    /**
     * Register content controls
     */
    private function registerContentControls(): void
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'elementor-wc-meta'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Meta field selection
        $this->add_control(
            'meta_field',
            [
                'label' => __('Meta Field', 'elementor-wc-meta'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->getMetaFieldOptions(),
                'default' => 'product_categories',
            ]
        );

        // Show label toggle
        $this->add_control(
            'show_label',
            [
                'label' => __('Show Label', 'elementor-wc-meta'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'elementor-wc-meta'),
                'label_off' => __('Hide', 'elementor-wc-meta'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Custom label
        $this->add_control(
            'custom_label',
            [
                'label' => __('Custom Label', 'elementor-wc-meta'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Enter custom label', 'elementor-wc-meta'),
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        // Limit control (conditional)
        $this->add_control(
            'limit',
            [
                'label' => __('Limit Items', 'elementor-wc-meta'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'default' => 0,
                'description' => __('Set to 0 for no limit', 'elementor-wc-meta'),
                'condition' => [
                    'meta_field!' => ['product_price', 'product_regular_price', 'product_sale_price', 'product_sku', 'product_stock_status', 'product_weight', 'product_dimensions'],
                ],
            ]
        );

        // Separator
        $this->add_control(
            'separator',
            [
                'label' => __('Separator', 'elementor-wc-meta'),
                'type' => Controls_Manager::TEXT,
                'default' => ', ',
                'condition' => [
                    'meta_field' => ['product_categories', 'product_tags', 'product_attributes'],
                ],
            ]
        );

        // Custom attribute key
        $this->add_control(
            'custom_attribute_key',
            [
                'label' => __('Attribute Key', 'elementor-wc-meta'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Enter attribute key (e.g., pa_color, _custom_field)', 'elementor-wc-meta'),
                'description' => __('Enter the attribute key or meta field name. Use "pa_" prefix for product attributes (e.g., pa_color).', 'elementor-wc-meta'),
                'condition' => [
                    'meta_field' => 'custom_attribute',
                ],
            ]
        );

        // HTML tag
        $this->add_control(
            'html_tag',
            [
                'label' => __('HTML Tag', 'elementor-wc-meta'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                ],
                'default' => 'div',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls
     */
    private function registerStyleControls(): void
    {
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'elementor-wc-meta'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .elementor-wc-meta',
            ]
        );

        // Text color
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-wc-meta'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wc-meta' => 'color: {{VALUE}}',
                ],
            ]
        );

        // Label color
        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'elementor-wc-meta'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wc-meta-label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        // Text align
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-wc-meta' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get meta field options for select control
     */
    private function getMetaFieldOptions(): array
    {
        $metaFields = $this->metaFieldsManager->getMetaFields();
        $options = [];

        foreach ($metaFields as $key => $field) {
            $options[$key] = $field['label'];
        }

        return $options;
    }

    /**
     * Render the widget output
     */
    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        
        // Get current product ID (works in loops and single product pages)
        $productId = $this->getCurrentProductId();
        
        if (!$productId) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-wc-meta">' . __('Please view this on a product page or in a product loop.', 'elementor-wc-meta') . '</div>';
            }
            return;
        }

        $metaField = $settings['meta_field'];
        $showLabel = $settings['show_label'] === 'yes';
        $customLabel = $settings['custom_label'];
        $limit = (int) $settings['limit'];
        $separator = $settings['separator'] ?: ', ';
        $htmlTag = $settings['html_tag'] ?: 'div';
        $customAttributeKey = $settings['custom_attribute_key'] ?? '';

        // Prepare options for meta value retrieval
        $options = [
            'show_label' => $showLabel,
            'limit' => $limit,
            'separator' => $separator,
            'custom_key' => $customAttributeKey,
        ];

        // Get meta value
        $metaValue = $this->metaFieldsManager->getMetaValue($productId, $metaField, $options);

        if (!$metaValue) {
            return;
        }

        // Handle custom label
        if ($showLabel && $customLabel) {
            $metaFieldConfig = $this->metaFieldsManager->getMetaField($metaField);
            $originalLabel = $metaFieldConfig['label'] . ': ';
            $metaValue = str_replace($originalLabel, $customLabel . ': ', $metaValue);
        }

        // Output the meta value
        printf(
            '<%1$s class="elementor-wc-meta">%2$s</%1$s>',
            esc_html($htmlTag),
            wp_kses_post($metaValue)
        );
    }

    /**
     * Get current product ID based on context
     */
    private function getCurrentProductId(): ?int
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
        if (is_product()) {
            return get_the_ID();
        }

        // Check if we're in a loop
        if (wc_get_loop_prop('is_shortcode') || wc_get_loop_prop('is_widget')) {
            $productId = get_the_ID();
            if (get_post_type($productId) === 'product') {
                return $productId;
            }
        }

        return null;
    }

    /**
     * Render the widget output in the editor
     */
    protected function content_template(): void
    {
        ?>
        <#
        var htmlTag = settings.html_tag || 'div';
        var metaField = settings.meta_field || 'product_categories';
        var showLabel = settings.show_label === 'yes';
        var customLabel = settings.custom_label;
        
        var sampleValues = {
            'product_categories': 'Electronics, Computers',
            'product_tags': 'New, Featured, Sale',
            'product_attributes': 'Color: Blue, Size: Large',
            'product_price': '$99.99',
            'product_regular_price': '$129.99',
            'product_sale_price': '$99.99',
            'product_sku': 'PROD-123',
            'product_stock_status': 'In Stock',
            'product_weight': '2.5 kg',
            'product_dimensions': '10 × 20 × 30 cm',
            'custom_attribute': 'Custom Value'
        };
        
        var value = sampleValues[metaField] || 'Sample Value';
        
        if (showLabel) {
            var label = customLabel || (metaField.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));
            value = label + ': ' + value;
        }
        #>
        <{{{ htmlTag }}} class="elementor-wc-meta">{{{ value }}}</{{{ htmlTag }}}>
        <?php
    }
}
