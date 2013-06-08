<?php

namespace Hezten\MessageBundle\Entity;

use Hezten\MessageBundle\Model\MessageMetadata as BaseMessageMetadata;
use FOS\MessageBundle\Model\MessageInterface;

abstract class MessageMetadata extends BaseMessageMetadata
{
    protected $id;

    protected $message;

    /**
     * Gets the metadata id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param  MessageInterface
     * @return null
     */
    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;
    }
}