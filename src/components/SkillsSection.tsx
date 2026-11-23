import { useState } from 'react';
import { Code2, Cpu, Wrench } from 'lucide-react';
import { SKILL_CATEGORIES, type SkillCategory, type SkillItem } from '../data/portfolio';

const CATEGORY_ICONS = {
  languages: Code2,
  tools: Wrench,
  systems: Cpu,
} as const;

const LEVEL_STYLES: Record<SkillItem['level'], string> = {
  Proficient:
    'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/30',
  Comfortable:
    'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-500/30',
  Foundational:
    'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
};

export function SkillsSection() {
  const [activeId, setActiveId] = useState<SkillCategory['id']>('languages');
  const activeCategory =
    SKILL_CATEGORIES.find((cat) => cat.id === activeId) ?? SKILL_CATEGORIES[0];

  return (
    <section
      id="skills"
      className="border-y border-slate-200/80 bg-white py-20 sm:py-24 dark:border-slate-800/80 dark:bg-slate-900/40"
    >
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col justify-between gap-6 md:flex-row md:items-end">
          <div className="max-w-2xl">
            <p className="font-mono text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">
              02 · Technical Skills
            </p>
            <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
              Toolkit across web, software &amp; hardware.
            </h2>
            <p className="mt-3 text-base text-slate-600 dark:text-slate-300">
              {activeCategory.description}
            </p>
          </div>

          <div
            role="tablist"
            aria-label="Skill categories"
            className="inline-flex flex-wrap gap-1.5 rounded-2xl border border-slate-200 bg-slate-100/80 p-1.5 dark:border-slate-800 dark:bg-slate-900"
          >
            {SKILL_CATEGORIES.map((category) => {
              const Icon = CATEGORY_ICONS[category.id];
              const isSelected = category.id === activeCategory.id;
              return (
                <button
                  key={category.id}
                  type="button"
                  role="tab"
                  aria-selected={isSelected}
                  onClick={() => setActiveId(category.id)}
                  className={`inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all sm:text-sm ${
                    isSelected
                      ? 'bg-white text-slate-900 shadow-xs dark:bg-blue-600 dark:text-white'
                      : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'
                  }`}
                >
                  <Icon className="h-4 w-4" />
                  <span>{category.label}</span>
                </button>
              );
            })}
          </div>
        </div>

        <div className="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {activeCategory.items.map((skill) => (
            <div
              key={skill.name}
              className="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 transition-all hover:border-blue-300 hover:bg-white hover:shadow-sm dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-blue-500/40"
            >
              <div className="flex items-start justify-between gap-2">
                <h3 className="font-display text-base font-bold text-slate-900 dark:text-white">
                  {skill.name}
                </h3>
                <span
                  className={`shrink-0 rounded-full border px-2.5 py-0.5 font-mono text-[11px] font-medium ${LEVEL_STYLES[skill.level]}`}
                >
                  {skill.level}
                </span>
              </div>

              <p className="mt-2 text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                {skill.note}
              </p>

              <div className="mt-4">
                <div
                  className="h-2 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800"
                  role="progressbar"
                  aria-label={`${skill.name} proficiency`}
                  aria-valuenow={skill.percent}
                  aria-valuemin={0}
                  aria-valuemax={100}
                >
                  <div
                    className="h-full rounded-full bg-gradient-to-r from-blue-600 to-cyan-500 transition-all duration-500"
                    style={{ width: `${skill.percent}%` }}
                  />
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
