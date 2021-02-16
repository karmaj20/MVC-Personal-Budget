<?php

declare(strict_types = 1);

namespace Core;

use App\Auth;
use App\Flash;

class View
{
    public static function render(string $view, array $args) : void
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view"; // relative to core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    public static function renderTemplate(string $template, $args = []) : void
    {
        echo static::getTemplate($template, $args);
    }

    public static function getTemplate(string $template, array $args) : string
    {
        static $twig = null;

        if ($twig == null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('current_user', Auth::getUser());
            $twig->addGlobal('flash_messages', Flash::getMessages());
        }

        return $twig->render($template, $args);
    }
}