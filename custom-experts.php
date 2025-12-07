<?php
/**
 * Plugin Name: WP Framework
 * Plugin URI: https://github.com/your-username/wp-framework
 * Description: یک فریمورک حرفه‌ای وردپرس با ساختار کلاس محور، Dependency Injection و Service Providers - مشابه لاراول
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: wp-framework
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Network: false
 */

// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) {
    exit;
}

// تعریف ثابت‌های پلاگین (با prefix منحصر به فرد برای جلوگیری از conflict)
if (!defined('WP_FRAMEWORK_VERSION')) {
    define('WP_FRAMEWORK_VERSION', '1.0.0');
}
if (!defined('WP_FRAMEWORK_PLUGIN_DIR')) {
    define('WP_FRAMEWORK_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('WP_FRAMEWORK_PLUGIN_URL')) {
    define('WP_FRAMEWORK_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('WP_FRAMEWORK_PLUGIN_FILE')) {
    define('WP_FRAMEWORK_PLUGIN_FILE', __FILE__);
}

// بارگذاری Autoloader (اول autoloader ساده، سپس composer)
require_once WP_FRAMEWORK_PLUGIN_DIR . 'includes/autoloader.php';

// بارگذاری Composer Autoloader (اگر وجود دارد)
if (file_exists(WP_FRAMEWORK_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once WP_FRAMEWORK_PLUGIN_DIR . 'vendor/autoload.php';
}

// بارگذاری فایل‌های Helper
require_once CUSTOM_EXPERTS_PLUGIN_DIR . 'includes/functions.php';

/**
 * کلاس اصلی پلاگین
 * استفاده از نام منحصر به فرد برای جلوگیری از conflict با پلاگین‌های دیگر
 */
if (!class_exists('WP_Framework_Plugin')) {
    final class WP_Framework_Plugin
    {
        /**
         * نمونه Singleton از پلاگین
         *
         * @var WP_Framework_Plugin
         */
        private static $instance = null;

        /**
         * نمونه Application
         *
         * @var \WPFramework\Core\Application
         */
        private $app;

        /**
         * دریافت نمونه Singleton
         *
         * @return WP_Framework_Plugin
         */
        public static function getInstance(): WP_Framework_Plugin
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor
         */
        private function __construct()
        {
            $this->init();
        }

        /**
         * جلوگیری از کلون شدن
         */
        private function __clone() {}

        /**
         * جلوگیری از unserialize شدن
         */
        public function __wakeup()
        {
            throw new \Exception("Cannot unserialize singleton");
        }

        /**
         * مقداردهی اولیه پلاگین
         */
        private function init(): void
        {
            // بررسی وجود کلاس Application
            if (!class_exists('\WPFramework\Core\Application')) {
                add_action('admin_notices', [$this, 'adminNoticeMissingClasses']);
                return;
            }

            // ایجاد Application
            $this->app = new \WPFramework\Core\Application();

            // ثبت Hook های وردپرس
            $this->registerHooks();
        }

        /**
         * نمایش پیام خطا در Admin
         */
        public function adminNoticeMissingClasses(): void
        {
            ?>
            <div class="notice notice-error">
                <p>
                    <strong>WP Framework:</strong> 
                    خطا در بارگذاری کلاس‌های پلاگین. لطفاً فایل‌های پلاگین را بررسی کنید.
                </p>
            </div>
            <?php
        }

        /**
         * ثبت Hook های وردپرس
         */
        private function registerHooks(): void
        {
            // فعال‌سازی پلاگین
            register_activation_hook(WP_FRAMEWORK_PLUGIN_FILE, [$this, 'activate']);
            
            // غیرفعال‌سازی پلاگین
            register_deactivation_hook(WP_FRAMEWORK_PLUGIN_FILE, [$this, 'deactivate']);

            // بارگذاری پلاگین
            add_action('plugins_loaded', [$this, 'load'], 10);
        }

        /**
         * فعال‌سازی پلاگین
         */
        public function activate(): void
        {
            // بررسی وجود Application
            if (!$this->app) {
                return;
            }

            // اجرای عملیات فعال‌سازی
            $this->app->activate();
        }

        /**
         * غیرفعال‌سازی پلاگین
         */
        public function deactivate(): void
        {
            // بررسی وجود Application
            if (!$this->app) {
                return;
            }

            // اجرای عملیات غیرفعال‌سازی
            $this->app->deactivate();
        }

        /**
         * بارگذاری پلاگین
         */
        public function load(): void
        {
            // بررسی وجود Application
            if (!$this->app) {
                return;
            }

            // بارگذاری متن‌ها
            load_plugin_textdomain(
                'wp-framework',
                false,
                dirname(plugin_basename(WP_FRAMEWORK_PLUGIN_FILE)) . '/languages'
            );

            // راه‌اندازی Application
            $this->app->boot();
        }

        /**
         * دریافت نمونه Application
         *
         * @return \WPFramework\Core\Application
         */
        public function getApp(): \WPFramework\Core\Application
        {
            return $this->app;
        }
    }
}

/**
 * راه‌اندازی پلاگین
 * استفاده از نام منحصر به فرد برای جلوگیری از conflict
 */
if (!function_exists('wp_framework_init')) {
    function wp_framework_init()
    {
        WP_Framework_Plugin::getInstance();
    }
    
    // راه‌اندازی پلاگین
    wp_framework_init();
}

