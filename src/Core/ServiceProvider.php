<?php

namespace WPFramework\Core;

/**
 * کلاس پایه Service Provider
 * تمام Service Providers باید از این کلاس ارث‌بری کنند
 */
abstract class ServiceProvider
{
    /**
     * نمونه Application
     *
     * @var Application
     */
    protected $app;

    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * ثبت Services در Container
     * این متد باید در کلاس‌های فرزند پیاده‌سازی شود
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * راه‌اندازی Services
     * این متد بعد از register همه Service Providers اجرا می‌شود
     *
     * @return void
     */
    public function boot(): void
    {
        // می‌تواند در کلاس‌های فرزند override شود
    }

    /**
     * Helper برای bind کردن
     *
     * @param string $abstract
     * @param callable|string|null $concrete
     * @return void
     */
    protected function bind(string $abstract, $concrete = null): void
    {
        $this->app->getContainer()->bind($abstract, $concrete);
    }

    /**
     * Helper برای singleton کردن
     *
     * @param string $abstract
     * @param callable|string|null $concrete
     * @return void
     */
    protected function singleton(string $abstract, $concrete = null): void
    {
        $this->app->getContainer()->singleton($abstract, $concrete);
    }
}

