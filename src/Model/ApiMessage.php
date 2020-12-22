<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * API message
 */
class ApiMessage extends JsonResponse
{
    public const DATA_LIMIT = 10;

    /**
     * The response message
     * @param mixed $data    The response data
     * @param string $message The response message
     * @param bool $success The response is successful
     * @return this
     */
    public function setMessage($data = null, string $message = 'OK', bool $success = true): self
    {
        $this->setData(['data' => $data, 'message' => $message, 'success' => $success]);

        return $this->update();
    }
}
