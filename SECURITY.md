# راهنمای امنیتی - WP Framework

این سند شامل نکات امنیتی مهم و بهترین روش‌های پیاده‌سازی شده در پلاگین است.

## ✅ اقدامات امنیتی پیاده‌سازی شده

### 1. جلوگیری از دسترسی مستقیم
- تمام فایل‌ها با بررسی `ABSPATH` محافظت شده‌اند
- جلوگیری از دسترسی مستقیم به فایل‌های PHP

### 2. SQL Injection Prevention
- استفاده از `$wpdb->prepare()` برای تمام Query ها
- استفاده از `$wpdb->_escape()` برای نام جداول
- استفاده از `absint()` برای ID ها
- محدود کردن limit/offset

### 3. XSS Prevention
- استفاده از `esc_html()` برای خروجی HTML
- استفاده از `esc_url()` و `esc_url_raw()` برای URL ها
- استفاده از `esc_attr()` برای attributes
- Escape تمام متغیرها در sprintf

### 4. Input Sanitization
- استفاده از `sanitize_text_field()` برای متن
- استفاده از `sanitize_user()` برای نام کاربری
- استفاده از `sanitize_file_name()` برای نام فایل
- استفاده از `urlencode()` برای URL parameters

### 5. GitHub Token Security
- Token در Authorization Header ارسال می‌شود (نه در URL)
- Token با `sanitize_text_field()` sanitize می‌شود
- جلوگیری از نمایش Token در URL

### 6. Capability Checks
- استفاده از `manage_options` برای دسترسی Admin
- بررسی دسترسی قبل از اجرای عملیات حساس

### 7. Nonce Verification
- استفاده از `check_ajax_referer()` برای AJAX requests
- استفاده از `wp_create_nonce()` برای ایجاد nonce

## 🔒 بهترین روش‌ها برای توسعه

### هنگام کار با دیتابیس:

```php
// ✅ درست
global $wpdb;
$table = $wpdb->_escape($wpdb->prefix . 'my_table');
$id = absint($id);
$result = $wpdb->get_row(
    $wpdb->prepare("SELECT * FROM `{$table}` WHERE id = %d", $id)
);

// ❌ اشتباه
$result = $wpdb->get_row("SELECT * FROM {$table} WHERE id = {$id}");
```

### هنگام خروجی HTML:

```php
// ✅ درست
echo esc_html($user_input);
echo esc_url($url);
echo esc_attr($attribute);

// ❌ اشتباه
echo $user_input;
```

### هنگام دریافت Input:

```php
// ✅ درست
$input = sanitize_text_field($_POST['input'] ?? '');
$id = absint($_GET['id'] ?? 0);
$email = sanitize_email($_POST['email'] ?? '');

// ❌ اشتباه
$input = $_POST['input'];
$id = $_GET['id'];
```

### برای AJAX Requests:

```php
// ✅ درست
public function handleAjax()
{
    check_ajax_referer('my_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized']);
    }
    
    $data = sanitize_text_field($_POST['data'] ?? '');
    // ...
}

// ❌ اشتباه
public function handleAjax()
{
    $data = $_POST['data'];
    // ...
}
```

### برای Admin Pages:

```php
// ✅ درست
public function renderAdminPage()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions.'));
    }
    
    echo '<div>' . esc_html($data) . '</div>';
}

// ❌ اشتباه
public function renderAdminPage()
{
    echo '<div>' . $data . '</div>';
}
```

## ⚠️ نکات مهم

1. **هرگز به کاربر اعتماد نکنید**: تمام ورودی‌های کاربر را sanitize کنید
2. **از Prepared Statements استفاده کنید**: هرگز Query را با concatenation نسازید
3. **Output را Escape کنید**: تمام خروجی‌ها را escape کنید
4. **Capability Checks**: همیشه دسترسی کاربر را بررسی کنید
5. **Nonce Verification**: برای تمام فرم‌ها و AJAX requests از nonce استفاده کنید
6. **Token Security**: Token ها را هرگز در URL قرار ندهید

## 🔍 بررسی امنیتی

قبل از انتشار، موارد زیر را بررسی کنید:

- [ ] تمام Query ها از `$wpdb->prepare()` استفاده می‌کنند
- [ ] تمام خروجی‌ها escape شده‌اند
- [ ] تمام ورودی‌ها sanitize شده‌اند
- [ ] Capability checks برای Admin functions وجود دارد
- [ ] Nonce verification برای AJAX requests وجود دارد
- [ ] Token ها در URL قرار نگرفته‌اند
- [ ] فایل‌ها با `ABSPATH` محافظت شده‌اند

## 📚 منابع بیشتر

- [WordPress Security Handbook](https://developer.wordpress.org/plugins/security/)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [WordPress Data Validation](https://developer.wordpress.org/plugins/security/data-validation/)

