<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Customer;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CustomerProcessor implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof Customer) {
            $user = $this->security->getUser(); // App\Entity\Client
            if ($user) {
                $data->setClient($user);
                $data->setCreatedAt($data->getCreatedAt() ?? new \DateTimeImmutable());
            }
        }

        // Delegate to Doctrine’s default persist processor
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
