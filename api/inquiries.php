<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
bootstrap_api();

try {
    $pdo = get_pdo();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    // Public contact form submission
    if ($method === 'POST') {
        $body = read_json_body();

        // Simple honeypot spam trap
        if (trim((string) ($body['website'] ?? '')) !== '') {
            json_response(['message' => 'Thank you for reaching out!'], 201);
        }

        $name = clean_text($body['name'] ?? '', 120);
        $email = clean_text($body['email'] ?? '', 160);
        $subject = clean_text($body['subject'] ?? '', 160);
        $message = clean_text($body['message'] ?? '', 3000);

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $subject === '' || $message === '') {
            json_response(['error' => 'Please enter a valid name, email, subject, and message.'], 422);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO inquiries (name, email, subject, message) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $subject, $message]);

        json_response([
            'id'      => (int) $pdo->lastInsertId(),
            'message' => 'Your message has been saved. Thanks for reaching out!',
        ], 201);
    }

    // Owner-only endpoints below
    $adminId = require_auth();

    if ($method === 'GET') {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, min(50, (int) ($_GET['per_page'] ?? 10)));
        $offset = ($page - 1) * $perPage;

        $total = (int) $pdo->query('SELECT COUNT(*) FROM inquiries')->fetchColumn();
        $unread = (int) $pdo->query('SELECT COUNT(*) FROM inquiries WHERE is_read = 0')->fetchColumn();

        $stmt = $pdo->prepare(
            'SELECT id, name, email, subject, message, is_read, created_at
             FROM inquiries
             ORDER BY created_at DESC, id DESC
             LIMIT ? OFFSET ?'
        );
        $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        $items = array_map(static fn(array $r): array => [
            'id'        => (int) $r['id'],
            'name'      => (string) $r['name'],
            'email'     => (string) $r['email'],
            'subject'   => (string) $r['subject'],
            'message'   => (string) $r['message'],
            'isRead'    => (bool) $r['is_read'],
            'createdAt' => (string) $r['created_at'],
        ], $stmt->fetchAll());

        json_response([
            'inquiries'  => $items,
            'total'      => $total,
            'unread'     => $unread,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => max(1, (int) ceil($total / $perPage)),
        ]);
    }

    verify_csrf();

    if ($method === 'PATCH') {
        $id = (int) ($_GET['id'] ?? 0);
        $body = read_json_body();
        $isRead = !empty($body['isRead']) ? 1 : 0;

        if ($id <= 0) {
            json_response(['error' => 'Invalid inquiry ID.'], 400);
        }

        $stmt = $pdo->prepare('UPDATE inquiries SET is_read = ? WHERE id = ?');
        $stmt->execute([$isRead, $id]);

        log_activity($pdo, $adminId, $isRead ? "Marked inquiry #{$id} as read" : "Marked inquiry #{$id} as unread");
        json_response(['message' => 'Inquiry status updated.']);
    }

    if ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            json_response(['error' => 'Invalid inquiry ID.'], 400);
        }

        $stmt = $pdo->prepare('DELETE FROM inquiries WHERE id = ?');
        $stmt->execute([$id]);

        log_activity($pdo, $adminId, "Deleted inquiry #{$id}");
        json_response(['message' => 'Inquiry deleted.']);
    }

    json_response(['error' => 'Method not allowed.'], 405);
} catch (Throwable $e) {
    handle_api_exception($e);
}
