<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Model\ApiMessage;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $message = array(
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'clientIP' => $event->getRequest()->getClientIp(),
            'URI' => $event->getRequest()->getUri(),
        );
        $message = json_encode($message);
        if ($exception instanceof HttpExceptionInterface)
            $response = (new ApiMessage(null, $exception->getStatusCode(), $exception->getHeaders()))->setMessage(null, $message, false);
        else
            $response = (new ApiMessage())->setStatusCode(ApiMessage::HTTP_INTERNAL_SERVER_ERROR)->setMessage(null, $message, false);

        $event->setResponse($response);
    }
}
