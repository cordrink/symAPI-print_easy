<?php

namespace App\services;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

class NotificationUserFilter extends AbstractFilter
{
    private Security $security;

    public function __construct(ManagerRegistry $managerRegistry, Security $security, $requestStack = null, ?array $properties = null) {
        parent::__construct($managerRegistry, $requestStack, $properties);
        $this->security = $security;
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        if ($property === 'user') {
            return;
        }

        $user = $this->security->getUser();
        if ($user && !$this->security->isGranted('ROLE_ADMIN')) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
            $queryBuilder->setParameter('current_user', $user);
        }
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'user' => [
                'property' => 'user',
                'type' => 'string',
                'required' => false,
                'description' => 'Filter notifications by user',
            ]
        ];
    }
}