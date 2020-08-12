<?php

/**
 * Description of PublishController
 *
 * @author Lamine Mansouri
 * @date 24/07/2020
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class PublishController {

    public function __invoke(PublisherInterface $publisher): Response {
        dd("dddddddddddddddddddd");
        $update = new Update(
                'http://example.com/books/1',
                json_encode(['status' => 'OutOfStock'])
        );

        // The Publisher service is an invokable object
        $publisher($update);

        return new Response('published!');
    }

}
