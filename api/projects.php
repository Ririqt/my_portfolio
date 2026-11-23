<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
bootstrap_api();

const VALID_PROJECT_CATEGORIES = ['Web App', 'Full-Stack', 'UI & Frontend'];
const VALID_PROJECT_STATUSES = ['Live', 'Completed', 'In Progress'];
const VALID_VISUAL_TYPES = ['windsurf', 'cms', 'weather', 'calculator'];

function normalize_string_list(mixed $value, int $maxItems = 12, int $maxItemLen = 180): array
{
    if (is_string($value)) {
        $value = preg_split('/[\r\n,]+/', $value) ?: [];
    }
    if (!is_array($value)) {
        return [];
    }
    $items = [];
    foreach ($value as $entry) {
        if (!is_string($entry)) {
            continue;
        }
        $clean = mb_substr(trim($entry), 0, $maxItemLen);
        if ($clean !== '') {
            $items[] = $clean;
        }
        if (count($items) >= $maxItems) {
            break;
        }
    }
    return $items;
}

try {
    $pdo = get_pdo();
    $adminId = require_auth();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'GET') {
        $stmt = $pdo->prepare(
            'SELECT * FROM projects
             WHERE admin_id = ?
             ORDER BY featured DESC, sort_order ASC, id DESC'
        );
        $stmt->execute([$adminId]);
        $rows = $stmt->fetchAll();

        json_response(['projects' => array_map('format_project_row', $rows)]);
    }

    verify_csrf();

    if ($method === 'POST') {
        $body = read_json_body();
        $title = clean_text($body['title'] ?? '', 140);
        $subtitle = clean_text($body['subtitle'] ?? '', 180);
        $category = (string) ($body['category'] ?? 'Web App');
        $status = (string) ($body['status'] ?? 'Completed');
        $featured = !empty($body['featured']) ? 1 : 0;
        $year = clean_text($body['year'] ?? date('Y'), 24);
        $role = clean_text($body['role'] ?? 'Developer', 120);
        $summary = clean_text($body['summary'] ?? '', 1200);
        $highlights = normalize_string_list($body['highlights'] ?? [], 8, 220);
        $tech = normalize_string_list($body['tech'] ?? [], 10, 40);
        $visualType = (string) ($body['visualType'] ?? 'windsurf');
        $liveUrl = clean_url($body['liveUrl'] ?? null);
        $repoUrl = clean_url($body['repoUrl'] ?? null);
        $imageUrl = clean_text($body['imageUrl'] ?? '', 255) ?: null;

        if (
            $title === '' ||
            $summary === '' ||
            !in_array($category, VALID_PROJECT_CATEGORIES, true) ||
            !in_array($status, VALID_PROJECT_STATUSES, true) ||
            !in_array($visualType, VALID_VISUAL_TYPES, true)
        ) {
            json_response(['error' => 'Title, summary, category, status, and visual style are required.'], 422);
        }

        $baseSlug = slugify($title);
        $slug = $baseSlug;
        $checkSlug = $pdo->prepare('SELECT id FROM projects WHERE admin_id = ? AND slug = ? LIMIT 1');
        $checkSlug->execute([$adminId, $slug]);
        if ($checkSlug->fetch()) {
            $slug = $baseSlug . '-' . bin2hex(random_bytes(2));
        }

        $stmt = $pdo->prepare(
            'INSERT INTO projects (
                admin_id, slug, title, subtitle, category, status, featured, year, role,
                summary, highlights_json, tech_json, visual_type, image_url, live_url, repo_url
             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $adminId,
            $slug,
            $title,
            $subtitle,
            $category,
            $status,
            $featured,
            $year,
            $role,
            $summary,
            json_encode($highlights, JSON_UNESCAPED_UNICODE),
            json_encode($tech, JSON_UNESCAPED_UNICODE),
            $visualType,
            $imageUrl,
            $liveUrl,
            $repoUrl,
        ]);

        $newId = (int) $pdo->lastInsertId();
        log_activity($pdo, $adminId, "Created project: {$title}");

        $created = $pdo->prepare('SELECT * FROM projects WHERE id = ? LIMIT 1');
        $created->execute([$newId]);
        $row = $created->fetch();

        json_response(['project' => $row ? format_project_row($row) : null], 201);
    }

    if ($method === 'PUT') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            json_response(['error' => 'Invalid project ID.'], 400);
        }

        $body = read_json_body();
        $title = clean_text($body['title'] ?? '', 140);
        $subtitle = clean_text($body['subtitle'] ?? '', 180);
        $category = (string) ($body['category'] ?? 'Web App');
        $status = (string) ($body['status'] ?? 'Completed');
        $featured = !empty($body['featured']) ? 1 : 0;
        $year = clean_text($body['year'] ?? date('Y'), 24);
        $role = clean_text($body['role'] ?? 'Developer', 120);
        $summary = clean_text($body['summary'] ?? '', 1200);
        $highlights = normalize_string_list($body['highlights'] ?? [], 8, 220);
        $tech = normalize_string_list($body['tech'] ?? [], 10, 40);
        $visualType = (string) ($body['visualType'] ?? 'windsurf');
        $liveUrl = clean_url($body['liveUrl'] ?? null);
        $repoUrl = clean_url($body['repoUrl'] ?? null);
        $imageUrl = clean_text($body['imageUrl'] ?? '', 255) ?: null;

        if (
            $title === '' ||
            $summary === '' ||
            !in_array($category, VALID_PROJECT_CATEGORIES, true) ||
            !in_array($status, VALID_PROJECT_STATUSES, true) ||
            !in_array($visualType, VALID_VISUAL_TYPES, true)
        ) {
            json_response(['error' => 'Title, summary, category, status, and visual style are required.'], 422);
        }

        $exists = $pdo->prepare('SELECT id FROM projects WHERE id = ? AND admin_id = ? LIMIT 1');
        $exists->execute([$id, $adminId]);
        if (!$exists->fetch()) {
            json_response(['error' => 'Project not found.'], 404);
        }

        $stmt = $pdo->prepare(
            'UPDATE projects SET
                title = ?, subtitle = ?, category = ?, status = ?, featured = ?, year = ?, role = ?,
                summary = ?, highlights_json = ?, tech_json = ?, visual_type = ?,
                image_url = ?, live_url = ?, repo_url = ?
             WHERE id = ? AND admin_id = ?'
        );
        $stmt->execute([
            $title,
            $subtitle,
            $category,
            $status,
            $featured,
            $year,
            $role,
            $summary,
            json_encode($highlights, JSON_UNESCAPED_UNICODE),
            json_encode($tech, JSON_UNESCAPED_UNICODE),
            $visualType,
            $imageUrl,
            $liveUrl,
            $repoUrl,
            $id,
            $adminId,
        ]);

        log_activity($pdo, $adminId, "Updated project: {$title}");
        json_response(['message' => 'Project updated successfully.']);
    }

    if ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            json_response(['error' => 'Invalid project ID.'], 400);
        }

        $stmt = $pdo->prepare('SELECT title FROM projects WHERE id = ? AND admin_id = ? LIMIT 1');
        $stmt->execute([$id, $adminId]);
        $row = $stmt->fetch();
        if (!$row) {
            json_response(['error' => 'Project not found.'], 404);
        }

        $del = $pdo->prepare('DELETE FROM projects WHERE id = ? AND admin_id = ?');
        $del->execute([$id, $adminId]);

        log_activity($pdo, $adminId, 'Deleted project: ' . (string) $row['title']);
        json_response(['message' => 'Project deleted.']);
    }

    json_response(['error' => 'Method not allowed.'], 405);
} catch (Throwable $e) {
    handle_api_exception($e);
}
