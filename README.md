# WP Framework - فریمورک حرفه‌ای وردپرس

یک فریمورک حرفه‌ای وردپرس با ساختار کلاس محور، Dependency Injection و Service Providers - مشابه فریمورک لاراول.

## ✨ ویژگی‌ها

- ✅ ساختار کلاس محور و قابل نگهداری
- ✅ Dependency Injection Container
- ✅ Service Providers برای مدیریت Services
- ✅ ساختار MVC (Model, View, Controller)
- ✅ Autoloading با PSR-4
- ✅ قابل توسعه و تست
- ✅ بدون نیاز به Composer (Autoloader داخلی)
- ✅ جلوگیری از Conflict با پلاگین‌های دیگر

## 📦 نصب

1. پلاگین را در پوشه `wp-content/plugins` کپی کنید
2. (اختیاری) Composer را نصب کنید:
```bash
composer install
```
3. پلاگین را از پنل مدیریت وردپرس فعال کنید

## 📁 ساختار پلاگین

```
wp-framework/
├── config/              # فایل‌های تنظیمات
│   └── app.php
├── src/                 # کدهای اصلی
│   ├── Core/           # کلاس‌های هسته
│   │   ├── Application.php
│   │   ├── Container.php
│   │   └── ServiceProvider.php
│   ├── Providers/      # Service Providers
│   │   └── AppServiceProvider.php
│   ├── Services/       # کلاس‌های Service
│   ├── Controllers/    # کلاس‌های Controller
│   ├── Models/         # کلاس‌های Model
│   └── Helpers/        # کلاس‌های Helper
├── includes/           # فایل‌های کمکی
│   ├── autoloader.php
│   └── functions.php
├── assets/            # فایل‌های استاتیک
│   ├── css/
│   └── js/
├── wp-framework.php   # فایل اصلی پلاگین
└── composer.json
```

## 🚀 نحوه استفاده

### ایجاد یک Service جدید

1. یک کلاس Service در `src/Services/` ایجاد کنید:

```php
<?php

namespace WPFramework\Services;

class MyNewService
{
    public function doSomething()
    {
        // کد شما
    }
}
```

2. Service را در `AppServiceProvider` ثبت کنید:

```php
public function register(): void
{
    $this->singleton(MyNewService::class);
}
```

3. استفاده از Service:

```php
// در Controller یا هر جای دیگر
$service = wp_framework_make(\WPFramework\Services\MyNewService::class);
$service->doSomething();
```

### ایجاد Controller جدید

```php
<?php

namespace WPFramework\Controllers;

use WPFramework\Services\MyNewService;

class MyController
{
    private $myService;

    public function __construct(MyNewService $myService)
    {
        $this->myService = $myService;
    }

    public function index()
    {
        // کد شما
    }
}
```

### ایجاد Model جدید

```php
<?php

namespace WPFramework\Models;

class MyModel
{
    protected $table = 'my_table';

    public function find($id)
    {
        global $wpdb;
        // کد شما
    }
}
```

### اضافه کردن Service Provider جدید

1. یک Service Provider جدید ایجاد کنید:

```php
<?php

namespace WPFramework\Providers;

use WPFramework\Core\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ثبت Services
    }

    public function boot(): void
    {
        // راه‌اندازی Services
    }
}
```

2. در `config/app.php` اضافه کنید:

```php
'providers' => [
    \WPFramework\Providers\AppServiceProvider::class,
    \WPFramework\Providers\MyServiceProvider::class,
],
```

## 🔧 توابع Helper

- `wp_framework_app()` - دریافت Application
- `wp_framework_make($class)` - ساخت یک کلاس از Container
- `wp_framework_config($key, $default)` - دریافت Config

## 📝 مثال‌های عملی

برای مثال‌های بیشتر، فایل `EXAMPLE.md` را مطالعه کنید.

## ⚙️ نکات مهم

- همیشه از Namespace استفاده کنید
- Services را در Service Providers ثبت کنید
- از Dependency Injection استفاده کنید
- کدهای منطق کسب‌وکار را در Services قرار دهید
- Controllers فقط برای مدیریت Request/Response استفاده شوند
- Models برای تعامل با دیتابیس استفاده شوند

## 🔄 توسعه

برای اضافه کردن قابلیت جدید:

1. Service مربوطه را ایجاد کنید
2. در Service Provider ثبت کنید
3. در Controller یا Hook استفاده کنید

این ساختار به شما امکان می‌دهد بدون تغییر در کدهای موجود، قابلیت‌های جدید اضافه کنید.

## 🔄 Auto-Updater از گیت‌هاب

پلاگین دارای سیستم آپدیت خودکار از گیت‌هاب است. برای فعال‌سازی:

1. فایل `config/app.php` را باز کنید
2. اطلاعات گیت‌هاب را وارد کنید:

```php
'github' => [
    'username' => 'your-username',      // نام کاربری گیت‌هاب
    'repository' => 'wp-framework',      // نام repository
    'token' => '',                       // GitHub Token (اختیاری)
],
```

3. بعد از هر Release در گیت‌هاب، پلاگین به صورت خودکار آپدیت می‌شود

**نکته:** برای استفاده از Private Repository، باید یک GitHub Personal Access Token ایجاد کنید.

## 📄 لایسنس

MIT License - برای جزئیات بیشتر فایل LICENSE را مطالعه کنید.

## 👥 مشارکت

مشارکت‌ها خوش‌آمدند! لطفاً Pull Request ارسال کنید.

## 📞 پشتیبانی

برای سوالات و مشکلات، Issue ایجاد کنید.
