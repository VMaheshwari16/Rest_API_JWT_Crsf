<?php
require_once __DIR__ . '/../../config/config.php';

class JWT {

    public static function generate($payload) {
        $header = base64_encode(json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]));

        $payload['iat'] = time();
        $payload['exp'] = time() + JWT_EXPIRY;
        $payload = base64_encode(json_encode($payload));

        $signature = base64_encode(
            hash_hmac("sha256", "$header.$payload", JWT_SECRET, true)
        );

        return "$header.$payload.$signature";
    }

    public static function verify($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        [$header, $payload, $signature] = $parts;

        $valid = base64_encode(
            hash_hmac("sha256", "$header.$payload", JWT_SECRET, true)
        );

        if (!hash_equals($valid, $signature)) return false;

        $data = json_decode(base64_decode($payload), true);
        if ($data['exp'] < time()) return false;

        return $data;
    }
    public static function refreshToken() {
        return bin2hex(random_bytes(40));
    }
}
