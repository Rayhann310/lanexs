<?php

namespace App\Helpers;

class View
{
    private static array $sections = [];
    private static string $layout = '';
    private static string $currentSection = '';

    public static function render(string $view, array $data = [])
    {
        $viewFile = BASE_PATH . '/app/Views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Extract data so variables are available in view
            extract($data);
            
            // Start buffering the view content
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            // If layout was set inside the view, require the layout
            if (self::$layout !== '') {
                $layoutFile = BASE_PATH . '/app/Views/layouts/' . self::$layout . '.php';
                if (file_exists($layoutFile)) {
                    require $layoutFile;
                } else {
                    echo "Layout file not found: " . self::$layout;
                }
            } else {
                echo $content;
            }
        } else {
            echo "View file not found: $view";
        }
    }

    public static function extends(string $layout)
    {
        self::$layout = $layout;
    }

    public static function section(string $name)
    {
        self::$currentSection = $name;
        ob_start();
    }

    public static function endSection()
    {
        if (self::$currentSection !== '') {
            self::$sections[self::$currentSection] = ob_get_clean();
            self::$currentSection = '';
        }
    }

    public static function renderSection(string $name)
    {
        echo self::$sections[$name] ?? '';
    }
}
