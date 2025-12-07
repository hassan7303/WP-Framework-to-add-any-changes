<?php

namespace WPFramework\Core;

/**
 * کلاس اصلی Application
 * مدیریت کلی پلاگین و Service Providers
 */
class Application
{
    /**
     * Container برای Dependency Injection
     *
     * @var Container
     */
    private $container;

    /**
     * لیست Service Providers
     *
     * @var array
     */
    private $serviceProviders = [];

    /**
     * وضعیت راه‌اندازی
     *
     * @var bool
     */
    private $booted = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->registerBaseBindings();
    }

    /**
     * ثبت Binding های پایه
     */
    private function registerBaseBindings(): void
    {
        $this->container->singleton(Container::class, function () {
            return $this->container;
        });

        $this->container->singleton(Application::class, function () {
            return $this;
        });
    }

    /**
     * ثبت Service Provider
     *
     * @param string $provider
     * @return $this
     */
    public function register(string $provider): self
    {
        if (isset($this->serviceProviders[$provider])) {
            return $this;
        }

        $instance = new $provider($this);
        $instance->register();
        
        $this->serviceProviders[$provider] = $instance;

        if ($this->booted) {
            $instance->boot();
        }

        return $this;
    }

    /**
     * راه‌اندازی Application
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        // بارگذاری Service Providers از Config
        $this->loadServiceProviders();

        // Boot کردن Service Providers
        foreach ($this->serviceProviders as $provider) {
            $provider->boot();
        }

        $this->booted = true;
    }

    /**
     * بارگذاری Service Providers از Config
     */
    private function loadServiceProviders(): void
    {
        $providers = $this->getConfig('app.providers', []);

        foreach ($providers as $provider) {
            $this->register($provider);
        }
    }

    /**
     * فعال‌سازی پلاگین
     */
    public function activate(): void
    {
        // اجرای عملیات فعال‌سازی
        // مثلاً ایجاد جداول دیتابیس، تنظیمات اولیه و...
        
        // ذخیره نسخه پلاگین
        update_option('wp_framework_version', WP_FRAMEWORK_VERSION);
        
        // اجرای Hook برای Service Providers
        do_action('wp_framework_activated');
    }

    /**
     * غیرفعال‌سازی پلاگین
     */
    public function deactivate(): void
    {
        // اجرای عملیات غیرفعال‌سازی
        
        // اجرای Hook برای Service Providers
        do_action('wp_framework_deactivated');
    }

    /**
     * دریافت Container
     *
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Resolve کردن یک کلاس از Container
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make(string $abstract, array $parameters = [])
    {
        return $this->container->make($abstract, $parameters);
    }

    /**
     * دریافت Config
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig(string $key, $default = null)
    {
        $configFile = WP_FRAMEWORK_PLUGIN_DIR . 'config/app.php';
        
        if (!file_exists($configFile)) {
            return $default;
        }

        $config = require $configFile;
        $keys = explode('.', $key);
        $value = $config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }
}

