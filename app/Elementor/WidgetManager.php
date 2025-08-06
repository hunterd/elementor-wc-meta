<?php

namespace ElementorWcMeta\Elementor;

use ElementorWcMeta\Elementor\Widgets\WcMetaWidget;

/**
 * Widget Manager - registers all Elementor widgets
 */
class WidgetManager
{
    /**
     * Available widgets
     */
    private array $widgets = [
        WcMetaWidget::class,
    ];

    /**
     * Register all widgets
     */
    public function registerWidgets(): void
    {
        foreach ($this->widgets as $widgetClass) {
            \Elementor\Plugin::instance()->widgets_manager->register(new $widgetClass());
        }
    }
}
