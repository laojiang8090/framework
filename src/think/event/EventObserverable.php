<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace think\event;

use think\Container;
use think\exception\EventException;
use think\facade\Event;
use think\helper\Str;

trait EventObserverable
{
    /**
     * 是否需要事件响应.
     *
     * @var bool
     */
    protected $withEvent = true;

    /**
     * 事件观察者.
     *
     * @var string
     */
    protected $eventObserver;

    /**
     * 当前操作的事件响应.
     *
     * @param bool $event 是否需要事件响应
     *
     * @return $this
     */
    public function withEvent(bool $event)
    {
        $this->withEvent = $event;

        return $this;
    }

    /**
     * 设置事件观察者.
     *
     * @param string $observer 事件观察者
     *
     * @return $this
     */
    public function observer(string $observer)
    {
        $this->eventObserver = $observer;

        return $this;
    }

    /**
     * 触发事件.
     *
     * @param string $event  事件名
     * @param mixed  $params 参数
     *
     * @return bool
     */
    protected function trigger(string $event, $params = null): bool
    {
        if (!$this->withEvent) {
            return true;
        }

        if (str_contains($event, '.')) {
            [$name, $event] = explode('.', $event, 2);
            $observer  = Event::getObserver($name);
            $eventName = $name . '.' . $event;
        } else {
            $observer  = $this->eventObserver ?: static::class;
        }

        $call = 'on' . Str::studly($event);

        try {
            if ($observer && method_exists($observer, $call)) {
                $result = Container::getInstance()->invoke([$observer, $call], [$params ?: $this]);
            } else {
                $result = Event::trigger($eventName ?? $event, $params ?: $this);
                $result = empty($result) ? true : end($result);
            }

            return !(false === $result);
        } catch (EventException $e) {
            return false;
        }
    }
}
