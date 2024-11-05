<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2023 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace think\console\command\make;

use think\console\command\Make;

class Observer extends Make
{
    protected $type = "Observer";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:observer')
            ->setDescription('Create a new observer class');
    }

    protected function getStub(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'observer.stub';
    }

    protected function getNamespace(string $app): string
    {
        return parent::getNamespace($app) . '\\observer';
    }
}
