<?php

/**
 * توابع Helper عمومی
 * 
 * این توابع در سراسر پلاگین قابل استفاده هستند
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper برای دسترسی به Application
 *
 * @return \WPFramework\Core\Application|null
 */
function wp_framework_app()
{
    if (!class_exists('WP_Framework_Plugin')) {
        return null;
    }
    
    $plugin = WP_Framework_Plugin::getInstance();
    return $plugin->getApp();
}

/**
 * Helper برای ساخت یک Service از Container
 *
 * @param string $abstract
 * @param array $parameters
 * @return mixed
 */
function wp_framework_make(string $abstract, array $parameters = [])
{
    $app = wp_framework_app();
    if (!$app) {
        return null;
    }
    return $app->make($abstract, $parameters);
}

/**
 * Helper برای دریافت Config
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function wp_framework_config(string $key, $default = null)
{
    $app = wp_framework_app();
    if (!$app) {
        return $default;
    }
    return $app->getConfig($key, $default);
}

