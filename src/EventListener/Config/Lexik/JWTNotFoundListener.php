<?php

/**
 * Description of JWTNotFoundListener
 *
 * @author Lamine Mansouri
 * @date 22/06/2020
 */

namespace App\EventListener\Config\Lexik;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JWTNotFoundListener {

    /**
     * @param JWTNotFoundEvent $event
     */
    public function onJWTNotFound(JWTNotFoundEvent $event) {
        $data = [
            'status'  => '403 Forbidden',
            'message' => 'Missing token',
        ];

        $response = new JsonResponse($data, 403);

        $event->setResponse($response);
    }

}
