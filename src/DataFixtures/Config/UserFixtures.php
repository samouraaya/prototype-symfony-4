<?php

/**
 * Description of AppFixtures
 *
 * @author Lamine Mansouri
 * @date 19/06/2020
 */

namespace App\DataFixtures\Config;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Config\User;
use App\Entity\Config\Role;
use App\Repository\Config\RoleRepository;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //create Role User
        $role = new Role();
        $role->setRole("ROLE_USER");
        $role->setNameAr("دور المستخدم");
        $role->setNameFr(" Role utilisateur");
        $manager->persist($role);


        //create Role Admin
        $role = new Role();
        $role->setRole("ROLE_ADMIN");
        $role->setNameAr("المشرف العام على الموقع");
        $role->setNameFr("Administrateur du site");
        $manager->persist($role);
        $manager->flush();

        //create User Admin
        $user1 = new User();
        $user1->setUsername("admin");
        $user1->setEmail("admin@gmail.com");
        $user1->setPassword($this->encoder->encodePassword($user1, "admin"));
        $role = $manager->getRepository(Role::class)->findOneByRole("ROLE_ADMIN");
        $user1->addRole($role);
        $manager->persist($user1);

        $manager->flush();
    }

}
