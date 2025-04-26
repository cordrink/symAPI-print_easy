<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;

class JwtDecorator implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $openApiFactory){}

    /**
     * @inheritDoc
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->openApiFactory)($context);
        $schemas = $openApi->getComponents()->getschemas();

        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readonly' => true,
                ]
            ]
        ]);

        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'user@example.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'password',
                ]
            ]
        ]);

        $schemas['Registration'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'user@example.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'password',
                ],
                'firstName' => [
                    'type' => 'string',
                    'example' => 'John',
                ],
                'lastName' => [
                    'type' => 'string',
                    'example' => 'Doe',
                ]
            ]
        ]);

        $schemas = $openApi->getComponents()->getSecuritySchemes() ?: [];
        $schemas['Jwt'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT'
        ]);

        // Endpoint pour l'inscription
        $registerPathItem = new Model\PathItem(
            ref: 'Register',
            post: new Model\Operation(
                operationId: 'postRegisterItem',
                tags: ['Register'],
                responses: [
                    '201' => [
                        'description' => 'User registered',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Register a new user.',
                requestBody: new Model\RequestBody(
                    description: 'User registration data',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Register',
                            ],
                        ],
                    ])
                ),
                security: [],
            )
        );

        // Endpoint pour l'authentification
        $loginPathItem = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Login'],
                responses: [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get JWT token to login.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ])
                ),
                security: [],
            )
        );

        $openApi->getPaths()->addPath('/api/register', $registerPathItem);
        $openApi->getPaths()->addPath('/api/login', $loginPathItem);

        return $openApi;
    }
}