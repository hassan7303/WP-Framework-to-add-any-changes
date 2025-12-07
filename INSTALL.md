# راهنمای نصب و راه‌اندازی - WP Framework

## پیش‌نیازها

- PHP 7.4 یا بالاتر
- (اختیاری) Composer
- وردپرس 5.0 یا بالاتر

## مراحل نصب

### 1. نصب Composer Dependencies (اختیاری)

در پوشه پلاگین، دستور زیر را اجرا کنید:

```bash
composer install
```

**نکته:** پلاگین دارای Autoloader داخلی است و بدون Composer هم کار می‌کند.

### 2. فعال‌سازی پلاگین

1. پلاگین را در پوشه `wp-content/plugins` کپی کنید
2. به پنل مدیریت وردپرس بروید
3. به بخش Plugins بروید
4. پلاگین "WP Framework" را فعال کنید

### 3. بررسی نصب

بعد از فعال‌سازی، پلاگین باید بدون خطا کار کند. می‌توانید با استفاده از تابع زیر در کد خود بررسی کنید:

```php
if (function_exists('wp_framework_app')) {
    // پلاگین فعال است
    $app = wp_framework_app();
}
```

## ساختار فایل‌ها

بعد از نصب، ساختار پلاگین باید به این صورت باشد:

```
wp-framework/
├── config/
├── src/
├── includes/
├── assets/
├── vendor/          # بعد از composer install (اختیاری)
├── wp-framework.php
└── composer.json
```

## توسعه

برای شروع توسعه:

1. یک Service جدید در `src/Services/` ایجاد کنید
2. آن را در `AppServiceProvider` ثبت کنید
3. از `custom_experts_make()` برای استفاده از آن استفاده کنید

برای مثال‌های بیشتر، فایل `EXAMPLE.md` را مطالعه کنید.

## عیب‌یابی

### خطای "Class not found"

اگر با خطای "Class not found" مواجه شدید:

1. فایل `includes/autoloader.php` باید وجود داشته باشد
2. Namespace کلاس‌ها را بررسی کنید (باید `WPFramework\` شروع شود)
3. اگر از Composer استفاده می‌کنید، `composer install` را اجرا کنید

### پلاگین فعال نمی‌شود

1. خطاهای PHP را در فایل `debug.log` بررسی کنید
2. مطمئن شوید PHP 7.4 یا بالاتر دارید
3. بررسی کنید که تمام فایل‌ها درست کپی شده‌اند

