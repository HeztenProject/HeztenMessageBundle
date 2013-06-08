<?php

namespace Hezten\MessageBundle\Model;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\MessageMetadata as ModelMessageMetadata;
use FOS\MessageBundle\Model\Message as BaseMessage ;

class Message extends BaseMessage
{
	/**
     * User who sent the message
     *
     * @var ParticipantInterface
     */
    protected $senderParent;


    /**
     * @see FOS\MessageBundle\Model\MessageInterface::getSender()
     */
    public function getSender()
    {
    	if($this->sender != null)
        	return $this->sender;
        else if($this->senderParent != null)
        	return $this->senderParent;
        else return null;
    }

    /**
     * @see FOS\MessageBundle\Model\MessageInterface::setSender()
     */
    public function setSender(ParticipantInterface $sender)
    {
    	$this->sender = null;
    	$this->senderParent = null; 

        if(is_subclass_of ($sender,'Hezten\CoreBundle\Model\TeacherInterface'))
            $this->sender = $sender;
        else if(is_subclass_of ($sender,'Hezten\CoreBundle\Model\ParentsInterface'))
            $this->senderParent = $sender;
        else 
            throw new \Exception(sprintf("Unkown sender class. Expected a class inheriting from ParticipantInterface '%s' given",get_class($participant)));
    }

    /**
     * Get the MessageMetadata for a participant.
     *
     * @param ParticipantInterface $participant
     * @return MessageMetadata
     */
    public function getMetadataForParticipant(ParticipantInterface $participant)
    {
        foreach ($this->metadata as $meta) {
            if (get_class($meta->getParticipant()) == get_class($participant) 
               && $meta->getParticipant()->getId() == $participant->getId()) {
                return $meta;
            }
        }

        return null;
    }

}