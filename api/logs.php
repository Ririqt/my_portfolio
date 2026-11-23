<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
bootstrap_api();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    json_response(['error' => 'Method not allowed.'], 405);
}

try {
    $pdo = get_pdo();
    $adminId = require_auth();

    $page = max(1, (int) ($_GET['page'] ?? 1));
    $perPage = max(1, min(50, (int) ($_GET['per_page'] ?? 15)));
    $offset = ($page - 1) * $perPage;

    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM activity_logs WHERE admin_id = ?');
    $countStmt->execute([$adminId]);
    $total = (int) $countStmt->fetchColumn();

    $stmt = $pdo->prepare(
        'SELECT id, action, ip_address, created_at
         FROM activity_logs
         WHERE admin_id = ?
         ORDER BY created_at DESC, id DESC
         LIMIT ? OFFSET ?'
    );
    $stmt->bindValue(1, $adminId, PDO::PARAM_INT);
    $stmt->bindValue(2, $perPage, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();

    $logs = array_map(static fn(array $r): array => [
        'id'        => (int) $r['id'],
        'action'    => (string) $r['action'],
        'ipAddress' => $r['ip_address'] ? (string) $r['ip_address'] : null,
        'createdAt' => (string) $r['created_at'],
    ], $stmt->fetchAll());

    json_response([
        'logs'       => $logs,
        'total'      => $total,
        'page'       => $page,
        'perPage'    => $perPage,
        'totalPages' => max(1, (int) ceil($total / $perPage)),
    ]);
} catch (Throwable $e) {
    handle_api_exception($e);
}
