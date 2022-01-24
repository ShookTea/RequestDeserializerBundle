<?php

namespace ShookTea\RequestDeserializerBundle\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestDeserializationException extends BadRequestHttpException
{
    public function __construct(Exception $exception)
    {
        parent::__construct($exception->getMessage(), $exception);
    }
}
