<?php

namespace Hezten\MessageBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Hezten\MessageBundle\Model\ThreadMetadata as BaseThreadMetadata;

use FOS\MessageBundle\Model\ThreadInterface;



abstract class ThreadMetadata extends BaseThreadMetadata
{
   public function setThread(ThreadInterface $thread) 
   {
        $this->thread = $thread;
   } 
}