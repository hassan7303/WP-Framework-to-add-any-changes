<?php

namespace WPFramework\Providers;

use WPFramework\Core\ServiceProvider;
use WPFramework\Services\ExampleService;

/**
 * Service Provider اصلی پلاگین
 * اینجا تمام Services و Bindings را ثبت می‌کنیم
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * ثبت Services
     *
     * @return void
     */
    public function register(): void
    {
        // ثبت Services به صورت Singleton
        $this->singleton(ExampleService::class);

        // می‌توانید Services بیشتری اینجا اضافه کنید
        // $this->singleton(AnotherService::class);
    }

    /**
     * راه‌اندازی Services
     *
     * @return void
     */
    public function boot(): void
    {
        // ثبت Action و Filter های وردپرس
        $this->registerHooks();
    }

    /**
     * ثبت Hook های وردپرس
     *
     * @return void
     */
    private function registerHooks(): void
    {
        // مثال: ثبت یک Action
        // add_action('init', [$this, 'onInit']);
    }

    /**
     * مثال: Hook برای init
     *
     * @return void
     */
    public function onInit(): void
    {
        // کد شما اینجا
    }
}

