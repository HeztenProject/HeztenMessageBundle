<?php

namespace Hezten\MessageBundle\Model;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\MessageMetadata as BaseMessageMetadata;

abstract class MessageMetadata extends BaseMessageMetadata
{
    protected $participantParent;

    protected $isRead = false;

    /**
     * @return ParticipantInterface
     */
    public function getParticipant()
    {
        if($this->participant != null)
            return $this->participant;
        else if($this->participantParent != null)
            return $this->participantParent;
        else return null;
    }

    /**
     * @param ParticipantInterface $participant
     * @return null
     */
    public function setParticipant(ParticipantInterface $participant)
    {
        $this->participant = null;
        $this->participantParent = null

        if($participant instanceof Hezten/CoreBundle/Model/TeacherInterface)
            $this->participant = $participant;
        else if($participant instanceof Hezten/CoreBundle/Model/ParentsInterface)
            $this->participantParent = $participant;
    }

}