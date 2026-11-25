<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function is_https_request(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function bootstrap_api(bool $startSession = true): void
{
    // Security & no-cache headers so dynamic CMS edits appear immediately
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Controlled CORS for Vite dev server / configured frontend origin
    $allowedOrigin = env_value('ALLOWED_ORIGIN', 'http://localhost:5173');
    $requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if ($requestOrigin !== '' && $requestOrigin === $allowedOrigin) {
        header("Access-Control-Allow-Origin: {$allowedOrigin}");
        header('Vary: Origin');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    if (!$startSession) {
        return;
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => is_https_request(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        ini_set('session.use_strict_mode', '1');
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

function json_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function read_json_body(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        json_response(['error' => 'Invalid JSON request payload.'], 400);
    }
    return $decoded;
}

function get_csrf_token(): string
{
    return (string) ($_SESSION['csrf_token'] ?? '');
}

function require_auth(): int
{
    $adminId = $_SESSION['admin_id'] ?? null;
    if (!is_int($adminId) || $adminId <= 0) {
        json_response(['error' => 'Authentication required. Please sign in.'], 401);
    }
    return $adminId;
}

function verify_csrf(): void
{
    $headerToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $sessionToken = get_csrf_token();
    if ($headerToken === '' || $sessionToken === '' || !hash_equals($sessionToken, $headerToken)) {
        json_response(['error' => 'Invalid or missing CSRF token. Refresh and try again.'], 403);
    }
}

function log_activity(PDO $pdo, int $adminId, string $action): void
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $stmt = $pdo->prepare('INSERT INTO activity_logs (admin_id, action, ip_address) VALUES (?, ?, ?)');
    $stmt->execute([$adminId, mb_substr($action, 0, 180), $ip]);
}

function clean_text(mixed $value, int $maxLength = 255): string
{
    if (!is_string($value)) {
        return '';
    }
    return mb_substr(trim($value), 0, $maxLength);
}

function clean_url(mixed $value): ?string
{
    if (!is_string($value)) {
        return null;
    }
    $trimmed = trim($value);
    if ($trimmed === '') {
        return null;
    }
    if (!filter_var($trimmed, FILTER_VALIDATE_URL)) {
        return null;
    }
    $scheme = strtolower((string) parse_url($trimmed, PHP_URL_SCHEME));
    if (!in_array($scheme, ['http', 'https'], true)) {
        return null;
    }
    return mb_substr($trimmed, 0, 255);
}

function format_project_row(array $row): array
{
    $highlights = json_decode((string) ($row['highlights_json'] ?? '[]'), true);
    $tech = json_decode((string) ($row['tech_json'] ?? '[]'), true);

    return [
        'id'         => (string) $row['id'],
        'dbId'       => (int) $row['id'],
        'slug'       => (string) $row['slug'],
        'title'      => (string) $row['title'],
        'subtitle'   => (string) $row['subtitle'],
        'category'   => (string) $row['category'],
        'status'     => (string) $row['status'],
        'featured'   => (bool) $row['featured'],
        'year'       => (string) $row['year'],
        'role'       => (string) $row['role'],
        'summary'    => (string) $row['summary'],
        'highlights' => is_array($highlights) ? array_values(array_map('strval', $highlights)) : [],
        'tech'       => is_array($tech) ? array_values(array_map('strval', $tech)) : [],
        'visualType' => (string) $row['visual_type'],
        'imageUrl'   => $row['image_url'] ?: null,
        'liveUrl'    => $row['live_url'] ?: undefined_to_null($row['live_url']),
        'repoUrl'    => $row['repo_url'] ?: undefined_to_null($row['repo_url']),
        'sortOrder'  => (int) $row['sort_order'],
    ];
}

function undefined_to_null(mixed $v): ?string
{
    return is_string($v) && trim($v) !== '' ? trim($v) : null;
}

function slugify(string $text): string
{
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $text) ?? '', '-'));
    return $slug !== '' ? mb_substr($slug, 0, 100) : 'project-' . bin2hex(random_bytes(4));
}

function handle_api_exception(Throwable $e): never
{
    $isDev = env_value('APP_ENV', 'development') === 'development';
    json_response(
        [
            'error' => $isDev ? $e->getMessage() : 'Internal server error.',
        ],
        500
    );
}
