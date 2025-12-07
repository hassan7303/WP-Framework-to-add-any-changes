<?php
/**
 * Autoloader ساده برای پلاگین
 * در صورت عدم وجود Composer autoloader از این استفاده می‌شود
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Autoloader برای namespace WPFramework
 *
 * @param string $class
 * @return void
 */
function wp_framework_autoloader($class)
{
    // فقط برای namespace ما
    if (strpos($class, 'WPFramework\\') !== 0) {
        return;
    }

    // تبدیل namespace به مسیر فایل
    $class = str_replace('WPFramework\\', '', $class);
    $class = str_replace('\\', '/', $class);
    
    // مسیر فایل
    $file = WP_FRAMEWORK_PLUGIN_DIR . 'src/' . $class . '.php';

    // بارگذاری فایل اگر وجود دارد
    if (file_exists($file)) {
        require_once $file;
    }
}

// ثبت autoloader
spl_autoload_register('wp_framework_autoloader');

