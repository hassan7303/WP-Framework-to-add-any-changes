# مثال‌های عملی استفاده از پلاگین

این فایل شامل مثال‌های عملی برای استفاده از پلاگین است.

## مثال 1: ایجاد یک Service جدید برای مدیریت کاربران

### 1. ایجاد Service

```php
<?php
// src/Services/UserService.php

namespace CustomExperts\Services;

class UserService
{
    /**
     * دریافت اطلاعات کاربر
     *
     * @param int $userId
     * @return \WP_User|null
     */
    public function getUser(int $userId)
    {
        return get_user_by('ID', $userId);
    }

    /**
     * ایجاد کاربر جدید
     *
     * @param array $userData
     * @return int|\WP_Error
     */
    public function createUser(array $userData)
    {
        return wp_insert_user($userData);
    }
}
```

### 2. ثبت Service در AppServiceProvider

```php
// src/Providers/AppServiceProvider.php

public function register(): void
{
    $this->singleton(ExampleService::class);
    $this->singleton(\CustomExperts\Services\UserService::class);
}
```

### 3. استفاده از Service

```php
// در Controller یا Hook
$userService = custom_experts_make(\CustomExperts\Services\UserService::class);
$user = $userService->getUser(1);
```

## مثال 2: ایجاد یک Controller برای مدیریت Ajax Request

### 1. ایجاد Controller

```php
<?php
// src/Controllers/AjaxController.php

namespace CustomExperts\Controllers;

use CustomExperts\Services\UserService;

class AjaxController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle Ajax Request
     */
    public function handleGetUser()
    {
        check_ajax_referer('custom_experts_nonce', 'nonce');

        $userId = intval($_POST['user_id'] ?? 0);
        $user = $this->userService->getUser($userId);

        if ($user) {
            wp_send_json_success([
                'user' => [
                    'id' => $user->ID,
                    'name' => $user->display_name,
                    'email' => $user->user_email,
                ]
            ]);
        }

        wp_send_json_error(['message' => 'User not found']);
    }
}
```

### 2. ثبت Ajax Hook در Service Provider

```php
// src/Providers/AppServiceProvider.php

public function boot(): void
{
    $this->registerHooks();
}

private function registerHooks(): void
{
    // ثبت Ajax Hook
    add_action('wp_ajax_custom_experts_get_user', function() {
        $controller = custom_experts_make(\CustomExperts\Controllers\AjaxController::class);
        $controller->handleGetUser();
    });
}
```

## مثال 3: ایجاد یک Model برای جدول سفارشی

### 1. ایجاد Model

```php
<?php
// src/Models/OrderModel.php

namespace CustomExperts\Models;

class OrderModel
{
    protected $table = 'custom_orders';

    /**
     * دریافت سفارشات یک کاربر
     *
     * @param int $userId
     * @return array
     */
    public function getByUserId(int $userId): array
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$table} WHERE user_id = %d ORDER BY created_at DESC",
                $userId
            )
        );
    }
}
```

### 2. استفاده از Model

```php
$orderModel = custom_experts_make(\CustomExperts\Models\OrderModel::class);
$orders = $orderModel->getByUserId(1);
```

## مثال 4: اضافه کردن یک Shortcode

### در Service Provider

```php
public function boot(): void
{
    $this->registerHooks();
}

private function registerHooks(): void
{
    // ثبت Shortcode
    add_shortcode('custom_experts_display', function($atts) {
        $service = custom_experts_make(\CustomExperts\Services\ExampleService::class);
        $data = $service->getData();
        
        return '<div>' . esc_html($data['message']) . '</div>';
    });
}
```

## مثال 5: اضافه کردن یک Admin Menu

### در Service Provider

```php
public function boot(): void
{
    $this->registerHooks();
}

private function registerHooks(): void
{
    add_action('admin_menu', [$this, 'addAdminMenu']);
}

public function addAdminMenu(): void
{
    add_menu_page(
        'Custom Experts',
        'Custom Experts',
        'manage_options',
        'custom-experts',
        [$this, 'renderAdminPage'],
        'dashicons-admin-generic',
        30
    );
}

public function renderAdminPage(): void
{
    $service = custom_experts_make(\CustomExperts\Services\ExampleService::class);
    $data = $service->getData();
    
    ?>
    <div class="wrap">
        <h1>Custom Experts</h1>
        <p><?php echo esc_html($data['message']); ?></p>
    </div>
    <?php
}
```

## مثال 6: ایجاد یک Service Provider جداگانه

### 1. ایجاد Service Provider

```php
<?php
// src/Providers/AdminServiceProvider.php

namespace CustomExperts\Providers;

use CustomExperts\Core\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ثبت Services مربوط به Admin
    }

    public function boot(): void
    {
        if (is_admin()) {
            add_action('admin_menu', [$this, 'registerAdminMenu']);
        }
    }

    public function registerAdminMenu(): void
    {
        // کد شما
    }
}
```

### 2. اضافه کردن به Config

```php
// config/app.php

'providers' => [
    \CustomExperts\Providers\AppServiceProvider::class,
    \CustomExperts\Providers\AdminServiceProvider::class,
],
```

## نکات مهم

1. **همیشه از Dependency Injection استفاده کنید**: به جای استفاده از `new`، از `custom_experts_make()` استفاده کنید.

2. **Services را Singleton کنید**: برای Services که باید یک نمونه داشته باشند، از `singleton()` استفاده کنید.

3. **منطق کسب‌وکار در Services**: منطق کسب‌وکار را در Services قرار دهید، نه در Controllers.

4. **Controllers برای Request/Response**: Controllers فقط برای مدیریت Request و Response استفاده شوند.

5. **Models برای دیتابیس**: Models فقط برای تعامل با دیتابیس استفاده شوند.

