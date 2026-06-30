<?php

namespace App\Libraries;

class Router
{
    private static array $routes = [];
    private static array $middlewares = [];
    private static string $currentGroupPrefix = '';

    public static function get(string $path, array|callable $callback)
    {
        self::addRoute('GET', $path, $callback);
    }

    public static function post(string $path, array|callable $callback)
    {
        self::addRoute('POST', $path, $callback);
    }

    public static function put(string $path, array|callable $callback)
    {
        self::addRoute('PUT', $path, $callback);
    }

    public static function delete(string $path, array|callable $callback)
    {
        self::addRoute('DELETE', $path, $callback);
    }

    private static function addRoute(string $method, string $path, array|callable $callback)
    {
        $path = self::$currentGroupPrefix . $path;
        $path = rtrim($path, '/') ?: '/';
        
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middlewares' => self::$middlewares
        ];
    }

    public static function middleware(array $middlewares, callable $callback)
    {
        $previousMiddlewares = self::$middlewares;
        self::$middlewares = array_merge(self::$middlewares, $middlewares);
        
        call_user_func($callback);
        
        self::$middlewares = $previousMiddlewares;
    }

    public static function group(string $prefix, callable $callback)
    {
        $previousPrefix = self::$currentGroupPrefix;
        self::$currentGroupPrefix .= $prefix;
        
        call_user_func($callback);
        
        self::$currentGroupPrefix = $previousPrefix;
    }

    public function dispatch(Request $request)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) continue;

            // Convert route with params like {id} to regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route['path']);
            $pattern = "@^" . $pattern . "$@D";

            if (preg_match($pattern, $uri, $matches)) {
                // Execute Middlewares
                if (!empty($route['middlewares'])) {
                    foreach ($route['middlewares'] as $middleware) {
                        if (method_exists($middleware, 'handle')) {
                            $middleware::handle();
                        }
                    }
                }
                
                // Extract params
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                if (is_array($route['callback'])) {
                    $controllerName = $route['callback'][0];
                    $methodName = $route['callback'][1];
                    $controller = new $controllerName();
                    
                    // Prepend Request to params and strip string keys to avoid PHP 8 named argument errors
                    $methodParams = array_values($params);
                    array_unshift($methodParams, $request);
                    return call_user_func_array([$controller, $methodName], $methodParams);
                } else {
                    return call_user_func_array($route['callback'], array_values($params));
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
