<?php

/**
 * Description of ExceptionListener
 *
 * @author Lamine Mansouri
 * @date 23/06/2020
 */

namespace App\EventListener\Config\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener {

    public function onKernelException(ExceptionEvent $event) {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $message   = sprintf(
                'My Error says: %s with code: %s',
                $exception->getMessage(),
                $exception->getCode()
        );
        // Customize your response object to display the exception details
        $response  = new Response();
        $response->setContent($message);
        if (!isset($_SERVER['APP_ENV'])) {
            throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
        }
        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            //get APP_ENV (dev/prod)
            if ($_SERVER["APP_ENV"] == "prod") {
                $statusText = Response::$statusTexts[$exception->getStatusCode()];
            } else {
                $statusText = $message;
            }
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            //get APP_ENV (dev/prod)
            if ($_SERVER["APP_ENV"] == "prod") {
                $statusText = Response::$statusTexts[$exception->getStatusCode()];
            } else {
                $statusText = $message;
            }
        }
        $jsonResponse = new JsonResponse(['version' => $response->getProtocolVersion(), 'statusCode' => $response->getStatusCode(), 'statusText' => $statusText]);
        // sends the modified response object to the event
        $event->setResponse($jsonResponse);
    }

}
