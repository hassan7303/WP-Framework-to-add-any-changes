<?php

namespace WPFramework\Helpers;

/**
 * کلاس Helper
 * 
 * توابع کمکی که در سراسر پلاگین استفاده می‌شوند
 */
class Helper
{
    /**
     * بررسی اینکه آیا در محیط Admin هستیم یا نه
     *
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return is_admin();
    }

    /**
     * دریافت URL پلاگین
     *
     * @param string $path
     * @return string
     */
    public static function pluginUrl(string $path = ''): string
    {
        $url = WP_FRAMEWORK_PLUGIN_URL;
        
        if ($path) {
            $url .= ltrim($path, '/');
        }

        return $url;
    }

    /**
     * دریافت مسیر پلاگین
     *
     * @param string $path
     * @return string
     */
    public static function pluginPath(string $path = ''): string
    {
        $pluginPath = WP_FRAMEWORK_PLUGIN_DIR;
        
        if ($path) {
            $pluginPath .= ltrim($path, '/');
        }

        return $pluginPath;
    }

    /**
     * Log کردن برای Debug
     *
     * @param mixed $data
     * @param string $label
     * @return void
     */
    public static function log($data, string $label = ''): void
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        $message = $label ? "[{$label}] " : '';
        $message .= print_r($data, true);
        
        error_log($message);
    }

    /**
     * دریافت Option با مقدار پیش‌فرض
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getOption(string $key, $default = null)
    {
        return get_option("wp_framework_{$key}", $default);
    }

    /**
     * ذخیره Option
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function setOption(string $key, $value): bool
    {
        return update_option("wp_framework_{$key}", $value);
    }
}

