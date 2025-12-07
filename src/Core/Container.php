<?php

namespace WPFramework\Core;

/**
 * Dependency Injection Container
 * مدیریت وابستگی‌ها و ایجاد نمونه‌های کلاس‌ها
 */
class Container
{
    /**
     * Binding های ثبت شده
     *
     * @var array
     */
    private $bindings = [];

    /**
     * Singleton instances
     *
     * @var array
     */
    private $instances = [];

    /**
     * ثبت یک Binding
     *
     * @param string $abstract
     * @param callable|string|null $concrete
     * @return void
     */
    public function bind(string $abstract, $concrete = null): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => false,
        ];
    }

    /**
     * ثبت یک Singleton
     *
     * @param string $abstract
     * @param callable|string|null $concrete
     * @return void
     */
    public function singleton(string $abstract, $concrete = null): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => true,
        ];
    }

    /**
     * ثبت یک Instance
     *
     * @param string $abstract
     * @param mixed $instance
     * @return void
     */
    public function instance(string $abstract, $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    /**
     * ساخت یک نمونه از کلاس
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make(string $abstract, array $parameters = [])
    {
        // اگر Instance قبلاً ساخته شده، برگردان
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // اگر Binding وجود دارد
        if (isset($this->bindings[$abstract])) {
            $binding = $this->bindings[$abstract];
            $concrete = $binding['concrete'];

            // اگر callable است، اجرا کن
            if (is_callable($concrete)) {
                $object = call_user_func_array($concrete, $parameters);
            } else {
                $object = $this->build($concrete, $parameters);
            }

            // اگر Singleton است، ذخیره کن
            if ($binding['shared']) {
                $this->instances[$abstract] = $object;
            }

            return $object;
        }

        // در غیر این صورت، سعی کن کلاس را بساز
        return $this->build($abstract, $parameters);
    }

    /**
     * ساخت یک کلاس با Dependency Injection
     *
     * @param string $concrete
     * @param array $parameters
     * @return object
     */
    private function build(string $concrete, array $parameters = [])
    {
        if (!class_exists($concrete)) {
            throw new \Exception("Class {$concrete} not found");
        }

        $reflector = new \ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$concrete} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $concrete();
        }

        $dependencies = $this->resolveDependencies($constructor->getParameters(), $parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * حل کردن وابستگی‌های Constructor
     *
     * @param array $parameters
     * @param array $provided
     * @return array
     */
    private function resolveDependencies(array $parameters, array $provided = []): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            // اگر مقدار از قبل ارائه شده
            if (isset($provided[$parameter->getName()])) {
                $dependencies[] = $provided[$parameter->getName()];
                continue;
            }

            // اگر Type Hint دارد
            $type = $parameter->getType();
            if ($type && !$type->isBuiltin()) {
                $typeName = $type->getName();
                $dependencies[] = $this->make($typeName);
                continue;
            }

            // اگر مقدار پیش‌فرض دارد
            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }

            throw new \Exception("Cannot resolve dependency: {$parameter->getName()}");
        }

        return $dependencies;
    }

    /**
     * بررسی وجود Binding
     *
     * @param string $abstract
     * @return bool
     */
    public function bound(string $abstract): bool
    {
        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]);
    }
}

