<?php

namespace ShookTea\RequestDeserializerBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;

class RequestValidationException extends BadRequestHttpException
{
    /**
     * @param ConstraintViolationInterface[] $violations
     */
    public function __construct(
        private array $violations,
    ) {
        $messageParts = array_map(
            fn (ConstraintViolationInterface $violation) => $violation->getMessage(),
            $this->violations,
        );
        $message = implode('; ', $messageParts);
        parent::__construct($message);
    }

    /** @return ConstraintViolationInterface[] */
    public function getViolations(): array
    {
        return $this->violations;
    }
}

