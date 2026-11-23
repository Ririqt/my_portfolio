<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
bootstrap_api();

const VALID_SKILL_CATEGORIES = ['languages', 'tools', 'systems'];
const VALID_SKILL_LEVELS = ['Proficient', 'Comfortable', 'Foundational'];

try {
    $pdo = get_pdo();
    $adminId = require_auth();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'GET') {
        $stmt = $pdo->prepare(
            'SELECT id, category, name, level, percent, note, sort_order
             FROM skills
             WHERE admin_id = ?
             ORDER BY category ASC, sort_order ASC, id ASC'
        );
        $stmt->execute([$adminId]);
        $rows = $stmt->fetchAll();

        json_response([
            'skills' => array_map(static fn(array $r): array => [
                'id'        => (int) $r['id'],
                'category'  => (string) $r['category'],
                'name'      => (string) $r['name'],
                'level'     => (string) $r['level'],
                'percent'   => (int) $r['percent'],
                'note'      => (string) $r['note'],
                'sortOrder' => (int) $r['sort_order'],
            ], $rows),
        ]);
    }

    verify_csrf();

    if ($method === 'POST') {
        $body = read_json_body();
        $name = clean_text($body['name'] ?? '', 100);
        $category = (string) ($body['category'] ?? 'languages');
        $level = (string) ($body['level'] ?? 'Comfortable');
        $percent = max(5, min(100, (int) ($body['percent'] ?? 70)));
        $note = clean_text($body['note'] ?? '', 255);

        if ($name === '' || !in_array($category, VALID_SKILL_CATEGORIES, true) || !in_array($level, VALID_SKILL_LEVELS, true)) {
            json_response(['error' => 'Valid skill name, category, and level are required.'], 422);
        }

        $dup = $pdo->prepare('SELECT id FROM skills WHERE admin_id = ? AND name = ? LIMIT 1');
        $dup->execute([$adminId, $name]);
        if ($dup->fetch()) {
            json_response(['error' => "Skill \"{$name}\" already exists."], 409);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO skills (admin_id, category, name, level, percent, note)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$adminId, $category, $name, $level, $percent, $note]);
        $newId = (int) $pdo->lastInsertId();

        log_activity($pdo, $adminId, "Added skill: {$name}");

        json_response([
            'skill' => [
                'id'        => $newId,
                'category'  => $category,
                'name'      => $name,
                'level'     => $level,
                'percent'   => $percent,
                'note'      => $note,
                'sortOrder' => 0,
            ],
        ], 201);
    }

    if ($method === 'PUT') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            json_response(['error' => 'Invalid skill ID.'], 400);
        }

        $body = read_json_body();
        $name = clean_text($body['name'] ?? '', 100);
        $category = (string) ($body['category'] ?? 'languages');
        $level = (string) ($body['level'] ?? 'Comfortable');
        $percent = max(5, min(100, (int) ($body['percent'] ?? 70)));
        $note = clean_text($body['note'] ?? '', 255);

        if ($name === '' || !in_array($category, VALID_SKILL_CATEGORIES, true) || !in_array($level, VALID_SKILL_LEVELS, true)) {
            json_response(['error' => 'Valid skill name, category, and level are required.'], 422);
        }

        $dup = $pdo->prepare('SELECT id FROM skills WHERE admin_id = ? AND name = ? AND id <> ? LIMIT 1');
        $dup->execute([$adminId, $name, $id]);
        if ($dup->fetch()) {
            json_response(['error' => "Another skill named \"{$name}\" already exists."], 409);
        }

        $stmt = $pdo->prepare(
            'UPDATE skills SET category = ?, name = ?, level = ?, percent = ?, note = ?
             WHERE id = ? AND admin_id = ?'
        );
        $stmt->execute([$category, $name, $level, $percent, $note, $id, $adminId]);

        if ($stmt->rowCount() === 0) {
            $exists = $pdo->prepare('SELECT id FROM skills WHERE id = ? AND admin_id = ? LIMIT 1');
            $exists->execute([$id, $adminId]);
            if (!$exists->fetch()) {
                json_response(['error' => 'Skill not found.'], 404);
            }
        }

        log_activity($pdo, $adminId, "Updated skill: {$name}");
        json_response(['message' => 'Skill updated successfully.']);
    }

    if ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            json_response(['error' => 'Invalid skill ID.'], 400);
        }

        $stmt = $pdo->prepare('SELECT name FROM skills WHERE id = ? AND admin_id = ? LIMIT 1');
        $stmt->execute([$id, $adminId]);
        $row = $stmt->fetch();
        if (!$row) {
            json_response(['error' => 'Skill not found.'], 404);
        }

        $del = $pdo->prepare('DELETE FROM skills WHERE id = ? AND admin_id = ?');
        $del->execute([$id, $adminId]);

        log_activity($pdo, $adminId, 'Deleted skill: ' . (string) $row['name']);
        json_response(['message' => 'Skill deleted.']);
    }

    json_response(['error' => 'Method not allowed.'], 405);
} catch (Throwable $e) {
    handle_api_exception($e);
}
