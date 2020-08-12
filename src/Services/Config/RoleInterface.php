<?php

/**
 * Description of RoleInterface
 *
 * @author Lamine Mansouri
 * @date 25/06/2020
 */

namespace App\Services\Config;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use App\Entity\Config\AccessInterface;

class RoleInterfaces {

    private $router;
    private $em;
    private $tokenStorage;
    private $requestStack;

    public function __construct(//\Symfony\Bundle\FrameworkBundle\Routing\Router $router,
            RouterInterface $router,
            EntityManagerInterface $entityManager,
            TokenStorageInterface $tokenStorage,
            RequestStack $requestStack
    ) {
        $this->router       = $router;
        $this->em           = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    public function createRoleInterfaces() {
        $router = $this->router;
        $routes = $router->getRouteCollection();
        foreach ($routes as $route) {
            if (!empty($route->getMethods())) {
                $pos = strpos($route->getPath(), "/api");
                if ($pos !== false) {
                    //var_dump($route->getMethods());
                    $path          = str_replace(".{_format}", "", $route->getPath());
                    $accessInterface = New AccessInterface();
                    $accessInterface->setBackInterface($path);
                    $accessInterface->setMethod($route->getMethods());
                    $this->em->persist($accessInterface);
                    $this->em->flush();
                }
            }
        }
    }

}
