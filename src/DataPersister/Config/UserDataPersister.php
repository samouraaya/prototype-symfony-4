<?php

/**
 * Description of UserDataPersister
 *
 * @author Lamine Mansouri
 * @date 17/06/2020
 */

namespace App\DataPersister\Config;

use App\Entity\Config\User;
use App\Entity\Config\Role;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\String\Slugger\SluggerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class UserDataPersister implements ContextAwareDataPersisterInterface {

    /**
     * @var EntityManagerInterface
     */
    private $_entityManager;

    /**
     * @var Container
     */
    private $_encoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder) {
        $this->_entityManager = $entityManager;
        $this->_encoder       = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = []) {

        if ($data->getPassword()) {
            $data->setPassword($this->_encoder->encodePassword($data, $data->getPassword()));
            $data->eraseCredentials();
        }
        $role = $this->_entityManager->getRepository(Role::class)->findOneByRole("ROLE_USER");
        if ($role) {
            $data->addRole($role);
        } else {
            $role = new Role();
            $role->setRole("ROLE_USER");
            $role->setNameAr("دور المستخدم");
            $role->setNameFr(" Role utilisateur");
            $this->_entityManager->persist($role);
            $this->_entityManager->flush();
            $data->addRole($role);
        }

        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    public function remove($data, array $context = []) {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }

}
