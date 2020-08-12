<?php

/**
 * Description of AuthController
 *
 * @author Lamine Mansouri
 * @date 24/06/2020
 */

namespace App\Controller\Config;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Config\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AuthController extends AbstractController {

    public function register(Request $request, UserPasswordEncoderInterface $encoder) {
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $user = new User();
        $user->setUsername($username);
        $user->setEmail("reader@gmail.com");
        $user->setPassword($encoder->encodePassword($user, $password));
        //$user->setRoles(($roles));
        $em->persist($user);
        $em->flush();

        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

}
