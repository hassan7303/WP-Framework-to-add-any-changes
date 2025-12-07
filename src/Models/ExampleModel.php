<?php

namespace WPFramework\Models;

/**
 * کلاس Model نمونه
 * 
 * Models برای تعامل با دیتابیس استفاده می‌شوند
 */
class ExampleModel
{
    /**
     * نام جدول
     *
     * @var string
     */
    protected $table = 'wp_framework_data';

    /**
     * دریافت یک رکورد با ID
     *
     * @param int $id
     * @return object|null
     */
    public function find(int $id)
    {
        global $wpdb;
        
        // Sanitize table name برای جلوگیری از SQL Injection
        $table = $wpdb->_escape($wpdb->prefix . $this->table);
        $result = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM `{$table}` WHERE id = %d", $id)
        );

        return $result;
    }

    /**
     * دریافت تمام رکوردها
     *
     * @param array $args
     * @return array
     */
    public function all(array $args = []): array
    {
        global $wpdb;
        
        // Sanitize table name و محدود کردن limit/offset
        $table = $wpdb->_escape($wpdb->prefix . $this->table);
        $limit = isset($args['limit']) ? absint($args['limit']) : 10;
        $limit = min($limit, 100); // حداکثر 100 رکورد
        $offset = isset($args['offset']) ? absint($args['offset']) : 0;

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM `{$table}` LIMIT %d OFFSET %d",
                $limit,
                $offset
            )
        );

        return $results ?: [];
    }

    /**
     * ایجاد یک رکورد جدید
     *
     * @param array $data
     * @return int|false
     */
    public function create(array $data)
    {
        global $wpdb;
        
        // Sanitize table name
        $table = $wpdb->_escape($wpdb->prefix . $this->table);
        
        // WordPress خودش data را sanitize می‌کند اما بهتر است بررسی کنیم
        $result = $wpdb->insert($table, $data, '%s');
        
        if ($result) {
            return absint($wpdb->insert_id);
        }

        return false;
    }

    /**
     * به‌روزرسانی یک رکورد
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        global $wpdb;
        
        // Sanitize table name و ID
        $table = $wpdb->_escape($wpdb->prefix . $this->table);
        $id = absint($id);
        
        if ($id <= 0) {
            return false;
        }
        
        $result = $wpdb->update(
            $table,
            $data,
            ['id' => $id],
            '%s',
            '%d'
        );

        return $result !== false;
    }

    /**
     * حذف یک رکورد
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        global $wpdb;
        
        // Sanitize table name و ID
        $table = $wpdb->_escape($wpdb->prefix . $this->table);
        $id = absint($id);
        
        if ($id <= 0) {
            return false;
        }
        
        $result = $wpdb->delete($table, ['id' => $id], '%d');

        return $result !== false;
    }
}

