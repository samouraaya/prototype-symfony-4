<?php

/**
 * Description of ReferencielDataPersister
 *
 * @author Wiem Hadiji
 * @date 22/07/2020
 */

namespace App\DataPersister\Referenciel;

use App\Entity\Referenciel\Referenciel;
use App\Entity\Config\Role;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\String\Slugger\SluggerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use http\Env\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class ReferencielDataPersister implements ContextAwareDataPersisterInterface {

    /**
     * @var EntityManagerInterface
     */
    private $_entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->_entityManager = $entityManager;

    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool {
        return $data instanceof Referenciel;
    }

    /**
     * @param Referenciel $data
     */
    public function persist($data, array $context = []) {
        //return all categories
        $categories=(($data->getReferencielCategories()->toArray()));
        $array = (array) $data;
        //test value of categorie
        if(in_array($array['categories'],$categories) )
        {
            $data->setCategorie($array['categories']);
        }
        else
        {
            return new JsonResponse(['status' => 'error', 'code' => Response::HTTP_BAD_REQUEST, 'data' =>'error' , 'message' =>'Réferenciel not exist' ], Response::HTTP_UNAUTHORIZED);
        }

          $this->_entityManager->persist($data);
          $this->_entityManager->flush();

    }

    public function remove($data, array $context = []) {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }

}
