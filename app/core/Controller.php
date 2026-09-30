<?php
declare(strict_types=1);

class Controller {
    protected function render(string $view, array $data = []): void {
        // Genera el token CSRF antes de renderizar cualquier formulario.
        // Así todas las vistas que usan la variable csrf reciben el mismo token
        // que posteriormente valida verifyCsrf().
        $data['csrf'] = $this->csrf();

        extract($data);

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect(string $url): never {
        header('Location: ' . $url);
        exit;
    }

    protected function csrf(): string {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf'];
    }

    protected function verifyCsrf(): void {
        $token = $_POST['csrf'] ?? '';

        if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(419);
            exit('Solicitud inválida. Recarga la página e inténtalo nuevamente.');
        }
    }

    protected function flash(string $type, string $message): void {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}