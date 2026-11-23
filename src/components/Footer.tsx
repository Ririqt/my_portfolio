import { ArrowUp, Github, Linkedin, Lock, Mail } from 'lucide-react';
import type { PortfolioProfileData } from '../lib/api';

interface FooterProps {
  profile: PortfolioProfileData;
  onOpenAdmin: () => void;
}

export function Footer({ profile, onOpenAdmin }: FooterProps) {
  const year = new Date().getFullYear();

  return (
    <footer className="border-t border-cream-200/90 bg-white py-10 dark:border-cocoa-800/80 dark:bg-cocoa-950">
      <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-6 px-4 sm:flex-row sm:px-6 lg:px-8">
        <div className="text-center sm:text-left">
          <p className="font-display text-sm font-bold text-cocoa-950 dark:text-white">
            {profile.fullName}
          </p>
          <p className="mt-1 text-xs text-cocoa-600 dark:text-cocoa-300">
            © {year} {profile.fullName}. Built with Vite, React, TypeScript &amp; PHP/MySQL CMS.
          </p>
        </div>

        <div className="flex flex-wrap items-center justify-center gap-2">
          {profile.socials.github && (
            <a
              href={profile.socials.github}
              target="_blank"
              rel="noreferrer"
              aria-label="GitHub"
              className="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-cream-200 text-cocoa-700 transition-colors hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-800 dark:text-cocoa-300 dark:hover:border-cocoa-500 dark:hover:text-white"
            >
              <Github className="h-4 w-4" />
            </a>
          )}
          {profile.socials.linkedin && (
            <a
              href={profile.socials.linkedin}
              target="_blank"
              rel="noreferrer"
              aria-label="LinkedIn"
              className="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-cream-200 text-cocoa-700 transition-colors hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-800 dark:text-cocoa-300 dark:hover:border-cocoa-500 dark:hover:text-white"
            >
              <Linkedin className="h-4 w-4" />
            </a>
          )}
          <a
            href={`mailto:${profile.email}`}
            aria-label="Email"
            className="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-cream-200 text-cocoa-700 transition-colors hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-800 dark:text-cocoa-300 dark:hover:border-cocoa-500 dark:hover:text-white"
          >
            <Mail className="h-4 w-4" />
          </a>
          <button
            type="button"
            onClick={onOpenAdmin}
            title="Owner Admin CMS"
            className="inline-flex items-center gap-1.5 rounded-lg border border-cream-200 px-2.5 py-2 text-xs font-medium text-cocoa-500 transition-colors hover:border-cocoa-400 hover:text-cocoa-900 dark:border-cocoa-800 dark:text-cocoa-400 dark:hover:border-cocoa-500 dark:hover:text-cream-100"
          >
            <Lock className="h-3.5 w-3.5" />
            <span>Admin</span>
          </button>
          <a
            href="#top"
            aria-label="Back to top"
            className="inline-flex items-center gap-1.5 rounded-lg border border-cream-200 px-3 py-2 text-xs font-semibold text-cocoa-800 transition-colors hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-800 dark:text-cocoa-200 dark:hover:border-cocoa-500 dark:hover:text-white"
          >
            <ArrowUp className="h-3.5 w-3.5" />
            <span>Top</span>
          </a>
        </div>
      </div>
    </footer>
  );
}
