<?php

namespace Hezten\MessageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\Thread as BaseThread;
use FOS\MessageBundle\Model\ParticipantInterface;

use FOS\MessageBundle\Model\ThreadMetadata as ModelThreadMetadata;

abstract class Thread extends BaseThread
{
    /**
     * Gets the users participating in this conversation
     *
     * @return array of ParticipantInterface
     */
    public function getParticipants()
    {
        return $this->getParticipantsCollection()->toArray();
    }

    /**
     * Gets the users participating in this conversation
     *
     * Since the ORM schema does not map the participants collection field, it
     * must be created on demand.
     *
     * @return ArrayCollection
     */
    protected function getParticipantsCollection()
    {
        if ($this->participants == null) {
            $this->participants = new ArrayCollection();

            foreach ($this->metadata as $data) {
                $this->participants->add($data->getParticipant());
            }
        }

        return $this->participants;
    }

    /**
     * Adds a participant to the thread
     * If it already exists, nothing is done.
     *
     * @param ParticipantInterface $participant
     * @return null
     */
    public function addParticipant(ParticipantInterface $participant)
    {
        if (!$this->isParticipant($participant)) {
            $this->getParticipantsCollection()->add($participant);
        }
    }

    /**
     * Adds many participants to the thread
     *
     * @param array|\Traversable
     *
     * @throws \InvalidArgumentException
     * @return Thread
     */
    public function addParticipants($participants)
    {
        if (!is_array($participants) && !$participants instanceof \Traversable) {
            throw new \InvalidArgumentException("Participants must be an array or instanceof Traversable");
        }

        foreach ($participants as $participant) {
            $this->addParticipant($participant);
        }

        return $this;
    }

    /**
     * Tells if the user participates to the conversation
     *
     * @param ParticipantInterface $participant
     * @return boolean
     */
    public function isParticipant(ParticipantInterface $participant)
    {
        return $this->getParticipantsCollection()->contains($participant);
    }

    /**
     * Get the collection of ThreadMetadata.
     *
     * @return Collection
     */
    public function getAllMetadata()
    {
        return $this->metadata;
    }
}