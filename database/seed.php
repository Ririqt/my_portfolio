<?php
declare(strict_types=1);

/**
 * CLI-only database seeder for the owner-only Portfolio CMS.
 *
 * Usage:
 *   php database/seed.php "YourStrongPassword123!"
 *   (or set ADMIN_INIT_PASSWORD in api/.env)
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Forbidden: seed.php can only be executed from the command line.');
}

require_once __DIR__ . '/../api/config.php';

$adminEmail = getenv('ADMIN_EMAIL') ?: 'mcrei1018@gmail.com';
$adminName = getenv('ADMIN_NAME') ?: 'Mharc Reinald Rigos';
$password = $argv[1] ?? (getenv('ADMIN_INIT_PASSWORD') ?: '');

if (strlen($password) < 8) {
    fwrite(STDERR, "Error: Provide an initial admin password (at least 8 characters).\n");
    fwrite(STDERR, "Example: php database/seed.php \"YourStrongPass123!\"\n");
    exit(1);
}

$pdo = get_pdo();
$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare('SELECT id FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([$adminEmail]);
    $existingAdmin = $stmt->fetch();

    if ($existingAdmin) {
        $adminId = (int) $existingAdmin['id'];
        echo "Admin already exists ({$adminEmail}, id={$adminId}). Updating password hash...\n";
        $upd = $pdo->prepare('UPDATE admins SET name = ?, password_hash = ? WHERE id = ?');
        $upd->execute([$adminName, password_hash($password, PASSWORD_DEFAULT), $adminId]);
    } else {
        $ins = $pdo->prepare('INSERT INTO admins (name, email, password_hash) VALUES (?, ?, ?)');
        $ins->execute([$adminName, $adminEmail, password_hash($password, PASSWORD_DEFAULT)]);
        $adminId = (int) $pdo->lastInsertId();
        echo "Created owner admin ({$adminEmail}, id={$adminId}).\n";
    }

    // Seed Profile (idempotent)
    $profileCheck = $pdo->prepare('SELECT id FROM profile WHERE admin_id = ? LIMIT 1');
    $profileCheck->execute([$adminId]);
    if (!$profileCheck->fetch()) {
        $profileStmt = $pdo->prepare(
            'INSERT INTO profile (
                admin_id, full_name, first_name, nickname, role, status, location, email,
                university, degree, graduation, headline_summary, about_bio_1, about_bio_2,
                github_url, linkedin_url, facebook_url, portrait_url
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $profileStmt->execute([
            $adminId,
            'Mharc Reinald Rigos',
            'Mharc Reinald',
            'Rei',
            'Computer Engineering Student & Web Developer',
            'Open to Software & Web Internships',
            'Pasig City, Philippines',
            'mcrei1018@gmail.com',
            'Polytechnic University of the Philippines',
            'B.S. in Computer Engineering',
            'Expected September 2027',
            'Computer Engineering student at PUP combining software development, web design, and hands-on QA testing to build clean, responsive, and dependable applications.',
            "I'm Mharc Reinald Rigos — most people call me Rei. I'm pursuing a Bachelor of Science in Computer Engineering at the Polytechnic University of the Philippines (Class of 2027), with a strong focus on software engineering, web development, and system integration.",
            'From visualizing data structures and sorting algorithms in the browser to testing web applications across devices for broken links and usability issues, I enjoy bridging thoughtful user interfaces with solid engineering fundamentals.',
            'https://github.com/Ririqt',
            'https://www.linkedin.com/in/reirigos1018/',
            'https://www.facebook.com/Mcreingggg218',
            'images/rei-portrait.jpg',
        ]);
        echo "Seeded initial profile.\n";
    }

    // Seed Skills (idempotent)
    $skills = [
        ['languages', 'HTML5 & Semantic Markup', 'Proficient', 85, 'Accessible structure, forms, SEO metadata', 1],
        ['languages', 'CSS3 & Responsive Design', 'Proficient', 80, 'Flexbox, CSS Grid, Tailwind CSS, mobile-first layouts', 2],
        ['languages', 'Python', 'Comfortable', 72, 'Scripting, data structures, algorithm tooling', 3],
        ['languages', 'JavaScript / TypeScript', 'Comfortable', 68, 'DOM interactivity, React + Vite UI architecture', 4],
        ['languages', 'Java', 'Comfortable', 65, 'Object-oriented design & academic engineering labs', 5],
        ['languages', 'PHP & MySQL', 'Foundational', 55, 'Server-side CRUD, session auth, relational schemas', 6],
        ['languages', 'C++', 'Foundational', 45, 'Core syntax, logic, and systems coursework', 7],
        ['tools', 'Visual Studio Code', 'Proficient', 90, 'Primary code editor, debugging & extensions', 1],
        ['tools', 'Git & GitHub', 'Comfortable', 75, 'Branching workflows, repositories & GitHub Pages', 2],
        ['tools', 'PyCharm', 'Comfortable', 75, 'Python project development & testing', 3],
        ['tools', 'TinkerCAD & AutoCAD', 'Comfortable', 70, 'Circuit simulation & computer-aided drafting', 4],
        ['tools', 'MS Office Suite', 'Proficient', 85, 'Technical documentation, QA reports & presentations', 5],
        ['systems', 'Website QA & Cross-Browser Testing', 'Proficient', 85, 'Functional checks, link audits, UX bug reporting', 1],
        ['systems', 'Windows OS Setup & Troubleshooting', 'Proficient', 85, 'OS installation, configuration & PC maintenance', 2],
        ['systems', 'Circuit Analysis & Breadboarding', 'Comfortable', 70, 'Electrical/electronic prototyping & diagnostics', 3],
        ['systems', 'Photo & Video Editing', 'Comfortable', 70, 'Visual media prep for web and presentations', 4],
    ];

    $skillStmt = $pdo->prepare(
        'INSERT IGNORE INTO skills (admin_id, category, name, level, percent, note, sort_order)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($skills as $s) {
        $skillStmt->execute([$adminId, $s[0], $s[1], $s[2], $s[3], $s[4], $s[5]]);
    }
    echo "Seeded skills.\n";

    // Seed Projects (idempotent)
    $projects = [
        [
            'windsurf-dsa',
            'Windsurf — DSA Visualizer',
            'Interactive Data Structure & Sorting Algorithm Web App',
            'Web App',
            'Live',
            1,
            '2025',
            'Front-end & Back-end Developer',
            'A web-based learning platform that animates core data structures and sorting algorithms step-by-step to make computer science fundamentals intuitive.',
            json_encode([
                'Visualizes Linked Lists, Stacks, Queues, Graphs, and Binary Trees interactively.',
                'Animates Quick Sort, Merge Sort, Insertion Sort, Selection Sort, and Bubble Sort.',
                'Led UI design improvements, layout structure, and UX feature ideas.',
            ]),
            json_encode(['HTML5', 'CSS3', 'JavaScript', 'Python', 'Render']),
            'windsurf',
            'https://windsurf.onrender.com/',
            'https://github.com/Ririqt',
            1,
        ],
        [
            'portfolio-cms',
            'Portfolio & Full-Stack CMS',
            'Personal Portfolio + Owner Admin CMS Architecture',
            'Full-Stack',
            'Completed',
            1,
            '2025–2026',
            'Full-Stack Developer',
            'A React + TypeScript + Tailwind portfolio paired with a hardened PHP & MySQL owner-only Admin CMS supporting PDO prepared statements, CSRF protection, and live CRUD.',
            json_encode([
                'Hardened PHP 8 + PDO REST API with prepared statements, bcrypt auth, and CSRF tokens.',
                'Warm brown-and-white Vite + React frontend with Light/Dark theme and static fallback.',
                'Owner-only /admin portal for live Profile, Skills, Projects, Inquiries, and Activity Logs.',
            ]),
            json_encode(['React', 'TypeScript', 'Vite', 'Tailwind CSS', 'PHP', 'MySQL']),
            'cms',
            null,
            'https://github.com/Ririqt/my_portfolio',
            2,
        ],
        [
            'weather-dashboard',
            'Weather Forecasting System',
            'Clean Real-Time & Multi-Day Forecast Interface',
            'UI & Frontend',
            'In Progress',
            0,
            '2025',
            'Front-end Developer',
            'A sleek weather monitoring UI structured around current conditions, air metrics (wind, humidity, feels-like), hourly intervals, and a weekly outlook.',
            json_encode([
                'Designed clear visual hierarchy for rapid scanning of temperature and forecast cards.',
                'Structured modular forecast rows and search-ready city lookup flow.',
                'Responsive grid architecture built for desktop and mobile viewports.',
            ]),
            json_encode(['HTML5', 'CSS3', 'JavaScript', 'UI Design']),
            'weather',
            null,
            'https://github.com/Ririqt',
            3,
        ],
        [
            'simple-calculator',
            'Interactive Web Calculator',
            'Responsive Arithmetic Utility with Tactile UI',
            'UI & Frontend',
            'Completed',
            0,
            '2025',
            'Front-end Developer',
            'A focused, functional web calculator emphasizing crisp spacing, instant button feedback, and clean expression display.',
            json_encode([
                'Clean state handling for chained operations, decimals, and clear/reset actions.',
                'High-contrast keypad grid crafted for both mouse clicks and touch screens.',
            ]),
            json_encode(['HTML5', 'CSS3', 'JavaScript']),
            'calculator',
            null,
            'https://github.com/Ririqt',
            4,
        ],
    ];

    $projStmt = $pdo->prepare(
        'INSERT IGNORE INTO projects (
            admin_id, slug, title, subtitle, category, status, featured, year, role,
            summary, highlights_json, tech_json, visual_type, live_url, repo_url, sort_order
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($projects as $p) {
        $projStmt->execute([$adminId, ...$p]);
    }
    echo "Seeded projects.\n";

    $logStmt = $pdo->prepare('INSERT INTO activity_logs (admin_id, action, ip_address) VALUES (?, ?, ?)');
    $logStmt->execute([$adminId, 'Initialized portfolio database via CLI seeder', '127.0.0.1']);

    $pdo->commit();
    echo "Done! You can now sign in at /#/admin with {$adminEmail}.\n";
} catch (Throwable $e) {
    $pdo->rollBack();
    fwrite(STDERR, 'Seed failed: ' . $e->getMessage() . "\n");
    exit(1);
}
