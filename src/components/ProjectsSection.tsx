import { useEffect, useState } from 'react';
import { ArrowUpRight, CheckCircle2, ExternalLink, Github, Sparkles, X } from 'lucide-react';
import {
  PROJECT_CATEGORIES,
  type ProjectCategory,
  type ProjectItem,
} from '../data/portfolio';
import { resolveAssetUrl, type DynamicProject } from '../lib/api';
import { ProjectVisual } from './ProjectVisual';

const STATUS_BADGES: Record<ProjectItem['status'], string> = {
  Live: 'bg-cocoa-800 text-cream-50 border-cocoa-800 dark:bg-cocoa-500/25 dark:text-cream-100 dark:border-cocoa-400/40',
  Completed:
    'bg-cocoa-100 text-cocoa-800 border-cocoa-200 dark:bg-cocoa-800/80 dark:text-cocoa-200 dark:border-cocoa-700',
  'In Progress':
    'bg-cream-100 text-cocoa-700 border-cream-300 dark:bg-cocoa-900 dark:text-cocoa-300 dark:border-cocoa-700',
};

interface ProjectsSectionProps {
  projects: DynamicProject[];
}

export function ProjectsSection({ projects }: ProjectsSectionProps) {
  const [selectedCategory, setSelectedCategory] = useState<ProjectCategory>('All');
  const [activeProject, setActiveProject] = useState<DynamicProject | null>(null);

  const filteredProjects =
    selectedCategory === 'All'
      ? projects
      : projects.filter((project) => project.category === selectedCategory);

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

  function renderProjectPreview(project: DynamicProject) {
    if (project.imageUrl) {
      return (
        <div className="h-52 w-full overflow-hidden bg-cocoa-900">
          <img
            src={resolveAssetUrl(project.imageUrl)}
            alt={project.title}
            className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
          />
        </div>
      );
    }
    return <ProjectVisual type={project.visualType} />;
  }

  return (
    <section id="projects" className="py-20 sm:py-24">
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col justify-between gap-6 md:flex-row md:items-end">
          <div className="max-w-2xl">
            <p className="font-mono text-xs font-semibold uppercase tracking-widest text-cocoa-600 dark:text-cocoa-300">
              03 · Selected Projects
            </p>
            <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-cocoa-950 sm:text-4xl dark:text-white">
              Things I&apos;ve built &amp; engineered.
            </h2>
            <p className="mt-3 text-base text-cocoa-700/85 dark:text-cream-100/75">
              Filter by category or open any card for architecture highlights and links.
            </p>
          </div>

          <div className="flex flex-wrap gap-2" role="group" aria-label="Filter projects by category">
            {PROJECT_CATEGORIES.map((category) => {
              const count =
                category === 'All'
                  ? projects.length
                  : projects.filter((project) => project.category === category).length;
              const isActive = selectedCategory === category;

              return (
                <button
                  key={category}
                  type="button"
                  onClick={() => setSelectedCategory(category)}
                  className={`inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-xs font-semibold transition-all sm:text-sm ${
                    isActive
                      ? 'border-cocoa-700 bg-cocoa-700 text-white shadow-xs dark:border-cocoa-500 dark:bg-cocoa-500'
                      : 'border-cream-200 bg-white text-cocoa-700 hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200 dark:hover:border-cocoa-600 dark:hover:text-white'
                  }`}
                >
                  <span>{category}</span>
                  <span
                    className={`rounded-full px-1.5 py-0.5 font-mono text-[10px] ${
                      isActive
                        ? 'bg-white/20 text-white'
                        : 'bg-cream-100 text-cocoa-600 dark:bg-cocoa-800 dark:text-cocoa-300'
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
              className="group flex flex-col overflow-hidden rounded-3xl border border-cream-200 bg-white shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-cocoa-300 hover:shadow-xl hover:shadow-cocoa-950/8 dark:border-cocoa-800 dark:bg-cocoa-900/85 dark:hover:border-cocoa-600"
            >
              <div className="relative overflow-hidden border-b border-cream-200/90 dark:border-cocoa-800">
                {renderProjectPreview(project)}
                {project.featured && (
                  <span className="absolute left-4 top-4 inline-flex items-center gap-1 rounded-full border border-cocoa-300/30 bg-cocoa-950/80 px-2.5 py-1 font-mono text-[10px] font-semibold text-cocoa-200 backdrop-blur-md">
                    <Sparkles className="h-3 w-3" />
                    <span>Featured</span>
                  </span>
                )}
              </div>

              <div className="flex flex-1 flex-col p-6">
                <div className="flex flex-wrap items-center justify-between gap-2">
                  <span className="font-mono text-xs font-medium text-cocoa-500 dark:text-cocoa-300">
                    {project.category} · {project.year}
                  </span>
                  <span
                    className={`rounded-full border px-2.5 py-0.5 font-mono text-[11px] font-semibold ${STATUS_BADGES[project.status]}`}
                  >
                    {project.status}
                  </span>
                </div>

                <h3 className="mt-3 font-display text-xl font-bold text-cocoa-950 dark:text-white">
                  {project.title}
                </h3>
                <p className="mt-0.5 text-sm font-medium text-cocoa-600 dark:text-cocoa-300">
                  {project.subtitle}
                </p>

                <p className="mt-3 flex-1 text-sm leading-relaxed text-cocoa-800/80 dark:text-cream-100/80">
                  {project.summary}
                </p>

                <div className="mt-5 flex flex-wrap gap-1.5">
                  {project.tech.map((tech) => (
                    <span
                      key={tech}
                      className="rounded-md bg-cream-100 px-2.5 py-1 font-mono text-[11px] font-medium text-cocoa-800 dark:bg-cocoa-800 dark:text-cocoa-200"
                    >
                      {tech}
                    </span>
                  ))}
                </div>

                <div className="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-cream-200/80 pt-4 dark:border-cocoa-800">
                  <button
                    type="button"
                    onClick={() => setActiveProject(project)}
                    className="inline-flex items-center gap-1.5 text-sm font-semibold text-cocoa-900 transition-colors hover:text-cocoa-600 dark:text-white dark:hover:text-cocoa-300"
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
                        className="inline-flex items-center gap-1.5 rounded-lg bg-cocoa-700 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-cocoa-800 dark:bg-cocoa-500 dark:hover:bg-cocoa-400"
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
                        className="inline-flex items-center gap-1.5 rounded-lg border border-cream-200 px-3 py-1.5 text-xs font-semibold text-cocoa-800 transition-colors hover:border-cocoa-300 hover:bg-cream-50 dark:border-cocoa-700 dark:text-cream-100 dark:hover:bg-cocoa-800"
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
          className="fixed inset-0 z-50 flex items-center justify-center bg-cocoa-950/70 p-4 backdrop-blur-xs"
          onClick={() => setActiveProject(null)}
        >
          <div
            className="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-3xl border border-cream-200 bg-white shadow-2xl dark:border-cocoa-800 dark:bg-cocoa-900"
            onClick={(event) => event.stopPropagation()}
          >
            <div className="relative">
              {renderProjectPreview(activeProject)}
              <button
                type="button"
                onClick={() => setActiveProject(null)}
                aria-label="Close project details"
                className="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full bg-cocoa-950/75 text-white backdrop-blur-md transition-colors hover:bg-cocoa-900"
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
                <span className="font-mono text-xs text-cocoa-500 dark:text-cocoa-300">
                  {activeProject.role} · {activeProject.year}
                </span>
              </div>

              <h3
                id="project-modal-title"
                className="mt-3 font-display text-2xl font-bold text-cocoa-950 dark:text-white"
              >
                {activeProject.title}
              </h3>
              <p className="mt-1 text-sm font-medium text-cocoa-600 dark:text-cocoa-300">
                {activeProject.subtitle}
              </p>

              <p className="mt-4 text-sm leading-relaxed text-cocoa-800/85 sm:text-base dark:text-cream-100/80">
                {activeProject.summary}
              </p>

              {activeProject.highlights.length > 0 && (
                <div className="mt-6">
                  <h4 className="font-mono text-xs font-semibold uppercase tracking-wider text-cocoa-500 dark:text-cocoa-300">
                    Key Highlights
                  </h4>
                  <ul className="mt-3 space-y-2.5">
                    {activeProject.highlights.map((item) => (
                      <li
                        key={item}
                        className="flex items-start gap-2.5 text-sm text-cocoa-800 dark:text-cream-100"
                      >
                        <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-cocoa-600 dark:text-cocoa-300" />
                        <span>{item}</span>
                      </li>
                    ))}
                  </ul>
                </div>
              )}

              <div className="mt-6 flex flex-wrap gap-2">
                {activeProject.tech.map((tech) => (
                  <span
                    key={tech}
                    className="rounded-lg border border-cream-200 bg-cream-50 px-2.5 py-1 font-mono text-xs font-medium text-cocoa-800 dark:border-cocoa-700 dark:bg-cocoa-800 dark:text-cream-100"
                  >
                    {tech}
                  </span>
                ))}
              </div>

              <div className="mt-8 flex flex-wrap items-center gap-3 border-t border-cream-200/80 pt-5 dark:border-cocoa-800">
                {activeProject.liveUrl && (
                  <a
                    href={activeProject.liveUrl}
                    target="_blank"
                    rel="noreferrer"
                    className="inline-flex items-center gap-2 rounded-xl bg-cocoa-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-cocoa-800 dark:bg-cocoa-500 dark:hover:bg-cocoa-400"
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
                    className="inline-flex items-center gap-2 rounded-xl border border-cocoa-200 px-4 py-2.5 text-sm font-semibold text-cocoa-900 transition-colors hover:bg-cream-50 dark:border-cocoa-700 dark:text-cream-50 dark:hover:bg-cocoa-800"
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
