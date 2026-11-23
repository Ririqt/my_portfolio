<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
bootstrap_api();

try {
    $pdo = get_pdo();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $action = (string) ($_GET['action'] ?? 'me');

    if ($method === 'GET' && $action === 'me') {
        $adminId = $_SESSION['admin_id'] ?? null;
        if (!is_int($adminId) || $adminId <= 0) {
            json_response([
                'authenticated' => false,
                'user'          => null,
                'csrfToken'     => get_csrf_token(),
            ]);
        }

        $stmt = $pdo->prepare('SELECT id, name, email, created_at FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        if (!$admin) {
            $_SESSION = [];
            json_response([
                'authenticated' => false,
                'user'          => null,
                'csrfToken'     => get_csrf_token(),
            ]);
        }

        json_response([
            'authenticated' => true,
            'user'          => [
                'id'    => (int) $admin['id'],
                'name'  => (string) $admin['name'],
                'email' => (string) $admin['email'],
            ],
            'csrfToken'     => get_csrf_token(),
        ]);
    }

    if ($method === 'POST' && $action === 'login') {
        verify_csrf();
        $body = read_json_body();
        $email = strtolower(clean_text($body['email'] ?? '', 160));
        $password = is_string($body['password'] ?? null) ? $body['password'] : '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            json_response(['error' => 'Please enter a valid email and password.'], 422);
        }

        $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM admins WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($password, (string) $admin['password_hash'])) {
            json_response(['error' => 'Invalid email or password.'], 401);
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        log_activity($pdo, (int) $admin['id'], 'Signed in to Admin CMS');

        json_response([
            'authenticated' => true,
            'user'          => [
                'id'    => (int) $admin['id'],
                'name'  => (string) $admin['name'],
                'email' => (string) $admin['email'],
            ],
            'csrfToken'     => get_csrf_token(),
        ]);
    }

    if ($method === 'POST' && $action === 'logout') {
        $adminId = require_auth();
        verify_csrf();

        log_activity($pdo, $adminId, 'Signed out of Admin CMS');

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                (bool) $params['secure'],
                (bool) $params['httponly']
            );
        }
        session_destroy();

        json_response(['authenticated' => false, 'message' => 'Signed out successfully.']);
    }

    if ($method === 'POST' && $action === 'change_password') {
        $adminId = require_auth();
        verify_csrf();

        $body = read_json_body();
        $currentPassword = is_string($body['currentPassword'] ?? null) ? $body['currentPassword'] : '';
        $newPassword = is_string($body['newPassword'] ?? null) ? $body['newPassword'] : '';

        if (strlen($newPassword) < 8 || !preg_match('/[A-Za-z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
            json_response(['error' => 'New password must be at least 8 characters and include a letter and number.'], 422);
        }

        $stmt = $pdo->prepare('SELECT password_hash FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($currentPassword, (string) $admin['password_hash'])) {
            json_response(['error' => 'Current password is incorrect.'], 401);
        }

        $upd = $pdo->prepare('UPDATE admins SET password_hash = ? WHERE id = ?');
        $upd->execute([password_hash($newPassword, PASSWORD_DEFAULT), $adminId]);

        log_activity($pdo, $adminId, 'Updated owner admin password');
        json_response(['message' => 'Password updated successfully.']);
    }

    json_response(['error' => 'Unsupported auth route or method.'], 405);
} catch (Throwable $e) {
    handle_api_exception($e);
}
