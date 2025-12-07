<?php

namespace WPFramework\Providers;

use WPFramework\Core\ServiceProvider;
use WPFramework\Core\GitHubUpdater;
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
        // راه‌اندازی Auto-Updater از گیت‌هاب
        $this->initAutoUpdater();

        // ثبت Action و Filter های وردپرس
        $this->registerHooks();
    }

    /**
     * راه‌اندازی Auto-Updater
     *
     * @return void
     */
    private function initAutoUpdater(): void
    {
        $githubConfig = $this->app->getConfig('github', []);

        if (empty($githubConfig['username']) || empty($githubConfig['repository'])) {
            return;
        }

        $updater = new GitHubUpdater(
            $githubConfig['username'],
            $githubConfig['repository'],
            $githubConfig['token'] ?? null
        );

        $updater->init();
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

