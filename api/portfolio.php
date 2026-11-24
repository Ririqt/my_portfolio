<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
bootstrap_api(false);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    json_response(['error' => 'Method not allowed.'], 405);
}

try {
    $pdo = get_pdo();

    $profileRow = $pdo->query('SELECT * FROM profile ORDER BY id ASC LIMIT 1')->fetch();
    if (!$profileRow) {
        json_response(['seeded' => false, 'profile' => null, 'skills' => [], 'projects' => []]);
    }

    $adminId = (int) $profileRow['admin_id'];

    $skillsStmt = $pdo->prepare(
        'SELECT id, category, name, level, percent, note, sort_order
         FROM skills
         WHERE admin_id = ?
         ORDER BY category ASC, sort_order ASC, id ASC'
    );
    $skillsStmt->execute([$adminId]);
    $skillsRows = $skillsStmt->fetchAll();

    $projectsStmt = $pdo->prepare(
        'SELECT * FROM projects
         WHERE admin_id = ?
         ORDER BY featured DESC, sort_order ASC, id DESC'
    );
    $projectsStmt->execute([$adminId]);
    $projectsRows = $projectsStmt->fetchAll();

    json_response([
        'seeded'   => true,
        'profile'  => [
            'fullName'        => (string) $profileRow['full_name'],
            'firstName'       => (string) $profileRow['first_name'],
            'nickname'        => (string) $profileRow['nickname'],
            'role'            => (string) $profileRow['role'],
            'status'          => (string) $profileRow['status'],
            'location'        => (string) $profileRow['location'],
            'email'           => (string) $profileRow['email'],
            'university'      => (string) $profileRow['university'],
            'degree'          => (string) $profileRow['degree'],
            'graduation'      => (string) $profileRow['graduation'],
            'headlineSummary' => (string) $profileRow['headline_summary'],
            'aboutParagraphs' => array_values(array_filter([
                (string) $profileRow['about_bio_1'],
                (string) $profileRow['about_bio_2'],
            ])),
            'portraitFile'    => (string) $profileRow['portrait_url'],
            'socials'         => [
                'github'   => (string) $profileRow['github_url'],
                'linkedin' => (string) $profileRow['linkedin_url'],
                'facebook' => (string) $profileRow['facebook_url'],
            ],
        ],
        'skills'   => array_map(static fn(array $row): array => [
            'id'        => (int) $row['id'],
            'category'  => (string) $row['category'],
            'name'      => (string) $row['name'],
            'level'     => (string) $row['level'],
            'percent'   => (int) $row['percent'],
            'note'      => (string) $row['note'],
            'sortOrder' => (int) $row['sort_order'],
        ], $skillsRows),
        'projects' => array_map('format_project_row', $projectsRows),
    ]);
} catch (Throwable $e) {
    handle_api_exception($e);
}
