<?php

/**
 * Description of HashPasswordListener
 *
 * @author Lamine Mansouri
 * @date 24/06/2020
 */

namespace App\EventListener\Config\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Config\User;

class HashPasswordListener implements EventSubscriber {

    private $passwordEncoder;

    /**
     * HashPasswordListener constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @ORM\PrePersist
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        if ($entity->getPlainPassword()) {
            $this->encodePassword($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        if ($entity->getPlainPassword()) {
            $this->encodePassword($entity);
        }
        $em   = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function getSubscribedEvents() {
        return array(
            'prePersist',
            'preUpdate',
        );
    }

    /**
     * @param User $entity
     */
    private function encodePassword(User $entity) {
        $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($encoded);
    }

}
