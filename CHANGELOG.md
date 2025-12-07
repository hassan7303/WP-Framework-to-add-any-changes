# Changelog - WP Framework

## [1.0.0] - 2025-12-07

### تغییرات اصلی

- تغییر نام پلاگین از "Custom Experts" به "WP Framework"
- تغییر Namespace از `CustomExperts` به `WPFramework`
- تغییر نام توابع Helper:
  - `custom_experts_app()` → `wp_framework_app()`
  - `custom_experts_make()` → `wp_framework_make()`
  - `custom_experts_config()` → `wp_framework_config()`
- تغییر نام ثابت‌ها:
  - `CUSTOM_EXPERTS_*` → `WP_FRAMEWORK_*`
- تغییر نام کلاس اصلی:
  - `CustomExperts_Plugin` → `WP_Framework_Plugin`
- تغییر نام فایل اصلی:
  - `custom-experts.php` → `wp-framework.php`

### بهبودها

- به‌روزرسانی مستندات
- بهبود توضیحات برای استفاده عمومی
- به‌روزرسانی README با اطلاعات کامل‌تر

### ویژگی‌ها

- ساختار کلاس محور
- Dependency Injection Container
- Service Providers
- Autoloader داخلی (بدون نیاز به Composer)
- جلوگیری از Conflict با پلاگین‌های دیگر

