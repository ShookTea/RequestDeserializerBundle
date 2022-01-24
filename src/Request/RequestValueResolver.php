<?php

namespace ShookTea\RequestDeserializerBundle\Request;

use ShookTea\RequestDeserializerBundle\Exception\RequestDeserializationException;
use ShookTea\RequestDeserializerBundle\Exception\RequestValidationException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {}

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $argumentType = $argument->getType();

        return null !== $argumentType && is_subclass_of($argumentType, RequestInterface::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var class-string $argumentType */
        $argumentType = $argument->getType();
        /** @var string $content */
        $content = $request->getContent(false);

        try {
            $result = $this->deserialize($argumentType, $content);
        } catch (Exception $exception) {
            throw new RequestDeserializationException($exception);
        }

        $validationResults = $this->validator->validate($result);

        if ($validationResults->count() > 0) {
            $violations = [];
            for ($i = 0; $i < $validationResults->count(); $i++) {
                $violations[] = $validationResults->get($i);
            }
            throw new RequestValidationException($violations);
        }

        yield $result;
    }


    /**
     * @param class-string $argumentType
     */
    private function deserialize(string $argumentType, string $content): RequestInterface
    {
        return $this->serializer->deserialize($content, $argumentType, 'json', [
            AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
        ]);
    }
}
