# Mharc Reinald Rigos — Developer Portfolio

A fast, responsive, modern static portfolio built with **Vite**, **React**, **TypeScript**, and **Tailwind CSS** — designed for free one-click hosting on **GitHub Pages**, **Vercel**, or **Netlify**.

> **Note:** The original PHP & MySQL portfolio CMS remains preserved in Git history on the `main` branch prior to this modernization.

## Highlights

- **Responsive Modern UI/UX:** Clean editorial engineering layout with Light & Dark theme toggle, mobile navigation drawer, active scroll-spy navigation, and accessible dialogs.
- **Data-Driven Content:** Update your bio, skills, projects, experience, certifications, and social links in a single file: `src/data/portfolio.ts`.
- **Interactive Portfolio Features:** Working project category filters, rich project detail modals, categorized skills tabs, one-click email copy, and a validated contact form (with optional Formspree support).
- **Lightweight Build:** Removed 15+ MB of unused fonts, test uploads, and backup files from the repo.
- **Automated Deployment:** Includes `.github/workflows/deploy.yml` for GitHub Pages deployment.

## Local Development

```bash
npm install
npm run dev
```

## Production Build & Preview

```bash
npm run build
npm run preview
```

## Customizing Your Content

Edit [`src/data/portfolio.ts`](src/data/portfolio.ts) to update:

- **Profile & Contact Info:** `PROFILE` (optionally set `formspreeEndpoint` for serverless contact form submissions)
- **Skills:** `SKILL_CATEGORIES`
- **Projects:** `PROJECTS`
- **Experience, Organizations & Certifications:** `EXPERIENCE`, `ORGANIZATIONS`, `CERTIFICATIONS`

To update your resume or portrait photo, replace `public/resume.pdf` or `public/images/rei-portrait.jpg`.

## Deploying

### GitHub Pages
1. Merge this branch into `main` and push to GitHub.
2. In your GitHub repo, open **Settings → Pages → Build and deployment → Source** and choose **GitHub Actions**.
3. Every push to `main` automatically runs `.github/workflows/deploy.yml` and publishes to `https://<username>.github.io/<repo>/`.

### Vercel or Netlify
Import the GitHub repository directly:
- **Build command:** `npm run build`
- **Output directory:** `dist`
