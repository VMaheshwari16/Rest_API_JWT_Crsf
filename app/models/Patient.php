<?php

class Patient {

    public static function all() {
        return Database::connect()
            ->query("SELECT * FROM patients")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT id FROM patients WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO patients (name,age,gender,phone,address)
             VALUES (?,?,?,?,?)"
        );
        return $stmt->execute($data);
    }

    public static function update($id, $data) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "UPDATE patients SET name=?,age=?,gender=?,phone=?,address=? WHERE id=?"
        );
        return $stmt->execute([
            $data['name'], $data['age'], $data['gender'],
            $data['phone'], $data['address'], $id
        ]);
    }

    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM patients WHERE id=?");
        return $stmt->execute([$id]);
    }
}
