<?php
class Router {
    public static function route($method, $uri, $req) {

        if ($uri === '/api/register' && $method === 'POST')
            AuthController::register($req);

        if ($uri === '/api/login' && $method === 'POST')
            AuthController::login($req);

        if ($uri === '/api/refresh' && $method === 'POST') {
            AuthController::refresh();
        }
        if ($uri === '/api/patients' && $method === 'GET')
            PatientController::index();

        if ($uri === '/api/patients' && $method === 'POST')
            PatientController::store($req);

        if (preg_match('#^/api/patients/(\d+)$#', $uri, $m) && $method === 'PUT')
            PatientController::update($m[1], $req);

        if (preg_match('#^/api/patients/(\d+)$#', $uri, $m) && $method === 'DELETE')
            PatientController::destroy($m[1]);

        Response::json(["error"=>"Route not found"], 404);
    }
}

