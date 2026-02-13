<?php

class AuthController {

    public static function register($req) {

        if (empty($req['name']) || empty($req['email']) || empty($req['password'])) {
            Response::json("All fields are required", 422);
        }

        Validator::email($req['email']);
        Validator::password($req['password']);

        if (User::findByEmail($req['email'])) {
            Response::json("Email already exists", 409);
        }

        User::create([
            $req['name'],
            $req['email'],
            password_hash($req['password'], PASSWORD_DEFAULT)
        ]);

        Response::json("User registered successfully", 201);
    }

public static function login($req) {

    $user = User::findByEmail($req['email']);

    if (!$user || !password_verify($req['password'], $user['password'])) {
        Response::json("Invalid credentials", 401);
    }

    $accessToken = JWT::generate([
        "id" => $user['id'],
        "email" => $user['email']
    ]);

    $refreshToken = bin2hex(random_bytes(40));

    $hashedToken = password_hash($refreshToken, PASSWORD_DEFAULT);

    User::deleteAllUserTokens($user['id']);

    User::saveRefreshToken(
        $user['id'],
        $hashedToken,
        date('Y-m-d H:i:s', time() + (7*24*60*60))
    );

    setcookie(
        "refresh_token",
        $refreshToken,
        time() + (7*24*60*60),
        "/",
        "",
        false,
        true
    );

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    Response::json([
    "access_token" => $accessToken,
    "csrf_token" => $_SESSION['csrf_token']
    ]);

}

public static function refresh() {
    
    CsrfMiddleware::handle();

    if (!isset($_COOKIE['refresh_token'])) {
        Response::json("Unauthorized", 401);
    }

    $refreshToken = $_COOKIE['refresh_token'];

    $record = User::getRefreshToken($refreshToken);

    if (!$record) {
        Response::json("Invalid refresh token", 401);
    }

    if (strtotime($record['expires_at']) < time()) {
        Response::json("Refresh token expired", 401);
    }

    $userId = $record['user_id'];

    User::deleteAllUserTokens($userId);

    $newAccess = JWT::generate([
        "id" => $userId
    ]);

    $newRefresh = bin2hex(random_bytes(40));

    $hashedToken = password_hash($newRefresh, PASSWORD_DEFAULT);

    User::saveRefreshToken(
        $userId,
        $hashedToken,
        date('Y-m-d H:i:s', time() + (7 * 24 * 60 * 60))
    );

    setcookie(
        "refresh_token",
        $newRefresh,
        time() + (7 * 24 * 60 * 60),
        "/",
        "",
        false,
        true
    );

    Response::json([
        "access_token" => $newAccess
    ]);
}


}
