# راهنمای Auto-Updater از گیت‌هاب

این پلاگین دارای سیستم آپدیت خودکار از گیت‌هاب است که به شما امکان می‌دهد با هر Release جدید در گیت‌هاب، پلاگین به صورت خودکار در تمام سایت‌ها آپدیت شود.

## فعال‌سازی Auto-Updater

### مرحله 1: تنظیمات در config/app.php

فایل `config/app.php` را باز کنید و اطلاعات گیت‌هاب را وارد کنید:

```php
'github' => [
    'username' => 'your-username',      // نام کاربری گیت‌هاب شما
    'repository' => 'wp-framework',     // نام repository شما
    'token' => '',                      // GitHub Personal Access Token (اختیاری)
],
```

### مرحله 2: ایجاد Release در گیت‌هاب

1. به repository خود در گیت‌هاب بروید
2. به بخش **Releases** بروید
3. روی **Create a new release** کلیک کنید
4. یک Tag جدید ایجاد کنید (مثلاً `v1.0.1`)
5. عنوان و توضیحات Release را وارد کنید
6. روی **Publish release** کلیک کنید

### مرحله 3: بررسی آپدیت

1. به پنل مدیریت وردپرس بروید
2. به بخش **Plugins** بروید
3. اگر آپدیت جدیدی وجود داشته باشد، پیام آپدیت نمایش داده می‌شود
4. روی **Update Now** کلیک کنید

## استفاده از Private Repository

اگر repository شما Private است، باید یک GitHub Personal Access Token ایجاد کنید:

1. به GitHub Settings → Developer settings → Personal access tokens → Tokens (classic) بروید
2. روی **Generate new token** کلیک کنید
3. دسترسی `repo` را انتخاب کنید
4. Token را کپی کنید
5. در `config/app.php` در قسمت `token` قرار دهید:

```php
'token' => 'ghp_your_token_here',
```

## نحوه کار

1. پلاگین هر 12 ساعت یکبار به صورت خودکار گیت‌هاب را بررسی می‌کند
2. اگر Release جدیدی پیدا شود، در لیست آپدیت‌ها نمایش داده می‌شود
3. کاربر می‌تواند به صورت دستی یا خودکار آپدیت را انجام دهد

## غیرفعال‌سازی

برای غیرفعال کردن Auto-Updater، در `config/app.php`:

```php
'github' => [
    'username' => '',
    'repository' => '',
    'token' => '',
],
```

## نکات مهم

- Tag های Release باید با فرمت `v1.0.0` یا `1.0.0` باشند
- نسخه در فایل `wp-framework.php` باید با Tag Release مطابقت داشته باشد
- برای تست، می‌توانید Cache را پاک کنید: `delete_transient('wp_framework_latest_release')`

## عیب‌یابی

### آپدیت نمایش داده نمی‌شود

1. بررسی کنید که Release در گیت‌هاب ایجاد شده باشد
2. Cache را پاک کنید
3. بررسی کنید که `username` و `repository` درست باشند
4. اگر repository Private است، Token را بررسی کنید

### خطای دانلود

1. بررسی کنید که URL دانلود درست باشد
2. اگر repository Private است، Token را بررسی کنید
3. بررسی کنید که سرور به گیت‌هاب دسترسی داشته باشد

