<?php
declare(strict_types=1);

class Auth {
    public static function check(): bool { return isset($_SESSION['user']); }
    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'=>(int)$user['id'], 'name'=>$user['name'], 'email'=>$user['email'], 'role'=>$user['role']
        ];
    }
    public static function logout(): void { $_SESSION=[]; session_destroy(); }
    public static function requireLogin(): void {
        if (!self::check()) { header('Location: index.php?url=login'); exit; }
    }
    public static function requireAdmin(): void {
        self::requireLogin();
        if ((self::user()['role'] ?? '') !== 'admin') { http_response_code(403); exit('No tienes permisos para acceder a esta sección.'); }
    }
}
