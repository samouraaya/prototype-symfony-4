<?php

/**
 * Description of AuthenticationSuccessListener
 *
 * @author Lamine Mansouri
 * @date 22/06/2020
 */

namespace App\EventListener\Config\Lexik;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

class AuthenticationFailureListener {

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event) {
        $data = [
            'status'  => '401 Unauthorized',
            'message' => 'Bad credentials, please verify that your username/password are correctly set',
        ];

        $response = new JWTAuthenticationFailureResponse($data);

        $event->setResponse($response);
    }

}
