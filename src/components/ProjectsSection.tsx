import { useEffect, useState } from 'react';
import { ArrowUpRight, CheckCircle2, ExternalLink, Github, Sparkles, X } from 'lucide-react';
import {
  PROJECT_CATEGORIES,
  PROJECTS,
  type ProjectCategory,
  type ProjectItem,
} from '../data/portfolio';
import { ProjectVisual } from './ProjectVisual';

const STATUS_BADGES: Record<ProjectItem['status'], string> = {
  Live: 'bg-emerald-500/15 text-emerald-700 border-emerald-200 dark:text-emerald-300 dark:border-emerald-500/30',
  Completed:
    'bg-blue-500/15 text-blue-700 border-blue-200 dark:text-blue-300 dark:border-blue-500/30',
  'In Progress':
    'bg-amber-500/15 text-amber-800 border-amber-200 dark:text-amber-300 dark:border-amber-500/30',
};

export function ProjectsSection() {
  const [selectedCategory, setSelectedCategory] = useState<ProjectCategory>('All');
  const [activeProject, setActiveProject] = useState<ProjectItem | null>(null);

  const filteredProjects =
    selectedCategory === 'All'
      ? PROJECTS
      : PROJECTS.filter((project) => project.category === selectedCategory);

  useEffect(() => {
    if (!activeProject) return;
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === 'Escape') {
        setActiveProject(null);
      }
    };
    window.addEventListener('keydown', onKeyDown);
    return () => window.removeEventListener('keydown', onKeyDown);
  }, [activeProject]);

  return (
    <section id="projects" className="py-20 sm:py-24">
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col justify-between gap-6 md:flex-row md:items-end">
          <div className="max-w-2xl">
            <p className="font-mono text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">
              03 · Selected Projects
            </p>
            <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
              Things I&apos;ve built &amp; engineered.
            </h2>
            <p className="mt-3 text-base text-slate-600 dark:text-slate-300">
              Filter by category or open any card for architecture highlights and links.
            </p>
          </div>

          <div className="flex flex-wrap gap-2" role="group" aria-label="Filter projects by category">
            {PROJECT_CATEGORIES.map((category) => {
              const count =
                category === 'All'
                  ? PROJECTS.length
                  : PROJECTS.filter((project) => project.category === category).length;
              const isActive = selectedCategory === category;

              return (
                <button
                  key={category}
                  type="button"
                  onClick={() => setSelectedCategory(category)}
                  className={`inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-xs font-semibold transition-all sm:text-sm ${
                    isActive
                      ? 'border-blue-600 bg-blue-600 text-white shadow-xs'
                      : 'border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-500/40 dark:hover:text-blue-400'
                  }`}
                >
                  <span>{category}</span>
                  <span
                    className={`rounded-full px-1.5 py-0.5 font-mono text-[10px] ${
                      isActive
                        ? 'bg-white/20 text-white'
                        : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'
                    }`}
                  >
                    {count}
                  </span>
                </button>
              );
            })}
          </div>
        </div>

        <div className="mt-10 grid gap-6 md:grid-cols-2">
          {filteredProjects.map((project) => (
            <article
              key={project.id}
              className="group flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-xl hover:shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-blue-500/40"
            >
              <div className="relative overflow-hidden border-b border-slate-200/80 dark:border-slate-800">
                <ProjectVisual type={project.visualType} />
                {project.featured && (
                  <span className="absolute left-4 top-4 inline-flex items-center gap-1 rounded-full bg-slate-950/75 px-2.5 py-1 font-mono text-[10px] font-semibold text-amber-300 backdrop-blur-md">
                    <Sparkles className="h-3 w-3" />
                    <span>Featured</span>
                  </span>
                )}
              </div>

              <div className="flex flex-1 flex-col p-6">
                <div className="flex flex-wrap items-center justify-between gap-2">
                  <span className="font-mono text-xs font-medium text-slate-500 dark:text-slate-400">
                    {project.category} · {project.year}
                  </span>
                  <span
                    className={`rounded-full border px-2.5 py-0.5 font-mono text-[11px] font-semibold ${STATUS_BADGES[project.status]}`}
                  >
                    {project.status}
                  </span>
                </div>

                <h3 className="mt-3 font-display text-xl font-bold text-slate-900 dark:text-white">
                  {project.title}
                </h3>
                <p className="mt-0.5 text-sm font-medium text-blue-600 dark:text-blue-400">
                  {project.subtitle}
                </p>

                <p className="mt-3 flex-1 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                  {project.summary}
                </p>

                <div className="mt-5 flex flex-wrap gap-1.5">
                  {project.tech.map((tech) => (
                    <span
                      key={tech}
                      className="rounded-md bg-slate-100 px-2.5 py-1 font-mono text-[11px] font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                    >
                      {tech}
                    </span>
                  ))}
                </div>

                <div className="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                  <button
                    type="button"
                    onClick={() => setActiveProject(project)}
                    className="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-900 transition-colors hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                  >
                    <span>View details</span>
                    <ArrowUpRight className="h-4 w-4" />
                  </button>

                  <div className="flex items-center gap-2">
                    {project.liveUrl && (
                      <a
                        href={project.liveUrl}
                        target="_blank"
                        rel="noreferrer"
                        className="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-blue-700"
                      >
                        <ExternalLink className="h-3.5 w-3.5" />
                        <span>Live Demo</span>
                      </a>
                    )}
                    {project.repoUrl && (
                      <a
                        href={project.repoUrl}
                        target="_blank"
                        rel="noreferrer"
                        className="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                      >
                        <Github className="h-3.5 w-3.5" />
                        <span>GitHub</span>
                      </a>
                    )}
                  </div>
                </div>
              </div>
            </article>
          ))}
        </div>
      </div>

      {activeProject && (
        <div
          role="dialog"
          aria-modal="true"
          aria-labelledby="project-modal-title"
          className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/65 p-4 backdrop-blur-xs"
          onClick={() => setActiveProject(null)}
        >
          <div
            className="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-3xl border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900"
            onClick={(event) => event.stopPropagation()}
          >
            <div className="relative">
              <ProjectVisual type={activeProject.visualType} />
              <button
                type="button"
                onClick={() => setActiveProject(null)}
                aria-label="Close project details"
                className="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-950/75 text-white backdrop-blur-md transition-colors hover:bg-slate-900"
              >
                <X className="h-4 w-4" />
              </button>
            </div>

            <div className="p-6 sm:p-8">
              <div className="flex flex-wrap items-center gap-2">
                <span
                  className={`rounded-full border px-2.5 py-0.5 font-mono text-[11px] font-semibold ${STATUS_BADGES[activeProject.status]}`}
                >
                  {activeProject.status}
                </span>
                <span className="font-mono text-xs text-slate-500 dark:text-slate-400">
                  {activeProject.role} · {activeProject.year}
                </span>
              </div>

              <h3
                id="project-modal-title"
                className="mt-3 font-display text-2xl font-bold text-slate-900 dark:text-white"
              >
                {activeProject.title}
              </h3>
              <p className="mt-1 text-sm font-medium text-blue-600 dark:text-blue-400">
                {activeProject.subtitle}
              </p>

              <p className="mt-4 text-sm leading-relaxed text-slate-600 sm:text-base dark:text-slate-300">
                {activeProject.summary}
              </p>

              <div className="mt-6">
                <h4 className="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  Key Highlights
                </h4>
                <ul className="mt-3 space-y-2.5">
                  {activeProject.highlights.map((item) => (
                    <li
                      key={item}
                      className="flex items-start gap-2.5 text-sm text-slate-700 dark:text-slate-200"
                    >
                      <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-blue-600 dark:text-blue-400" />
                      <span>{item}</span>
                    </li>
                  ))}
                </ul>
              </div>

              <div className="mt-6 flex flex-wrap gap-2">
                {activeProject.tech.map((tech) => (
                  <span
                    key={tech}
                    className="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 font-mono text-xs font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                  >
                    {tech}
                  </span>
                ))}
              </div>

              <div className="mt-8 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
                {activeProject.liveUrl && (
                  <a
                    href={activeProject.liveUrl}
                    target="_blank"
                    rel="noreferrer"
                    className="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-blue-700"
                  >
                    <ExternalLink className="h-4 w-4" />
                    <span>Open Live Project</span>
                  </a>
                )}
                {activeProject.repoUrl && (
                  <a
                    href={activeProject.repoUrl}
                    target="_blank"
                    rel="noreferrer"
                    className="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-800 transition-colors hover:bg-slate-50 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800"
                  >
                    <Github className="h-4 w-4" />
                    <span>View on GitHub</span>
                  </a>
                )}
              </div>
            </div>
          </div>
        </div>
      )}
    </section>
  );
}
