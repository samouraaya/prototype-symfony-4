<?php

/**
 * Description of AccessPermission
 *
 * @author wiem Hadiji
 * @date 03/07/2020
 */

namespace App\EventListener\Config\Access;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccessPermissionListener
{

    private $tokenStorage;
    private $em;
    private $container;

    //private $container;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager, Container $container)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $entityManager;
        $this->container = $container;
    }


    public function onKernelRequest(GetResponseEvent $event)
    {// get request
//        $request = $event->getRequest();
//        $path = $request->getPathInfo();
//        $autorizedUrl =['/docs'];
//        $tabInterfaces = $this->interfaceUsersConnecte();
//        if (!in_array($path, $tabInterfaces)&& !in_array($path, $autorizedUrl)) {
//            $data = [
//                'status' => '401 unauthorized',
//                'message' => 'access denied',
//            ];
//
//            $response = new JsonResponse($data, 401);
//
//            $event->setResponse($response);
//        }

    }

    //return les interfaces du users connecté
    public function interfaceUsersConnecte()
    {
        $frontInterface = array();
        $frontI = array();
        if ($this->tokenStorage->getToken() != null && $this->tokenStorage->getToken()->getUser() != "anon.") {
            //récupérer le user connecté à partir du tocken
            $user = $this->tokenStorage->getToken()->getUser();
            //récupérer les roles du user connecté à partir du tocken
            $roles = $user->getRole();
            foreach ($roles as $role) {
                foreach ($role->getAccessInterfaces() as $interface) {
                    //stocker les frontInterfaces du user connecté
                    array_push($frontInterface, $interface->getBackInterface());
                }


            }

//            dd($frontInterface);
            //faire le merge des interface et éliminer les niveaux
            //  $allFrontToSee = array_merge([], ...$frontInterface);
            //eliminer les doublon du tableau
            $frontI = array_unique($frontInterface);
        }
        return $frontI;
    }
}
