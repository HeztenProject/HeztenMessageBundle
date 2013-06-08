<?php

namespace Hezten\MessageBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Hezten\MessageBundle\Model\Message as BaseMessage;
use FOS\MessageBundle\Model\MessageMetadata;

abstract class Message extends BaseMessage
{
    public function addMetadata(MessageMetadata $meta) 
    {
        $meta->setMessage($this);
        parent::addMetadata($meta);
    }

}