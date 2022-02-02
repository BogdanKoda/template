<?php

namespace app\components;

use app\components\EventData\SmsData;
use app\components\Notifications\NotificationsData;
use app\components\Notifications\SaveNotifications;
use app\components\Notifications\SendNotifications;
use app\components\Notifications\SmsNotifications;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\ListenerCollection;
use Yiisoft\EventDispatcher\Provider\Provider;

class EventsListenerInitializer
{
    private ListenerCollection $listenerCollection;
    private ?Provider $provider;
    private ?Dispatcher $dispatcher;

    private static ?self $_instance = null;
    public static function handle(): self
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new self(new ListenerCollection());
        }

        return self::$_instance;
    }

    private function __construct(ListenerCollection $listenerCollection)
    {
        $this->listenerCollection = $listenerCollection;

        // Инициализируем события здесь, но можно и из любого места, после объявления этого класса
        $this->add(new SendNotifications(), NotificationsData::class);
        $this->add(new SaveNotifications(), NotificationsData::class);
        $this->add(new SmsNotifications(), SmsData::class);
    }
    private function __clone() {}

    // Вызываем этот метод, чтобы добавить слушателя
    public function add(callable $listener, string ...$eventClassNames): self
    {
        $this->listenerCollection = $this->listenerCollection->add($listener, ...$eventClassNames);
        return $this;
    }

    public function init(){
        $this->provider = new Provider($this->listenerCollection);
        $this->dispatcher = new Dispatcher($this->provider);
    }

    /**
     * @return ListenerCollection
     */
    public function getListenerCollection(): ListenerCollection
    {
        return $this->listenerCollection;
    }

    /**
     * @return Provider
     */
    public function getProvider(): Provider
    {
        return $this->provider;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher(): Dispatcher
    {
        if(is_null($this->dispatcher)) {
            $this->init();
        }

        return $this->dispatcher;
    }



}