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
        
        $table = $wpdb->prefix . $this->table;
        $result = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $id)
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
        
        $table = $wpdb->prefix . $this->table;
        $limit = isset($args['limit']) ? intval($args['limit']) : 10;
        $offset = isset($args['offset']) ? intval($args['offset']) : 0;

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$table} LIMIT %d OFFSET %d",
                $limit,
                $offset
            )
        );

        return $results;
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
        
        $table = $wpdb->prefix . $this->table;
        
        $result = $wpdb->insert($table, $data);
        
        if ($result) {
            return $wpdb->insert_id;
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
        
        $table = $wpdb->prefix . $this->table;
        
        $result = $wpdb->update(
            $table,
            $data,
            ['id' => $id]
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
        
        $table = $wpdb->prefix . $this->table;
        
        $result = $wpdb->delete($table, ['id' => $id]);

        return $result !== false;
    }
}

