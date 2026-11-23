import { useEffect, useState } from 'react';
import { Download, Menu, Moon, Sun, X } from 'lucide-react';
import { PROFILE } from '../data/portfolio';

const NAV_ITEMS = [
  { id: 'about', label: 'About' },
  { id: 'skills', label: 'Skills' },
  { id: 'projects', label: 'Projects' },
  { id: 'experience', label: 'Experience' },
  { id: 'contact', label: 'Contact' },
] as const;

interface NavbarProps {
  isDark: boolean;
  onToggleTheme: () => void;
}

export function Navbar({ isDark, onToggleTheme }: NavbarProps) {
  const [mobileOpen, setMobileOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [activeSection, setActiveSection] = useState<string>('');

  const resumeHref = `${import.meta.env.BASE_URL}${PROFILE.resumeFile}`;

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 16);
    };
    handleScroll();
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    const sections = NAV_ITEMS.map((item) => document.getElementById(item.id)).filter(
      (el): el is HTMLElement => el !== null,
    );

    if (sections.length === 0) return;

    const observer = new IntersectionObserver(
      (entries) => {
        const visible = entries
          .filter((entry) => entry.isIntersecting)
          .sort((a, b) => b.intersectionRatio - a.intersectionRatio);
        if (visible[0]?.target.id) {
          setActiveSection(visible[0].target.id);
        }
      },
      { rootMargin: '-25% 0px -55% 0px', threshold: [0.1, 0.25, 0.5] },
    );

    sections.forEach((section) => observer.observe(section));
    return () => observer.disconnect();
  }, []);

  useEffect(() => {
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === 'Escape') {
        setMobileOpen(false);
      }
    };
    window.addEventListener('keydown', onKeyDown);
    return () => window.removeEventListener('keydown', onKeyDown);
  }, []);

  return (
    <header
      className={`fixed inset-x-0 top-0 z-50 transition-all duration-300 ${
        scrolled
          ? 'border-b border-cream-200/90 bg-white/90 shadow-xs backdrop-blur-md dark:border-cocoa-800/90 dark:bg-cocoa-950/90'
          : 'bg-transparent'
      }`}
    >
      <div className="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a
          href="#top"
          className="group inline-flex items-center gap-2.5 font-display text-lg font-bold tracking-tight text-cocoa-950 dark:text-cream-50"
        >
          <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-cocoa-900 text-sm font-extrabold text-cream-50 transition-transform duration-200 group-hover:scale-105 dark:bg-cocoa-600">
            R<span className="text-cocoa-300 dark:text-cream-100">.</span>
          </span>
          <span className="hidden sm:inline">
            {PROFILE.nickname}{' '}
            <span className="font-medium text-cocoa-500 dark:text-cocoa-300">Rigos</span>
          </span>
        </a>

        <nav aria-label="Primary" className="hidden items-center gap-1 md:flex">
          {NAV_ITEMS.map((item) => {
            const isActive = activeSection === item.id;
            return (
              <a
                key={item.id}
                href={`#${item.id}`}
                className={`rounded-lg px-3.5 py-2 text-sm font-medium transition-colors ${
                  isActive
                    ? 'bg-cocoa-100 text-cocoa-800 dark:bg-cocoa-800/80 dark:text-cocoa-200'
                    : 'text-cocoa-700 hover:bg-cream-100 hover:text-cocoa-950 dark:text-cocoa-200 dark:hover:bg-cocoa-900 dark:hover:text-white'
                }`}
              >
                {item.label}
              </a>
            );
          })}
        </nav>

        <div className="flex items-center gap-2">
          <button
            type="button"
            onClick={onToggleTheme}
            aria-label={isDark ? 'Switch to light theme' : 'Switch to dark theme'}
            className="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-cream-200 bg-white text-cocoa-700 transition-colors hover:border-cocoa-300 hover:bg-cream-100 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200 dark:hover:border-cocoa-700 dark:hover:bg-cocoa-800"
          >
            {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
          </button>

          <a
            href={resumeHref}
            download="Mharc_Reinald_Rigos_Resume.pdf"
            className="hidden items-center gap-1.5 rounded-lg bg-cocoa-800 px-3.5 py-2 text-sm font-semibold text-white transition-all hover:bg-cocoa-700 sm:inline-flex dark:bg-cocoa-500 dark:hover:bg-cocoa-400 dark:text-white"
          >
            <Download className="h-4 w-4" />
            <span>Resume</span>
          </a>

          <button
            type="button"
            onClick={() => setMobileOpen((prev) => !prev)}
            aria-expanded={mobileOpen}
            aria-label="Toggle navigation menu"
            className="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-cream-200 bg-white text-cocoa-800 md:hidden dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200"
          >
            {mobileOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
          </button>
        </div>
      </div>

      {mobileOpen && (
        <div className="border-b border-cream-200 bg-white/95 px-4 pb-5 pt-2 shadow-lg backdrop-blur-md md:hidden dark:border-cocoa-800 dark:bg-cocoa-950/95">
          <nav aria-label="Mobile" className="flex flex-col gap-1">
            {NAV_ITEMS.map((item) => (
              <a
                key={item.id}
                href={`#${item.id}`}
                onClick={() => setMobileOpen(false)}
                className={`rounded-lg px-3 py-2.5 text-sm font-medium transition-colors ${
                  activeSection === item.id
                    ? 'bg-cocoa-100 text-cocoa-800 dark:bg-cocoa-800/80 dark:text-cocoa-200'
                    : 'text-cocoa-800 hover:bg-cream-100 dark:text-cream-100 dark:hover:bg-cocoa-900'
                }`}
              >
                {item.label}
              </a>
            ))}
            <a
              href={resumeHref}
              download="Mharc_Reinald_Rigos_Resume.pdf"
              onClick={() => setMobileOpen(false)}
              className="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-cocoa-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-cocoa-800"
            >
              <Download className="h-4 w-4" />
              <span>Download Resume</span>
            </a>
          </nav>
        </div>
      )}
    </header>
  );
}
