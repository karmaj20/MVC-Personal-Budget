<?php

declare(strict_types = 1);

namespace Core;

class Router
{
    protected array $routes;
    protected array $params;

    public function add(string $route, $params = []) : void
    {
        // convert the route to a regular expression: escape forward slashes
        $route = preg_match('/\//', '\\/', $route);

        // convert variables e.g. {controller}
        $route = preg_replace('/\{[a-z]+)\}', '(?P<\1>[a-z-]+)', $route);

        // convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2', $route);

        // Add start and end delmiters and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    public function getRoutes() : array
    {
        return $this->routes;
    }

    public function match($url) : bool
    {
        foreach ($this->routes as $route => $params){
            if (preg_match($route, $url, $matches)){
                // Get named capture group values
                foreach ($matches as $key =>$match){
                    if (is_string($key)){
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function getParams() : array
    {
        return $this->params;
    }


    /*
     * Dispatch - creating the controller object and running the action method
     */
    public function dispatch($url) : void
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {

                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (is_callable([$controller_object], $action)) {
                    $controller_object->action();
                } else {
                    throw new \Exception("Method $action (in controller $controller) not found");
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception("No route matched.", 404);
        }

    }

    protected function convertToStudlyCaps(string $string) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase(string $string) : string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables(string $url) : string
    {
        if($url != ''){

            $parts = explode('&', $url, 2);

            if(strpos($parts[0], '=') === false){
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    protected function getNamespace() : string
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}