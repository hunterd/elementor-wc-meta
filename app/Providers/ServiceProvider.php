<?php

namespace ElementorWcMeta\Providers;

use ElementorWcMeta\Foundation\Application;

/**
 * Base Service Provider class - similar to Laravel's ServiceProvider
 */
abstract class ServiceProvider
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register services
     */
    abstract public function register(): void;

    /**
     * Boot services (optional)
     */
    public function boot(): void
    {
        // Optional implementation in child classes
    }
}
