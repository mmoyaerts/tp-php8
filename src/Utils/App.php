<?php
// src/App/App.php

namespace App;

use App\Utils\Filesystem;
use App\Routing\Router;
use Psr\Container\ContainerInterface;
use App\Routing\Attribute\Route;


class App
{
    private Router $router;
    private ContainerInterface $container;

    public function __construct(Router $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function handle(string $uri, string $httpMethod): string
    {
        try {
            $this->registerRoutes();
            return $this->router->execute($uri, $httpMethod);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    private function registerRoutes(): void
    {
        // Explorer le répertoire des contrôleurs
        $controllersFqcn = Filesystem::getFqcns(self::CONTROLLERS_BASE_DIR, self::CONTROLLERS_NAMESPACE_PREFIX);

        foreach ($controllersFqcn as $fqcn) {
            $classInfos = new \ReflectionClass($fqcn);

            if ($classInfos->isAbstract()) {
                continue;
            }

            $methods = $classInfos->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if ($method->isConstructor()) {
                    continue;
                }

                $attributes = $method->getAttributes(RouteAttribute::class);

                if (!empty($attributes)) {
                    $routeAttribute = $attributes[0];
                    $route = $routeAttribute->newInstance();
                    $this->router->addRoute(new Route(
                        $route->getUri(),
                        $route->getName(),
                        $route->getHttpMethod(),
                        $fqcn,
                        $method->getName()
                    ));
                }
            }
        }
    }

    private function handleException(\Exception $e): string
    {
        return 'Erreur interne, veuillez contacter l\'administrateur';
    }
}

