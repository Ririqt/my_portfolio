# Mharc Reinald Rigos — Developer Portfolio & Owner CMS

A fast, responsive **brown-and-white** personal developer portfolio built with **Vite, React, TypeScript, and Tailwind CSS**, paired with a hardened, owner-only **PHP 8 + MySQL REST API** (`api/` + `database/`) behind `/#/admin`.

> **Note:** Your original PHP & MySQL codebase remains untouched on `main` in Git history.

## Architecture Highlights

- **Public Portfolio (`/`):** Clean, recruiter-friendly presentation with zero public login/register clutter, Light & Dark mode, active scroll-spy navigation, categorized skills, filterable project showcase + modals, and graceful static fallback (works seamlessly on GitHub Pages / Vercel / Netlify even without PHP running).
- **Owner-Only Admin CMS (`/#/admin`):** Discreetly accessible via the footer `Admin` link or `/#/admin` URL. Manage Profile/About, Skills CRUD, Projects CRUD, Contact Inquiries inbox, Activity Logs, portrait uploads, and owner password updates.
- **Hardened PHP + MySQL Backend (`api/`):**
  - 100% native **PDO prepared statements** (`ATTR_EMULATE_PREPARES => false`) — eliminates SQL injection.
  - **Owner-only authentication** with `password_hash` / `password_verify`, `session_regenerate_id(true)`, and `HttpOnly` + `SameSite=Lax` cookies.
  - **CSRF token verification** (`X-CSRF-Token` header) on all state-changing requests (`POST`, `PUT`, `PATCH`, `DELETE`).
  - **Safe image uploads** (`api/upload.php`) using `finfo` MIME validation, 3 MB size cap, cryptographic filenames, and `.htaccess` script execution blocking.
  - **Local Preview Mode:** When PHP/MySQL isn't running locally, `/#/admin` offers an instant browser preview mode so you can test the entire CMS UI anytime.

## Local Frontend Development

```bash
npm install
npm run dev
```

Open `http://localhost:5173/` for the public portfolio and `http://localhost:5173/#/admin` for the owner CMS.

## Connecting the PHP + MySQL Backend

1. **Import the schema** into MySQL / MariaDB (e.g., via phpMyAdmin or CLI):
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. **Configure credentials:** Copy `api/.env.example` to `api/.env` and fill in your MySQL `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS`.
3. **Seed your owner account & initial data** (CLI-only for security):
   ```bash
   php database/seed.php "ChooseAStrongPassword123!"
   ```
4. **Run PHP locally** (either via XAMPP/Apache or PHP's built-in server):
   ```bash
   php -S 127.0.0.1:8000
   ```
   Vite automatically proxies `/api/*` to `http://127.0.0.1:8000` during `npm run dev` (override with `VITE_API_PROXY_TARGET` if hosting under XAMPP like `http://localhost/my_portfolio`).

## Deploying

### Static Portfolio (GitHub Pages / Vercel / Netlify)
- Includes `.github/workflows/deploy.yml` for automatic GitHub Pages publishing on `main`.
- Build command: `npm run build` · Output directory: `dist`.

### Full-Stack Hosting (PHP + MySQL Host)
1. Run `npm run build`.
2. Deploy `dist/` contents alongside `api/` (with your production `api/.env`) to your PHP/MySQL web root, and run `database/schema.sql` + `php database/seed.php`.
