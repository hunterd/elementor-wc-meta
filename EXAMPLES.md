# Custom Attributes Examples

## Real-World Use Cases

### 1. Product Color Attribute

**Setup:**
- Go to Products → Attributes
- Create "Color" attribute with slug `color`
- Add color terms: Red, Blue, Green

**Usage in Widget:**
- Meta Field: "Custom Attribute"
- Attribute Key: `pa_color`
- Result: "Red" or "Red, Blue" if multiple colors

### 2. Brand Custom Field

**Setup:**
```php
// Add to functions.php or plugin
add_action('woocommerce_product_options_general_product_data', function() {
    woocommerce_wp_text_input([
        'id' => '_product_brand',
        'label' => __('Brand', 'your-textdomain'),
        'placeholder' => 'Enter brand name'
    ]);
});

add_action('woocommerce_process_product_meta', function($post_id) {
    update_post_meta($post_id, '_product_brand', sanitize_text_field($_POST['_product_brand']));
});
```

**Usage in Widget:**
- Meta Field: "Custom Attribute"
- Attribute Key: `_product_brand`
- Result: "Nike" or "Apple"

### 3. Product Rating (Plugin Integration)

**For WooCommerce Product Reviews Pro:**
- Attribute Key: `_wc_review_count`
- Result: "25 reviews"

**For YITH WooCommerce Advanced Reviews:**
- Attribute Key: `_yith_wcrev_overall_rating`
- Result: "4.5"

### 4. External Product Integration

**For external/affiliate products:**
- Attribute Key: `_product_url` (External URL)
- Attribute Key: `_button_text` (Button text)

### 5. Downloadable Product Info

**For downloadable products:**
- Attribute Key: `_download_limit` (Download limit)
- Attribute Key: `_download_expiry` (Download expiry days)

### 6. SEO Fields Integration

**Yoast SEO:**
- Attribute Key: `_yoast_wpseo_title`
- Attribute Key: `_yoast_wpseo_metadesc`

**Rank Math:**
- Attribute Key: `rank_math_title`
- Attribute Key: `rank_math_description`

### 7. Plugin-Specific Examples

**WooCommerce Bookings:**
- Attribute Key: `_wc_booking_duration`
- Attribute Key: `_wc_booking_duration_type`

**WooCommerce Subscriptions:**
- Attribute Key: `_subscription_period`
- Attribute Key: `_subscription_price`

**Advanced Custom Fields (ACF):**
- Attribute Key: `field_12345` (ACF field key)
- Or: `your_custom_field_name` (ACF field name)

## Developer Tips

### Finding Meta Keys

```php
// Add this to functions.php temporarily to see all meta keys
add_action('wp_footer', function() {
    if (is_product()) {
        global $product;
        $meta = get_post_meta($product->get_id());
        echo '<pre style="display:none;">';
        print_r(array_keys($meta));
        echo '</pre>';
    }
});
```

### Custom Validation

```php
// Validate custom attribute keys
add_filter('elementor_wc_meta_custom_attribute_value', function($value, $key, $product) {
    // Custom validation logic
    if (empty($value) && $key === '_required_field') {
        return __('Not specified', 'your-textdomain');
    }
    return $value;
}, 10, 3);
```

### Advanced Formatting

```php
// Format custom values
add_filter('elementor_wc_meta_custom_attribute_value', function($value, $key, $product) {
    switch ($key) {
        case '_product_rating':
            return $value ? sprintf('★%.1f', $value) : '';
        case '_product_views':
            return $value ? sprintf('%s views', number_format($value)) : '';
        default:
            return $value;
    }
}, 10, 3);
```

## Styling Examples

### Custom CSS for Specific Attributes

```css
/* Style brand field */
.elementor-wc-meta[data-field="brand"] {
    font-weight: bold;
    color: #333;
}

/* Style ratings */
.elementor-wc-meta[data-field="rating"] {
    color: #ffa500;
}

/* Style price-related fields */
.elementor-wc-meta[data-field*="price"] {
    font-size: 1.2em;
    color: #e74c3c;
}
```

### Responsive Styling

```css
@media (max-width: 768px) {
    .elementor-wc-meta {
        font-size: 0.9em;
        margin-bottom: 0.5em;
    }
}
```
