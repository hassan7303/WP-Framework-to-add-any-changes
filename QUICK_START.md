# راهنمای سریع شروع - WP Framework

## ساختار پلاگین

این فریمورک با ساختار کلاس محور و قابل توسعه طراحی شده است، مشابه فریمورک لاراول.

## اضافه کردن قابلیت جدید

### مرحله 1: ایجاد Service

یک فایل جدید در `src/Services/` ایجاد کنید:

```php
<?php
namespace WPFramework\Services;

class MyNewService
{
    public function doSomething()
    {
        // منطق کسب‌وکار شما
    }
}
```

### مرحله 2: ثبت Service

در `src/Providers/AppServiceProvider.php`:

```php
public function register(): void
{
    $this->singleton(\WPFramework\Services\MyNewService::class);
}
```

### مرحله 3: استفاده از Service

```php
$service = wp_framework_make(\WPFramework\Services\MyNewService::class);
$service->doSomething();
```

## حل مشکل در سایت

### اگر مشکلی در سایت دیدید:

1. **مشکل را شناسایی کنید**: مشکل چیست؟ (مثلاً یک Ajax Request کار نمی‌کند)

2. **Service مربوطه را پیدا یا ایجاد کنید**: 
   - اگر Service وجود دارد، آن را در `src/Services/` پیدا کنید
   - اگر وجود ندارد، یک Service جدید ایجاد کنید

3. **مشکل را حل کنید**: کد را در Service اصلاح کنید

4. **تست کنید**: تغییرات را تست کنید

### مثال: حل مشکل Ajax

```php
// 1. Service را پیدا کنید یا ایجاد کنید
// src/Services/AjaxService.php

// 2. مشکل را حل کنید
public function handleRequest($data)
{
    // کد اصلاح شده
}

// 3. در Controller استفاده کنید
$service = wp_framework_make(\WPFramework\Services\AjaxService::class);
$result = $service->handleRequest($data);
```

## اضافه کردن کد جدید بدون خراب کردن چیزهای دیگر

### نکات مهم:

1. **همیشه از Namespace استفاده کنید**: این باعث می‌شود کلاس‌های شما با کلاس‌های دیگر تداخل نداشته باشند

2. **Services را Singleton کنید**: برای Services که باید یک نمونه داشته باشند

3. **از Dependency Injection استفاده کنید**: به جای `new Class()` از `custom_experts_make(Class::class)` استفاده کنید

4. **منطق را در Services قرار دهید**: Controllers فقط برای Request/Response هستند

### مثال: اضافه کردن یک قابلیت جدید

```php
// 1. Service جدید
// src/Services/EmailService.php
namespace WPFramework\Services;

class EmailService
{
    public function send($to, $subject, $message)
    {
        wp_mail($to, $subject, $message);
    }
}

// 2. ثبت در AppServiceProvider
$this->singleton(\WPFramework\Services\EmailService::class);

// 3. استفاده
$emailService = wp_framework_make(\WPFramework\Services\EmailService::class);
$emailService->send('test@example.com', 'Subject', 'Message');
```

## ساختار پیشنهادی

- **Services**: منطق کسب‌وکار
- **Controllers**: مدیریت Request/Response
- **Models**: تعامل با دیتابیس
- **Helpers**: توابع کمکی

## توابع Helper مفید

- `wp_framework_app()` - دریافت Application
- `wp_framework_make($class)` - ساخت کلاس از Container
- `wp_framework_config($key, $default)` - دریافت Config

## نکته مهم

**همیشه قبل از تغییر کد موجود، یک Backup بگیرید!**

