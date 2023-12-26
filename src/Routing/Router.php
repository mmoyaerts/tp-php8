<?php

namespace App\Routing;

use App\Routing\Exception\RouteNotFoundException;
use Psr\Container\ContainerInterface;

class Router
{
    private array $routes = [];
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addRoute(Route $route): self
    {
        $this->routes[] = $route;
        return $this;
    }

    public function getRoute(string $uri, string $httpMethod): ?Route
    {
        foreach ($this->routes as $savedRoute) {
            if ($savedRoute->getUri() === $uri && $savedRoute->getHttpMethod() === $httpMethod) {
                return $savedRoute;
            }
        }

        return null;
    }

    public function execute(string $uri, string $httpMethod): string
    {
        $route = $this->getRoute($uri, $httpMethod);

        if ($route === null) {
            throw new RouteNotFoundException();
        }

        $controllerClass = $route->getControllerClass();
        $constructorParams = $this->getMethodParams($controllerClass . '::__construct');
        $controllerInstance = new $controllerClass(...$constructorParams);

        $method = $route->getController();
        $controllerParams = $this->getMethodParams($controllerClass . '::' . $method);
        return $controllerInstance->$method(...$controllerParams);
    }

    private function getMethodParams(string $method): array
    {
        $methodInfos = new \ReflectionMethod($method);
        $methodParameters = $methodInfos->getParameters();

        $params = [];
        foreach ($methodParameters as $param) {
            $paramType = $param->getType();
            $paramTypeFQCN = $paramType->getName();
            $params[] = $this->container->get($paramTypeFQCN);
        }

        return $params;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}

