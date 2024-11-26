<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2023 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think\route\dispatch;

use think\route\Dispatch;

/**
 * Callback Dispatcher
 */
class Callback extends Dispatch
{
    public function exec()
    {
        // 执行回调方法
        if (is_array($this->dispatch)) {
            [$class, $action] = $this->dispatch;

            // 设置当前请求的控制器、操作
            $controllerLayer      = $this->rule->config('controller_layer') ?: 'controller';
            [$layer, $controller] = explode('/' . $controllerLayer . '/', trim(str_replace('\\', '/', $class), '/'));
            $this->request
                ->setLayer(trim(str_replace('app', '', $layer), '/'))
                ->setController($controller)
                ->setAction($action);

            $instance = $this->app->invokeClass($class);
            return $this->responseWithMiddlewarePipeline($instance, $action);
        }

        $vars = $this->getActionBindVars();
        return $this->app->invoke($this->dispatch, $vars);
    }
}
