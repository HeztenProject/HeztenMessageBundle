<?php

namespace Hezten\MessageBundle\EntityManager;

use FOS\MessageBundle\EntityManager\ThreadManager as BaseThreadManager;
use Doctrine\ORM\EntityManager;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Query\Builder;

/**
 * Default ORM ThreadManager.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ThreadManager extends BaseThreadManager
{
    /**
     * Finds not deleted threads for a participant,
     * containing at least one message not written by this participant,
     * ordered by last message not written by this participant in reverse order.
     * In one word: an inbox.
     *
     * @param ParticipantInterface $participant
     * @return Builder a query builder suitable for pagination
     */
    public function getParticipantInboxThreadsQueryBuilder(ParticipantInterface $participant)
    {
        if($participant instanceof Hezten/CoreBundle/Model/TeacherInterface)
                 $where = 'p.id = :user_id';
        else if($participant instanceof Hezten/CoreBundle/Model/ParentsInterface)
             $where = 'pp.id = :user_id';
        else
            throw \Exception("Unkown participant class");

        return $this->repository->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')
            ->innerJoin('tm.participantParent', 'pp')
            
            // the participant is in the thread participants
            ->andWhere($where)
            

            ->setParameter('user_id', $participant->getId())

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            // there is at least one message written by an other participant
            ->andWhere('tm.lastMessageDate IS NOT NULL')

            // sort by date of last message written by an other participant
            ->orderBy('tm.lastMessageDate', 'DESC')
        ;
    }

    /**
     * Finds not deleted threads from a participant,
     * containing at least one message written by this participant,
     * ordered by last message written by this participant in reverse order.
     * In one word: an sentbox.
     *
     * @param ParticipantInterface $participant
     * @return Builder a query builder suitable for pagination
     */
    public function getParticipantSentThreadsQueryBuilder(ParticipantInterface $participant)
    {
        if($participant instanceof Hezten/CoreBundle/Model/TeacherInterface)
                 $where = 'p.id = :user_id';
        else if($participant instanceof Hezten/CoreBundle/Model/ParentsInterface)
             $where = 'pp.id = :user_id';
        else
            throw \Exception("Unkown participant class");

        return $this->repository->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')
            ->innerJoin('tm.participantParent', 'pp')
            
            // the participant is in the thread participants
            
           ->andWhere($where)

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            // there is at least one message written by this participant
            ->andWhere('tm.lastParticipantMessageDate IS NOT NULL')

            // sort by date of last message written by this participant
            ->orderBy('tm.lastParticipantMessageDate', 'DESC')
        ;
    }


    /**
     * Gets threads created by a participant
     *
     * @param ParticipantInterface $participant
     * @return array of ThreadInterface
     */
    public function findThreadsCreatedBy(ParticipantInterface $participant)
    {   
        if($participant instanceof Hezten/CoreBundle/Model/TeacherInterface)
            $where = 'p.id = :user_id';
        else if($participant instanceof Hezten/CoreBundle/Model/ParentsInterface)
            $where = 'pp.id = :user_id';
        else
            throw \Exception("Unkown participant class");

        return $this->repository->createQueryBuilder('t')

            ->innerJoin('t.createdBy', 'p')
            ->innerJoin('t.createdByParent', 'pp')
            
            // the participant is in the thread participants
            ->andWhere($where)

            ->setParameter('participant_id', $participant->getId())

            ->getQuery()
            ->execute();
    }

    /**
     * Update the dates of last message written by other participants
     */
    protected function doDatesOfLastMessageWrittenByOtherParticipant(ThreadInterface $thread)
    {
        foreach ($thread->getAllMetadata() as $meta) {
            $participantId = $meta->getParticipant()->getId();
            $timestamp = 0;

            foreach ($thread->getMessages() as $message) {
                if (get_class($meta->getParticipant()) == get_class($participant)
                    && $participantId != $message->getSender()->getId()) {
                    $timestamp = max($timestamp, $message->getTimestamp());
                }
            }
            if ($timestamp) {
                $date = new \DateTime();
                $date->setTimestamp($timestamp);
                $meta->setLastMessageDate($date);
            }
        }
    }
}