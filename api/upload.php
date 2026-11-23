<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
bootstrap_api();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response(['error' => 'Method not allowed.'], 405);
}

const MAX_UPLOAD_BYTES = 3 * 1024 * 1024; // 3 MB
const ALLOWED_MIME_EXTENSIONS = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

try {
    $pdo = get_pdo();
    $adminId = require_auth();
    verify_csrf();

    if (empty($_FILES['image']) || !is_array($_FILES['image'])) {
        json_response(['error' => 'No image file provided.'], 400);
    }

    $file = $_FILES['image'];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        json_response(['error' => 'Upload failed. Please try a smaller image file.'], 400);
    }

    $size = (int) ($file['size'] ?? 0);
    if ($size <= 0 || $size > MAX_UPLOAD_BYTES) {
        json_response(['error' => 'Image must be under 3 MB.'], 422);
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        json_response(['error' => 'Invalid upload source.'], 400);
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file($tmpName);

    if (!isset(ALLOWED_MIME_EXTENSIONS[$mime])) {
        json_response(['error' => 'Only JPG, PNG, and WebP images are allowed.'], 422);
    }

    $ext = ALLOWED_MIME_EXTENSIONS[$mime];
    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $safeFileName = 'img_' . bin2hex(random_bytes(12)) . '.' . $ext;
    $destination = $uploadDir . DIRECTORY_SEPARATOR . $safeFileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        json_response(['error' => 'Could not save uploaded file.'], 500);
    }

    log_activity($pdo, $adminId, "Uploaded image asset ({$safeFileName})");

    json_response([
        'url'     => 'api/uploads/' . $safeFileName,
        'message' => 'Image uploaded successfully.',
    ], 201);
} catch (Throwable $e) {
    handle_api_exception($e);
}
