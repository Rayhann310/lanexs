<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Libraries\Request;

abstract class BaseController
{
    /**
     * Render a view file with data.
     *
     * @param string $view e.g. 'dashboard/index'
     * @param array $data
     * @return void
     */
    protected function view(string $view, array $data = [])
    {
        View::render($view, $data);
    }

    /**
     * Send JSON response.
     *
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    protected function json(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
