<?php

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;
use Vich\UploaderBundle\Storage\StorageInterface;

class DocumentSubscriber implements EventSubscriberInterface
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function onRemove(Event $event)
    {
        $path = $this->storage->resolveUri($event->getObject(), $event->getMapping()->getFilePropertyName());
    }

    public static function getSubscribedEvents()
    {
        return [Events::PRE_REMOVE => 'onRemove'];
    }
}