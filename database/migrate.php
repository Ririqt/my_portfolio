<?php
declare(strict_types=1);

/**
 * CLI-only schema migration runner.
 * Reads MySQL credentials from api/.env and executes database/schema.sql.
 *
 * Usage:
 *   php database/migrate.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Forbidden: migrate.php can only be executed from the command line.');
}

require_once __DIR__ . '/../api/config.php';

$host = env_value('DB_HOST', '127.0.0.1');
$port = env_value('DB_PORT', '3306');
$db   = env_value('DB_NAME', 'my_portfolio');
$user = env_value('DB_USER', 'root');
$pass = env_value('DB_PASS', '');

try {
    $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $host, $port);
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Check for legacy schema conflict if database already exists
    $dbExists = $pdo->prepare('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?');
    $dbExists->execute([$db]);
    if ($dbExists->fetch()) {
        $colCheck = $pdo->prepare(
            "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'skills' AND COLUMN_NAME = 'admin_id'"
        );
        $tableCheck = $pdo->prepare(
            "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'skills'"
        );
        $tableCheck->execute([$db]);
        $colCheck->execute([$db]);
        if ((int) $tableCheck->fetchColumn() > 0 && (int) $colCheck->fetchColumn() === 0) {
            fwrite(
                STDERR,
                "LEGACY_SCHEMA_CONFLICT: Database '{$db}' already contains an older 'skills' table without 'admin_id'.\n"
            );
            exit(2);
        }
    }

    $sqlPath = __DIR__ . '/schema.sql';
    $sql = file_get_contents($sqlPath);
    if ($sql === false) {
        throw new RuntimeException('Could not read database/schema.sql');
    }

    // Replace default database name if DB_NAME in .env was customized
    if ($db !== 'my_portfolio') {
        $safeDb = preg_replace('/[^a-zA-Z0-9_]/', '', $db) ?: 'my_portfolio';
        $sql = str_replace('`my_portfolio`', "`{$safeDb}`", $sql);
    }

    // Remove single-line SQL comments and split into statements
    $lines = preg_split('/\r?\n/', $sql) ?: [];
    $cleaned = [];
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '--')) {
            continue;
        }
        $cleaned[] = $line;
    }

    $statements = array_filter(
        array_map('trim', explode(';', implode("\n", $cleaned))),
        static fn(string $s): bool => $s !== ''
    );

    foreach ($statements as $statement) {
        $pdo->exec($statement);
    }

    echo "Schema applied successfully to database '{$db}'.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Migration failed: ' . $e->getMessage() . "\n");
    exit(1);
}
