# Development Guide

## Architecture Overview

This plugin follows a Laravel-inspired architecture with the following key components:

### Application Structure

```
app/
├── Foundation/
│   └── Application.php          # Main application container
├── Providers/
│   ├── ServiceProvider.php      # Base service provider
│   ├── ElementorServiceProvider.php
│   ├── WooCommerceServiceProvider.php
│   └── AssetServiceProvider.php
├── Elementor/
│   ├── WidgetManager.php        # Manages widget registration
│   └── Widgets/
│       └── WcMetaWidget.php     # Main widget implementation
├── WooCommerce/
│   └── MetaFieldsManager.php    # Handles WC meta fields
├── Assets/
│   └── AssetManager.php         # Asset compilation and loading
└── Utils/
    └── Helpers.php              # Utility functions
```

### Service Providers

Service providers are the central place to register plugin functionality:

- **ElementorServiceProvider**: Registers Elementor widgets and controls
- **WooCommerceServiceProvider**: Initializes WooCommerce integration
- **AssetServiceProvider**: Handles frontend/admin asset loading

### Widget Development

The main widget (`WcMetaWidget`) extends Elementor's `Widget_Base` and provides:

1. **Control Registration**: Defines the widget's form controls
2. **Content Rendering**: Outputs the widget HTML
3. **Editor Preview**: Provides preview functionality in Elementor editor

### Meta Fields System

The `MetaFieldsManager` class handles:

- Meta field definitions and configuration
- Value retrieval and formatting
- Support for different field types (taxonomy, meta, attributes, etc.)

## Adding New Meta Fields

To add a new meta field, modify the `MetaFieldsManager::initializeMetaFields()` method:

```php
$this->metaFields['custom_field'] = [
    'label' => __('Custom Field', 'elementor-wc-meta'),
    'type' => 'meta', // or 'taxonomy', 'attributes', 'dimensions'
    'meta_key' => '_custom_meta_key',
    'supports_limit' => false,
    'supports_label' => true,
];
```

### Field Types

1. **meta**: Standard WooCommerce meta fields
2. **taxonomy**: Product categories, tags, custom taxonomies
3. **attributes**: Product attributes
4. **dimensions**: Special handling for product dimensions

## Asset Development

### Frontend Assets

JavaScript files are located in `resources/js/`:
- `admin.js`: Admin-only functionality
- `editor.js`: Elementor editor integration
- `frontend.js`: Public-facing functionality

SCSS files are in `resources/scss/`:
- `style.scss`: Main stylesheet with component-based organization

### Build Process

Assets are compiled using Vite:

```bash
# Development with hot reload
npm run dev

# Production build
npm run build
```

## Hooks and Filters

### Available Filters

```php
// Modify available meta fields
add_filter('elementor_wc_meta_fields', function($fields) {
    // Add or modify fields
    return $fields;
});

// Filter meta fields for display
add_filter('elementor_wc_meta_get_fields', function($fields) {
    // Modify fields before display
    return $fields;
});
```

### Available Actions

```php
// Before widget renders
add_action('elementor_wc_meta_before_render', function($widget_instance) {
    // Custom logic before render
});

// After widget renders
add_action('elementor_wc_meta_after_render', function($widget_instance) {
    // Custom logic after render
});
```

## Testing

### Running Tests

```bash
# Install PHPUnit (if not already installed)
composer require --dev phpunit/phpunit

# Run tests
vendor/bin/phpunit tests/
```

### Static Analysis

```bash
# Run PHPStan
vendor/bin/phpstan analyse
```

### Code Standards

```bash
# Check coding standards
vendor/bin/phpcs --standard=WordPress app/

# Fix coding standards
vendor/bin/phpcbf --standard=WordPress app/
```

## Debugging

### Debug Mode

Enable WordPress debug mode in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

### Logging

Use the helper method for logging:

```php
use ElementorWcMeta\Utils\Helpers;

Helpers::log('Debug message', 'debug');
Helpers::log('Error message', 'error');
```

## Performance Considerations

### Caching

- Meta field definitions are cached using WordPress transients
- Widget output can be cached using Elementor's built-in caching
- Consider implementing object cache for heavy operations

### Database Queries

- Meta field retrieval is optimized to use WooCommerce's existing queries
- Batch operations when possible for multiple products
- Use appropriate WordPress hooks to avoid duplicate queries

### Asset Loading

- Assets are only loaded when needed (conditional loading)
- Production builds are minified and optimized
- CSS/JS are split by context (admin, editor, frontend)

## Deployment

### Production Checklist

1. Build assets: `npm run build`
2. Install production dependencies: `composer install --no-dev --optimize-autoloader`
3. Remove development files:
   - `node_modules/`
   - Development dependencies in `vendor/`
   - `.git/` directory
   - Development configuration files
   - Source files in `resources/` (keep only `public/dist/`)

### Automated Production Build

Use the provided script for automated production packaging:

```bash
./build-production.sh
```

This script will:
- Clean previous builds
- Install production dependencies only
- Build and optimize assets
- Create a production-ready ZIP package
- Validate the package

### Production Validation

Before deployment, run the production checker:

```bash
php check-production.php
```

This validates:
- Environment requirements
- Dependencies
- Asset compilation
- Security considerations
- Performance optimizations

### Version Updates

1. Update version in `elementor-wc-meta.php`
2. Update version in `composer.json`
3. Update version in `package.json`
4. Generate new language files if needed
5. Update README.md with changelog
6. Build production package: `./build-production.sh`

### Deployment Methods

1. **WordPress Plugin Directory**: Upload ZIP package
2. **Manual Installation**: Upload to `/wp-content/plugins/`
3. **Git Deployment**: Use production branch with built assets
4. **Automated CI/CD**: Integrate build script in deployment pipeline
