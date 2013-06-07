<?php

namespace Hezten\MessageBundle\EntityManager;

use FOS\MessageBundle\EntityManager\MessageManager as BaseMessageManager;

use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Query\Builder;

/**
 * Default ORM MessageManager.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class MessageManager extends BaseMessageManager
{
    /**
     * Tells how many unread messages this participant has
     *
     * @param ParticipantInterface $participant
     * @return int the number of unread messages
     */
    public function getNbUnreadMessageByParticipant(ParticipantInterface $participant)
    {
        if($participant instanceof Hezten/CoreBundle/Model/TeacherInterface)
        {
            $participantWhere = 'p.id = :participant_id';
            $senderParent = 'm.sender != :sender';
        }
        else if($participant instanceof Hezten/CoreBundle/Model/ParentsInterface)
        {
            $participantWhere = 'pp.id = :participant_id';
            $senderParent = 'm.senderParent != :sender';
        }
        else
            throw \Exception("Unkown participant class");

        $builder = $this->repository->createQueryBuilder('m');

        return (int)$builder
            ->select($builder->expr()->count('mm.id'))

            ->innerJoin('m.metadata', 'mm')
            ->innerJoin('mm.participant', 'p')
            ->innerJoin('mm.participantParent', 'pp')

                       
            ->setParameter('participant_id', $participant->getId())

            ->where($senderParent)
            ->setParameter('sender', $participant->getId())

            ->andWhere('mm.isRead = :isRead')
            ->setParameter('isRead', false, \PDO::PARAM_BOOL)

            ->getQuery()
            ->getSingleScalarResult();
    }
}