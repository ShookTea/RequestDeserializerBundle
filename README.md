# RequestDeserializerBundle

Tested on PHP 8.1 with Symfony 6.0.

## Installation

Install with Composer:

```
composer require shooktea/request-deserializer-bundle
```

And add bundle to `bundles.php` (if was not added automatically by Symfony Flex):

```php
<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    // ...
    ShookTea\RequestDeserializerBundle\ShookTeaRequestDeserializerBundle::class => ['all' => true],
];
```

## How to use it?

- Create a class implementing `ShookTea\RequestDeserializerBundle\Query\QueryInterface` (for GET parameters) or
  `ShookTea\RequestDeserializerBundle\Request\RequestInterface` (for POST parameters)
- add annotations from `symfony/serializer` and `symfony/validator` components if necessary
- inject your class to controller method

And that's it!

**Example query class:** (for GET parameters)

```php
<?php

use ShookTea\RequestDeserializerBundle\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class ExampleQuery implements QueryInterface
{
    #[SerializedName('id')]
    #[Assert\Uuid]
    public string $uuid;
}
```

**Usage in controller:**

```php
class ExampleController extends AbstractController
{
    #[Route('/some/route')]
    public function someAction(ExampleQuery $query)
    {
        // do something with query
    }
}
```

After requesting `/some/route?id=217c26de-132e-49f7-a17d-fd80a70e3fe3`, in `someAction()` method of controller,
object `$query` will have a `$uuid` with value "217c26de-132e-49f7-a17d-fd80a70e3fe3".
