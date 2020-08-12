<?php

/**
 * Description of CustomController
 *
 * @author Lamine Mansouri
 * @date 13/07/2020
 */

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//Symfony\Component
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiPlatform\Core\Bridge\Symfony\Routing\IriConverter;
use ApiPlatform\Core\Api\IriConverterInterface;
//Entity
use App\Entity\Config\User;
use App\Entity\Config\Role;

/**
 * Custom controller.
 *
 * @Route("/custom")
 */
class CustomController extends AbstractController {

    /**
     * @Route(
     *     name="user_register",
     *     path="/post",
     *     methods={"POST"},
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="user_post"}
     * )
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $encoder) {
        $data       = json_decode($request->getContent(), true);
        $em         = $this->getDoctrine()->getManager();
        $serializer = new Serializer([new GetSetMethodNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
        $user       = $serializer->deserialize(json_encode($data), User::class, 'json');
        $user->setPassword($encoder->encodePassword(New User(), $data["password"]));
        $em->persist($user);
        if ($data["role"]) {
            foreach ($data["role"] as $key => $line) {
                $role = $em->getRepository(Role::class)->findOneByRole($line['role']);
                if ($role) {
                    $user->addRole($role);
                } else {
                    $role = $serializer->deserialize(json_encode($line), Role::class, 'json');
                    $user->addRole($role);
                }
                $em->persist($role);
                $em->persist($user);
            }
        }

        $em->flush();
        return new JsonResponse(['version' => 1.0, 'statusCode' => Response::HTTP_CREATED, 'statusText' => Response::$statusTexts[201]], Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     name="user_edit_patch",
     *     path="/patch/{id}",
     *     methods={"PATCH"},
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="user_patch"}
     * )
     */
    public function updateAction(Request $request, UserPasswordEncoderInterface $encoder, User $user) {
        $data       = json_decode($request->getContent(), true);
        $em         = $this->getDoctrine()->getManager();
        $serializer = new Serializer([new GetSetMethodNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
        $user       = $serializer->deserialize(json_encode($data), User::class, 'json', array('object_to_populate' => $user));
        $user->setPassword($encoder->encodePassword($user, $data["password"]));
        if (isset($data["role"])) {
            foreach ($data["role"] as $key => $line) {
                $iri  = explode("/", $line);
                $id   = end($iri);
                $role = $em->getRepository(Role::class)->find($id);
                if ($role) {
                    $user->addRole($role);
                }
                $em->persist($role);
            }
        }

        $em->flush();
        return new JsonResponse(['version' => 1.0, 'statusCode' => Response::HTTP_OK, 'statusText' => Response::$statusTexts[200]], Response::HTTP_OK);
    }

    /**
     * @Route(
     *     name="user_edit_put",
     *     path="/put/{id}",
     *     methods={"PUT"},
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="user_put"}
     * )
     */
    public function putAction(Request $request, UserPasswordEncoderInterface $encoder, User $user) {
        $data       = json_decode($request->getContent(), true);
        $em         = $this->getDoctrine()->getManager();
        $serializer = new Serializer([new GetSetMethodNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
        $user       = $serializer->deserialize(json_encode($data), User::class, 'json', array('object_to_populate' => $user));
        $user->setPassword($encoder->encodePassword($user, $data["password"]));
        if (isset($data["role"])) {
            foreach ($data["role"] as $key => $line) {
                $iri  = explode("/", $line);
                $id   = end($iri);
                $role = $em->getRepository(Role::class)->find($id);
                if ($role) {
                    $user->addRole($role);
                }
                $em->persist($role);
            }
        }
        $em->flush();
        return new JsonResponse(['version' => 1.0, 'statusCode' => Response::HTTP_OK, 'statusText' => Response::$statusTexts[200]], Response::HTTP_OK);
    }

    /**
     * @Route(
     *     name="user_all",
     *     path="/all",
     *     methods={"GET"},
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="user_get"}
     * )
     */
    public function getAction() {
        $em    = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();
        return $users;
    }

    /**
     * @Route(
     *     name="user_all_pagination",
     *     path="/all-pagination/{page}",
     *     methods={"GET"},
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="user_get_pagination"}
     * )
     */
    public function getPaginationAction($page = 1) {
        $nbPerPage  = 10;
        $em         = $this->getDoctrine()->getManager();
        $users      = $em->getRepository(User::class)->findAllPaginator($page, $nbPerPage);
        $pagination = array(
            'page'        => $page,
            'pages_count' => ceil(count($users) / $nbPerPage)
        );
        return array(
            'pagination' => $pagination,
            'users'      => $users
        );
    }

}
