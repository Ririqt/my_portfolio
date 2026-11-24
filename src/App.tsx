import { useCallback, useEffect, useState } from 'react';
import { AboutSection } from './components/AboutSection';
import { AdminPortal } from './components/admin/AdminPortal';
import { ContactSection } from './components/ContactSection';
import { ExperienceSection } from './components/ExperienceSection';
import { Footer } from './components/Footer';
import { Hero } from './components/Hero';
import { Navbar } from './components/Navbar';
import { ProjectsSection } from './components/ProjectsSection';
import { SkillsSection } from './components/SkillsSection';
import {
  fetchPublicPortfolio,
  getDefaultPortfolioSnapshot,
  groupSkillsByCategory,
  type BackendMode,
  type PortfolioSnapshot,
} from './lib/api';

function isAdminRoute(): boolean {
  if (typeof window === 'undefined') return false;
  return window.location.hash.replace(/^#/, '').startsWith('/admin');
}

export function App() {
  const [isDark, setIsDark] = useState<boolean>(() => {
    if (typeof document === 'undefined') return false;
    return document.documentElement.classList.contains('dark');
  });

  const [inAdmin, setInAdmin] = useState<boolean>(isAdminRoute);
  const [backendMode, setBackendMode] = useState<BackendMode>('preview');
  const [portfolio, setPortfolio] = useState<PortfolioSnapshot>(getDefaultPortfolioSnapshot);

  useEffect(() => {
    const root = document.documentElement;
    if (isDark) {
      root.classList.add('dark');
      localStorage.setItem('portfolio-theme', 'dark');
    } else {
      root.classList.remove('dark');
      localStorage.setItem('portfolio-theme', 'light');
    }
  }, [isDark]);

  useEffect(() => {
    const onHashChange = () => {
      setInAdmin(isAdminRoute());
    };
    window.addEventListener('hashchange', onHashChange);
    return () => window.removeEventListener('hashchange', onHashChange);
  }, []);

  const refreshPortfolio = useCallback(async () => {
    const { mode, snapshot } = await fetchPublicPortfolio();
    setBackendMode(mode);
    setPortfolio(snapshot);
  }, []);

  useEffect(() => {
    void refreshPortfolio();
  }, [refreshPortfolio]);

  function openAdmin() {
    window.location.hash = '#/admin';
    setInAdmin(true);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function backToPortfolio() {
    window.location.hash = '#top';
    setInAdmin(false);
    window.scrollTo({ top: 0, behavior: 'smooth' });
    void refreshPortfolio();
  }

  if (inAdmin) {
    return (
      <AdminPortal
        isDark={isDark}
        onToggleTheme={() => setIsDark((prev) => !prev)}
        onBackToPortfolio={backToPortfolio}
        onPortfolioChanged={() => {
          void refreshPortfolio();
        }}
      />
    );
  }

  const skillCategories = groupSkillsByCategory(portfolio.skills);

  return (
    <div className="min-h-screen bg-cream-50 text-cocoa-950 dark:bg-cocoa-950 dark:text-cream-50">
      <a
        href="#main-content"
        className="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-cocoa-700 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white"
      >
        Skip to main content
      </a>

      <Navbar
        profile={portfolio.profile}
        isDark={isDark}
        onToggleTheme={() => setIsDark((prev) => !prev)}
      />

      <main id="main-content">
        <Hero profile={portfolio.profile} />
        <AboutSection profile={portfolio.profile} />
        <SkillsSection categories={skillCategories} />
        <ProjectsSection projects={portfolio.projects} />
        <ExperienceSection />
        <ContactSection profile={portfolio.profile} backendMode={backendMode} />
      </main>

      <Footer profile={portfolio.profile} onOpenAdmin={openAdmin} />
    </div>
  );
}

export default App;
