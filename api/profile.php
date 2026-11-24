<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
bootstrap_api();

try {
    $pdo = get_pdo();
    $adminId = require_auth();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'GET') {
        $stmt = $pdo->prepare('SELECT * FROM profile WHERE admin_id = ? LIMIT 1');
        $stmt->execute([$adminId]);
        $row = $stmt->fetch();
        json_response(['profile' => $row ?: null]);
    }

    if ($method === 'PUT' || $method === 'POST') {
        verify_csrf();
        $body = read_json_body();

        $fullName = clean_text($body['fullName'] ?? '', 120);
        $firstName = clean_text($body['firstName'] ?? '', 80);
        $nickname = clean_text($body['nickname'] ?? '', 40);
        $role = clean_text($body['role'] ?? '', 160);
        $status = clean_text($body['status'] ?? '', 160);
        $location = clean_text($body['location'] ?? '', 120);
        $email = clean_text($body['email'] ?? '', 160);
        $university = clean_text($body['university'] ?? '', 160);
        $degree = clean_text($body['degree'] ?? '', 160);
        $graduation = clean_text($body['graduation'] ?? '', 80);
        $headlineSummary = clean_text($body['headlineSummary'] ?? '', 1000);
        $bio1 = clean_text($body['aboutBio1'] ?? '', 1500);
        $bio2 = clean_text($body['aboutBio2'] ?? '', 1500);
        $githubUrl = clean_url($body['githubUrl'] ?? '') ?? '';
        $linkedinUrl = clean_url($body['linkedinUrl'] ?? '') ?? '';
        $facebookUrl = clean_url($body['facebookUrl'] ?? '') ?? '';
        $portraitUrl = clean_text($body['portraitFile'] ?? 'images/rei-portrait.jpg', 255);

        if ($fullName === '' || $role === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            json_response(['error' => 'Full name, role, and a valid email address are required.'], 422);
        }

        $check = $pdo->prepare('SELECT id FROM profile WHERE admin_id = ? LIMIT 1');
        $check->execute([$adminId]);

        if ($check->fetch()) {
            $stmt = $pdo->prepare(
                'UPDATE profile SET
                    full_name = ?, first_name = ?, nickname = ?, role = ?, status = ?,
                    location = ?, email = ?, university = ?, degree = ?, graduation = ?,
                    headline_summary = ?, about_bio_1 = ?, about_bio_2 = ?,
                    github_url = ?, linkedin_url = ?, facebook_url = ?, portrait_url = ?
                 WHERE admin_id = ?'
            );
            $stmt->execute([
                $fullName, $firstName ?: $fullName, $nickname ?: $fullName, $role, $status,
                $location, $email, $university, $degree, $graduation,
                $headlineSummary, $bio1, $bio2,
                $githubUrl, $linkedinUrl, $facebookUrl, $portraitUrl,
                $adminId,
            ]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO profile (
                    admin_id, full_name, first_name, nickname, role, status,
                    location, email, university, degree, graduation,
                    headline_summary, about_bio_1, about_bio_2,
                    github_url, linkedin_url, facebook_url, portrait_url
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $adminId, $fullName, $firstName ?: $fullName, $nickname ?: $fullName, $role, $status,
                $location, $email, $university, $degree, $graduation,
                $headlineSummary, $bio1, $bio2,
                $githubUrl, $linkedinUrl, $facebookUrl, $portraitUrl,
            ]);
        }

        log_activity($pdo, $adminId, 'Updated profile and about details');
        json_response(['message' => 'Profile saved successfully.']);
    }

    json_response(['error' => 'Method not allowed.'], 405);
} catch (Throwable $e) {
    handle_api_exception($e);
}
