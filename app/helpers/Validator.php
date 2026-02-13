<?php
class Validator {


    public static function email($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json("Invalid email format", 422);
        }
    }

    public static function password($password) {
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
            Response::json(
                "Password must be at least 6 characters and contain letters and numbers",
                422
            );
        }
    }

    public static function allRequired(array $data, array $fields) {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                Response::json(false, "All fields are required", null, 422);
            }
        }
    }

    public static function numeric($value, $field) {
        if (!is_numeric($value)) {
            Response::json(false, "$field must be numeric", null, 422);
        }
    }

    public static function phone($value) {
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            Response::json(
                false,
                "Invalid phone number. It must be 10 digits",
                null,
                422
            );
        }
    }

    public static function validId($record) {
        if (!$record) {
            Response::json(false, "Invalid patient id", null, 404);
        }
    }
}
