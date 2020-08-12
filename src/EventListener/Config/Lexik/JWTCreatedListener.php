<?php

/**
 * Description of JWTCreatedListener
 *
 * @author Lamine Mansouri
 * @date 22/06/2020
 */

namespace App\EventListener\Config\Lexik;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener {

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event) {
        $expiration = new \DateTime('+1 day');
        $expiration->setTime(2, 0, 0);

        $payload               = $event->getData();
        $payload['data'] = array(
            "expiration" => $expiration->getTimestamp()
        );
        $event->setData($payload);
    }

}
