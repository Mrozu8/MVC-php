<?php

namespace Core;

class View
{
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);
        $file = "../App/Views/$view";

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not fund");
        }
    }

    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;


        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader, ['auto_reload' => true]);
        }

        $args['session'] = $_SESSION;

        echo $twig->render($template, $args);
    }
}
