<?php

declare(strict_types = 1);

// use App\Auth;
// use App\Flash;

abstract class Controller
{
    public function __construct(array $route_params)
    {
        $this->route_params = $route_params;
    }

    public function __call(string $name, array $args) : void
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    protected function before() : void
    {
    }

    protected function redirect(string $url) : void
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }

    /*
    public function requireLogin() : void
    {
        if(!Auth::getUser()){

            Flash::addMessage('Please login to access that page' . FLASH::INFO);

            Auth::rememberRequestedPage();

            $this->redirect('/login');

        }
    }
    */
}