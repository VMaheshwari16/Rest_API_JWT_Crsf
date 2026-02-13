<?php

class PatientController {

    public static function index() {
        AuthMiddleware::handle();

        $data = Patient::all();

        if (!$data || count($data) === 0) {
            Response::json("Patient not found", 404);
        }

        Response::json($data);
    }

    public static function store($req) {
        AuthMiddleware::handle();

        if ( empty($req['name']) ||
            empty($req['age']) ||
            empty($req['gender']) ||
            empty($req['phone']) ||
            empty($req['address'])
        ) {
            Response::json("All fields are required", 422);
        }

        if (!preg_match('/^[0-9]{10}$/', $req['phone'])) {
            Response::json("Invalid phone number", 422);
        }

        Patient::create([
            $req['name'],
            $req['age'],
            $req['gender'],
            $req['phone'],
            $req['address']
        ]);

        Response::json("Patient added successfully", 201);
    }

    public static function update($id, $req) {
        AuthMiddleware::handle();

        if (!Patient::findById($id)) {
            Response::json("Invalid patient id", 404);
        }

        if (
            empty($req['name']) ||
            empty($req['age']) ||
            empty($req['gender']) ||
            empty($req['phone']) ||
            empty($req['address'])
        ) {
            Response::json("All fields are required", 422);
        }
                if (!preg_match('/^[0-9]{10}$/', $req['phone'])) {
            Response::json("Invalid phone number", 422);
        }

        Patient::update($id, $req);
        Response::json("Patient updated successfully");
    }

    public static function destroy($id) {
        AuthMiddleware::handle();

        if (!Patient::findById($id)) {
            Response::json("Invalid patient id", 404);
        }

        Patient::delete($id);
        Response::json("Patient deleted successfully");
    }
}
