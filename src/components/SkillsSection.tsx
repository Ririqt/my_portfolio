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
    'bg-cocoa-800 text-cream-50 border-cocoa-800 dark:bg-cocoa-500/25 dark:text-cocoa-200 dark:border-cocoa-500/50',
  Comfortable:
    'bg-cocoa-100 text-cocoa-800 border-cocoa-200 dark:bg-cocoa-800/80 dark:text-cocoa-200 dark:border-cocoa-700',
  Foundational:
    'bg-cream-100 text-cocoa-700 border-cream-200 dark:bg-cocoa-900 dark:text-cocoa-300 dark:border-cocoa-800',
};

export function SkillsSection() {
  const [activeId, setActiveId] = useState<SkillCategory['id']>('languages');
  const activeCategory =
    SKILL_CATEGORIES.find((cat) => cat.id === activeId) ?? SKILL_CATEGORIES[0];

  return (
    <section
      id="skills"
      className="border-y border-cream-200/90 bg-white py-20 sm:py-24 dark:border-cocoa-800/80 dark:bg-cocoa-900/45"
    >
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col justify-between gap-6 md:flex-row md:items-end">
          <div className="max-w-2xl">
            <p className="font-mono text-xs font-semibold uppercase tracking-widest text-cocoa-600 dark:text-cocoa-300">
              02 · Technical Skills
            </p>
            <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-cocoa-950 sm:text-4xl dark:text-white">
              Toolkit across web, software &amp; hardware.
            </h2>
            <p className="mt-3 text-base text-cocoa-700/85 dark:text-cream-100/75">
              {activeCategory.description}
            </p>
          </div>

          <div
            role="tablist"
            aria-label="Skill categories"
            className="inline-flex flex-wrap gap-1.5 rounded-2xl border border-cream-200 bg-cream-100/90 p-1.5 dark:border-cocoa-800 dark:bg-cocoa-950"
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
                      ? 'bg-white text-cocoa-900 shadow-xs dark:bg-cocoa-700 dark:text-white'
                      : 'text-cocoa-600 hover:text-cocoa-950 dark:text-cocoa-300 dark:hover:text-white'
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
              className="rounded-2xl border border-cream-200 bg-cream-50/75 p-5 transition-all hover:border-cocoa-300 hover:bg-white hover:shadow-sm dark:border-cocoa-800 dark:bg-cocoa-900/85 dark:hover:border-cocoa-600"
            >
              <div className="flex items-start justify-between gap-2">
                <h3 className="font-display text-base font-bold text-cocoa-950 dark:text-white">
                  {skill.name}
                </h3>
                <span
                  className={`shrink-0 rounded-full border px-2.5 py-0.5 font-mono text-[11px] font-medium ${LEVEL_STYLES[skill.level]}`}
                >
                  {skill.level}
                </span>
              </div>

              <p className="mt-2 text-xs leading-relaxed text-cocoa-700/80 dark:text-cocoa-200/80">
                {skill.note}
              </p>

              <div className="mt-4">
                <div
                  className="h-2 w-full overflow-hidden rounded-full bg-cream-200 dark:bg-cocoa-800"
                  role="progressbar"
                  aria-label={`${skill.name} proficiency`}
                  aria-valuenow={skill.percent}
                  aria-valuemin={0}
                  aria-valuemax={100}
                >
                  <div
                    className="h-full rounded-full bg-gradient-to-r from-cocoa-700 to-cocoa-400 transition-all duration-500 dark:from-cocoa-400 dark:to-cocoa-200"
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
