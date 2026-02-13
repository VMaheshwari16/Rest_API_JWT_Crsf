<?php

class AuthMiddleware {

    public static function handle() {

        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Response::json("Unauthorized", 401);
        }

        $token = $matches[1];

        $payload = JWT::verify($token);

        if ($payload) {
            $user = User::findById($payload['id']);

            if (!$user) {
                Response::json("Unauthorized: User not found", 401);
            }

            return $user;
        }

        // Access token expired → manual decode
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            Response::json("Invalid token", 401);
        }

        $payloadData = json_decode(base64_decode($parts[1]), true);
        $expiredUserId = $payloadData['id'] ?? null;

        $refreshToken = $_COOKIE['refresh_token'] ?? null;

        if (!$refreshToken) {
            Response::json("Session expired", 401);
        }

        $tokenRow = User::getRefreshToken($refreshToken);

        if (!$tokenRow) {
            Response::json("Invalid refresh token", 401);
        }

        if ((int)$expiredUserId !== (int)$tokenRow['user_id']) {
            Response::json("Unauthorized: Token mismatch", 401);
        }

        Response::json("Access token expired. Please refresh.", 401);
    }
}
