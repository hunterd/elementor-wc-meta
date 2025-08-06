# Elementor WooCommerce Meta

A professional WordPress plugin that adds WooCommerce product meta fields to Elementor widgets with granular control.

## Features

- **Granular Meta Field Control**: Select specific WooCommerce meta fields (categories, tags, attributes, price, SKU, etc.)
- **Flexible Display Options**: Show/hide labels, limit quantity of displayed items, custom separators
- **Loop Integration**: Works seamlessly with Elementor loop queries
- **Professional Architecture**: Laravel-inspired structure with Composer autoloading and Vite.js asset building
- **Responsive Design**: Mobile-friendly output with customizable styling
- **Developer Friendly**: Extensible architecture with hooks and filters

## Supported Meta Fields

- **Product Categories**: Display product categories with optional limits
- **Product Tags**: Show product tags with customizable separators  
- **Product Attributes**: Display all product attributes or limit quantity
- **Product Price**: Show current price with proper formatting
- **Regular Price**: Display regular price
- **Sale Price**: Show sale price when available
- **Product SKU**: Display product SKU
- **Stock Status**: Show in stock/out of stock status
- **Product Weight**: Display weight with unit
- **Product Dimensions**: Show dimensions (L × W × H)

## Requirements

- WordPress 6.0+
- PHP 8.0+
- WooCommerce 8.0+
- Elementor 3.0+

## Installation

1. Upload the plugin files to `/wp-content/plugins/elementor-wc-meta/`
2. Install dependencies: `composer install`
3. Build assets: `npm install && npm run build`
4. Activate the plugin through WordPress admin
5. The "WC Meta Field" widget will be available in Elementor's WooCommerce elements

## Usage

### In Elementor Editor

1. Add the "WC Meta Field" widget to your layout
2. Select the desired meta field from the dropdown
3. Configure display options:
   - Toggle label visibility
   - Set custom label text
   - Limit number of items (for taxonomies/attributes)
   - Choose separator for multiple values
   - Select HTML tag for output

### In Loop Templates

The widget automatically detects the current product context, making it perfect for:
- Product archive pages
- Loop templates
- Single product layouts
- Custom WooCommerce layouts

## Development

### Project Structure

```
elementor-wc-meta/
├── app/                          # Application code (Laravel-style)
│   ├── Foundation/              # Core application classes
│   ├── Providers/               # Service providers
│   ├── Elementor/              # Elementor integration
│   ├── WooCommerce/            # WooCommerce integration
│   └── Assets/                 # Asset management
├── resources/                   # Frontend resources
│   ├── js/                     # JavaScript files
│   └── scss/                   # Sass stylesheets
├── public/                     # Compiled assets
├── vendor/                     # Composer dependencies
├── composer.json               # PHP dependencies
├── package.json               # Node.js dependencies
├── vite.config.js             # Vite configuration
└── elementor-wc-meta.php      # Main plugin file
```

### Building Assets

```bash
# Development (with watching)
npm run dev

# Production build
npm run build
```

### PHP Dependencies

```bash
# Install PHP dependencies
composer install

# Update dependencies
composer update
```

## Hooks & Filters

### Filters

- `elementor_wc_meta_fields` - Modify available meta fields
- `elementor_wc_meta_get_fields` - Filter meta fields for display

### Actions

- `elementor_wc_meta_before_render` - Before widget render
- `elementor_wc_meta_after_render` - After widget render

## Extending the Plugin

### Adding Custom Meta Fields

```php
add_filter('elementor_wc_meta_fields', function($fields) {
    $fields['custom_field'] = [
        'label' => 'Custom Field',
        'type' => 'meta',
        'meta_key' => '_custom_meta_key',
        'supports_limit' => false,
        'supports_label' => true,
    ];
    return $fields;
});
```

### Custom Widget Styling

Add your custom CSS targeting the widget classes:

```css
.elementor-widget-wc-meta .elementor-wc-meta {
    /* Your custom styles */
}
```

## License

GPL v2 or later

## Support

For support and feature requests, please use the GitHub issues page.
