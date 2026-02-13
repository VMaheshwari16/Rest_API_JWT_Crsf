<?php
class User {
    public static function create($data) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO users (name,email,password) VALUES (?,?,?)"
        );
        return $stmt->execute($data);
    }

    public static function findByEmail($email) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {

    $db = Database::connect();

    $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");

    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public static function saveRefreshToken($userId, $tokenHash, $expiry) {
    $db = Database::connect();
    $stmt = $db->prepare(
        "INSERT INTO refresh_tokens (user_id, token_hash, expires_at)
         VALUES (?, ?, ?)"
    );
    $stmt->execute([$userId, $tokenHash, $expiry]);
}


public static function getRefreshToken($refreshToken) {

    $db = Database::connect();

    $stmt = $db->query("SELECT * FROM refresh_tokens");
    $tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tokens as $row) {

        if (password_verify($refreshToken, $row['token_hash'])) {
            return $row;
        }
    }

    return false;
}

public static function deleteAllUserTokens($userId) {
    $db = Database::connect();
    $stmt = $db->prepare("DELETE FROM refresh_tokens WHERE user_id = ?");
    $stmt->execute([$userId]);
}



}
