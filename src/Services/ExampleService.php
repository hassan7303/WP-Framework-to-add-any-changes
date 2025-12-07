<?php

namespace WPFramework\Services;

/**
 * کلاس Service نمونه
 * 
 * Services برای منطق کسب‌وکار استفاده می‌شوند
 * این کلاس‌ها مستقل از وردپرس هستند و قابل تست می‌باشند
 */
class ExampleService
{
    /**
     * مثال یک متد Service
     *
     * @param string $data
     * @return string
     */
    public function processData(string $data): string
    {
        // منطق کسب‌وکار شما اینجا
        return strtoupper($data);
    }

    /**
     * مثال متد دیگر
     *
     * @return array
     */
    public function getData(): array
    {
        return [
            'message' => 'Hello from ExampleService',
            'timestamp' => current_time('mysql'),
        ];
    }
}

