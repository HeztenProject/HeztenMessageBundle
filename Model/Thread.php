<?php

namespace Hezten\MessageBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\Thread as BaseThread;

/**
 * Abstract thread model
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class Thread extends BaseThread
{
    /**
     * Users participating in this conversation
     *
     * @var Collection of ParticipantInterface
     */
    protected $participantsParents;

    /**
     * Participant that created the thread
     *
     * @var ParticipantInterface
     */
    protected $createdByParent;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->metadata = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    /**
     * @see FOS\MessageBundle\Model\ThreadInterface::getCreatedBy()
     */
    public function getCreatedBy()
    {
        if($this->createdBy != null)
            return $this->createdBy;
        else if($this->createdByParent != null)
            return $this->createdByParent;
        else return null;
    }

    /**
     * @see FOS\MessageBundle\Model\ThreadInterface::setCreatedBy()
     */
    public function setCreatedBy(ParticipantInterface $participant)
    {
        $this->createdBy = null;
        $this->createdByParent = null

        if($createdBy instanceof Hezten/CoreBundle/Model/TeacherInterface)
            $this->createdBy = $participant;
        else if($createdBy instanceof Hezten/CoreBundle/Model/ParentsInterface)
            $this->createdByParent = $participant;
    }
    
    /**
     * Gets the ThreadMetadata for a participant.
     *
     * @param ParticipantInterface $participant
     * @return ThreadMetadata
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