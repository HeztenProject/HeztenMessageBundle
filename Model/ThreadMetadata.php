<?php

namespace Hezten\MessageBundle\Model;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadMetadata as BaseThreadMetadata;

abstract class ThreadMetadata extends BaseThreadMetadata
{
    protected $participantParent;
    
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
     * @param ParticipantInterface
     * @return null
     */
    public function setParticipant(ParticipantInterface $participant)
    {
        $this->participant = null;
        $this->participantParent = null;

        if(is_subclass_of ($participant,'Hezten\CoreBundle\Model\TeacherInterface'))
            $this->participant = $participant;
        else if(is_subclass_of ($participant,'Hezten\CoreBundle\Model\ParentsInterface'))
            $this->participantParent = $participant;
        else 
            throw new \Exception(sprintf("Unkown sender class. Expected a class inheriting from ParticipantInterface '%s' given",get_class($participant)));
    }

}