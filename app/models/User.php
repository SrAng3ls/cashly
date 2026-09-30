<?php
class User {
    public static function findByEmail(string $email): ?array {
        $st=Database::get()->prepare('SELECT * FROM users WHERE email=? LIMIT 1'); $st->execute([$email]); return $st->fetch() ?: null;
    }
    public static function find(int $id): ?array {
        $st=Database::get()->prepare('SELECT * FROM users WHERE id=?'); $st->execute([$id]); return $st->fetch() ?: null;
    }
    public static function all(): array { return Database::get()->query('SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC')->fetchAll(); }
    public static function create(string $name,string $email,string $password,string $role='user'): int {
        $st=Database::get()->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)'); $st->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$role]); return (int)Database::get()->lastInsertId();
    }
    public static function update(int $id,string $name,string $email,string $role): void {
        $st=Database::get()->prepare('UPDATE users SET name=?,email=?,role=? WHERE id=?'); $st->execute([$name,$email,$role,$id]);
    }
    public static function delete(int $id): void { $st=Database::get()->prepare('DELETE FROM users WHERE id=?'); $st->execute([$id]); }
    public static function setPassword(int $id,string $password): void { $st=Database::get()->prepare('UPDATE users SET password=? WHERE id=?'); $st->execute([password_hash($password,PASSWORD_DEFAULT),$id]); }
}
