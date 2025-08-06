<?php

namespace ElementorWcMeta\Providers;

use ElementorWcMeta\Elementor\WidgetManager;

/**
 * Elementor Service Provider
 */
class ElementorServiceProvider extends ServiceProvider
{
    /**
     * Register Elementor services
     */
    public function register(): void
    {
        // Register widget manager
        add_action('elementor/widgets/widgets_registered', [$this, 'registerWidgets']);
        add_action('elementor/controls/controls_registered', [$this, 'registerControls']);
    }

    /**
     * Boot Elementor services
     */
    public function boot(): void
    {
        // Additional initialization if needed
    }

    /**
     * Register custom widgets
     */
    public function registerWidgets(): void
    {
        $widgetManager = new WidgetManager();
        $widgetManager->registerWidgets();
    }

    /**
     * Register custom controls
     */
    public function registerControls(): void
    {
        // Register custom controls if needed
    }
}
