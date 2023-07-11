<?php

use Swoole\Http\Request;
use Swoole\Http\Response;

class Router
{
  private $request;
  private $response;
  private $routes;

  public function __construct(Request $request, Response $response)
  {
    $this->request = $request;
    $this->response = $response;
    $this->routes = [];
  }

  public function get($path, $callback)
  {
    $this->addRoute('GET', $path, $callback);
  }

  public function post($path, $callback)
  {
    $this->addRoute('POST', $path, $callback);
  }

  public function delete($path, $callback)
  {
    $this->addRoute('DELETE', $path, $callback);
  }

  public function put($path, $callback)
  {
    $this->addRoute('PUT', $path, $callback);
  }

  public function notFound($callback)
  {
    $this->addRoute('NOT_FOUND', '', $callback);
  }

  private function addRoute($method, $path, $callback)
  {
    $this->routes[] = ['method' => $method, 'path' => $path, 'callback' => $callback];
  }

  public function execute()
  {
    $path = $this->request->server['request_uri'];
    $method = $this->request->server['request_method'];

    foreach ($this->routes as $route) {
      if ($route['method'] === $method) {
        $pattern = $this->buildRoutePattern($route['path']);
        if (preg_match($pattern, $path, $matches)) {
          if ($route['callback'] instanceof Closure) {
            $params = $this->getRouteParams($route['path'], $matches);
            $route['callback']($params);
          } else {
            $this->response->status(500);
            $this->response->end("Erro no servidor: Função de callback inválida");
          }
          return;
        }
      }
    }

    $notFoundRoutes = array_filter($this->routes, function ($route) {
      return $route['method'] === 'NOT_FOUND';
    });

    if (!empty($notFoundRoutes)) {
      $notFoundRoute = array_shift($notFoundRoutes);
      $notFoundRoute['callback']();
    } else {
      $this->response->status(404);
      $this->response->end();
    }
  }

  private function buildRoutePattern($routePath)
  {
    $pattern = preg_replace('/\//', '\\/', $routePath);
    $pattern = preg_replace('/:(\w+)/', '(?P<$1>[^\\/]+)', $pattern);
    $pattern = '/^' . $pattern . '$/';
    return $pattern;
  }

  private function getRouteParams($routePath, $matches)
  {
    $routeParams = [];
    $routeParts = explode('/', trim($routePath, '/'));

    foreach ($routeParts as $index => $part) {
      if (strpos($part, ':') === 0) {
        $name = substr($part, 1);
        $value = $matches[$name] ?? null;
        $routeParams[$name] = $value;
      }
    }

    return $routeParams;
  }
}
