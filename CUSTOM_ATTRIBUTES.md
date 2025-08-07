# Custom Attributes Usage

## Overview

The Elementor WC Meta plugin now supports displaying custom product attributes and meta fields. This feature allows you to display any custom attribute or meta field associated with a WooCommerce product.

## How to Use

1. **Add the WC Meta Field Widget** to your Elementor page/template
2. **Select "Custom Attribute"** from the Meta Field dropdown
3. **Enter the Attribute Key** in the "Attribute Key" field

## Attribute Key Examples

### Product Attributes (Taxonomy-based)
- `pa_color` - Color attribute
- `pa_size` - Size attribute  
- `pa_brand` - Brand attribute
- `pa_material` - Material attribute

### Custom Meta Fields
- `_custom_field_name` - Any custom meta field
- `_product_url` - External product URL (for external products)
- `_button_text` - Button text (for external products)
- `_download_limit` - Download limit (for downloadable products)
- `_download_expiry` - Download expiry (for downloadable products)

### Plugin-specific Meta Fields
Many plugins add their own custom meta fields. Common examples:
- `_yoast_wpseo_title` - Yoast SEO title
- `_rank_math_title` - Rank Math SEO title
- `_product_video_url` - Product video URL (various video plugins)

## Notes

- **Product Attributes**: Use the `pa_` prefix for global product attributes (e.g., `pa_color`)
- **Custom Meta Fields**: Use the exact meta key name (usually starts with `_`)
- **Case Sensitive**: Attribute keys are case-sensitive
- **Empty Values**: If the attribute/meta field doesn't exist or is empty, nothing will be displayed

## Finding Attribute Keys

### Method 1: WordPress Admin
1. Go to Products → Attributes in WordPress admin
2. The attribute slug is what you need (add `pa_` prefix)

### Method 2: Product Edit Screen
1. Edit a product in WordPress admin
2. Check the Attributes section
3. Use the attribute name with `pa_` prefix for global attributes

### Method 3: Custom Fields
1. Edit a product in WordPress admin
2. Scroll down to Custom Fields section
3. Use the exact field name (key)

## Examples

### Display Product Color
- **Attribute Key**: `pa_color`
- **Result**: "Blue" or "Red, Blue" (if multiple values)

### Display Custom Brand Field
- **Attribute Key**: `_brand_name`
- **Result**: "Nike" or whatever value is stored

### Display External Product URL
- **Attribute Key**: `_product_url`
- **Result**: "https://example.com/product"

## Styling

The custom attribute output will have the same CSS class as other meta fields: `.elementor-wc-meta`

You can style it using Elementor's style controls or custom CSS.
