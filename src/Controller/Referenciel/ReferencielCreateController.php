<?php

/**
 * Description of ReferencielCreateController
 *
 * @author Wiem Hadiji
 */

namespace App\Controller\Referenciel;

use App\Entity\Referenciel\Referenciel;
use Mfpe\ConfigBundle\Exception\ApiProblem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Config\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ReferencielCreateController extends  AbstractController
{

    public function __invoke(Referenciel $data,Request $request)
    {
       $dataJson = json_decode($request->getContent(), true);
       $categorie=(($data->getReferencielCategories()->toArray()));
       if(in_array($dataJson['categorie'],$categorie) )
       {
           $data->setCategorie($dataJson['categorie']);
       }
       else
       {
           return new JsonResponse(['status' => 'error', 'code' => Response::HTTP_BAD_REQUEST, 'data' =>'error' , 'message' =>'Réferenciel not exist' ], Response::HTTP_UNAUTHORIZED);
       }
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return $data;
    }

}
