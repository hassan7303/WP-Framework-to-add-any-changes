<?php

namespace WPFramework\Controllers;

use WPFramework\Services\ExampleService;

/**
 * کلاس Controller نمونه
 * 
 * Controllers برای مدیریت Request/Response و ارتباط با Services استفاده می‌شوند
 */
class ExampleController
{
    /**
     * نمونه Service
     *
     * @var ExampleService
     */
    private $exampleService;

    /**
     * Constructor با Dependency Injection
     *
     * @param ExampleService $exampleService
     */
    public function __construct(ExampleService $exampleService)
    {
        $this->exampleService = $exampleService;
    }

    /**
     * مثال یک Action
     *
     * @return void
     */
    public function index(): void
    {
        $data = $this->exampleService->getData();
        
        // انجام عملیات مورد نظر
        // مثلاً render کردن view یا return کردن JSON
    }

    /**
     * مثال Action دیگر
     *
     * @param string $input
     * @return string
     */
    public function process(string $input): string
    {
        return $this->exampleService->processData($input);
    }
}

