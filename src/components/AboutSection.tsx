import { Cpu, GraduationCap, Layout, ShieldCheck, Terminal } from 'lucide-react';
import { COURSEWORK, PROFILE } from '../data/portfolio';

const FOCUS_PILLARS = [
  {
    icon: Layout,
    title: 'Responsive Web Development',
    description:
      'Building clean, accessible interfaces with semantic HTML, modern CSS, TypeScript, and React.',
  },
  {
    icon: Terminal,
    title: 'Algorithms & Problem Solving',
    description:
      'Applying Python, Java, and Data Structures & Algorithms to craft interactive educational tools.',
  },
  {
    icon: ShieldCheck,
    title: 'Quality Assurance & Testing',
    description:
      'Hands-on website QA experience verifying functionality, performance, links, and multi-device UX.',
  },
  {
    icon: Cpu,
    title: 'Computer Engineering Core',
    description:
      'Grounded in operating systems, computer hardware fundamentals, circuit analysis, and system setup.',
  },
] as const;

export function AboutSection() {
  return (
    <section id="about" className="py-20 sm:py-24">
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="max-w-2xl">
          <p className="font-mono text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">
            01 · About Me
          </p>
          <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
            Engineering fundamentals paired with thoughtful web craft.
          </h2>
        </div>

        <div className="mt-10 grid gap-8 lg:grid-cols-12">
          <div className="space-y-6 lg:col-span-7">
            <div className="space-y-4 text-base leading-relaxed text-slate-600 dark:text-slate-300">
              {PROFILE.aboutParagraphs.map((paragraph) => (
                <p key={paragraph}>{paragraph}</p>
              ))}
            </div>

            <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900/70">
              <div className="flex items-start gap-3.5">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                  <GraduationCap className="h-5 w-5" />
                </div>
                <div>
                  <h3 className="font-display text-lg font-bold text-slate-900 dark:text-white">
                    {PROFILE.degree}
                  </h3>
                  <p className="text-sm font-medium text-slate-600 dark:text-slate-300">
                    {PROFILE.university}
                  </p>
                  <p className="mt-1 font-mono text-xs text-blue-600 dark:text-blue-400">
                    {PROFILE.graduation}
                  </p>
                </div>
              </div>

              <div className="mt-5 border-t border-slate-100 pt-4 dark:border-slate-800">
                <p className="mb-2.5 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  Relevant Coursework
                </p>
                <div className="flex flex-wrap gap-2">
                  {COURSEWORK.map((course) => (
                    <span
                      key={course}
                      className="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-700 dark:border-slate-700/80 dark:bg-slate-800/70 dark:text-slate-300"
                    >
                      {course}
                    </span>
                  ))}
                </div>
              </div>
            </div>
          </div>

          <div className="grid gap-4 sm:grid-cols-2 lg:col-span-5 lg:grid-cols-1">
            {FOCUS_PILLARS.map((pillar) => {
              const Icon = pillar.icon;
              return (
                <div
                  key={pillar.title}
                  className="group rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:border-blue-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-blue-500/40"
                >
                  <div className="flex items-start gap-3.5">
                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-700 transition-colors group-hover:bg-blue-600 group-hover:text-white dark:bg-slate-800 dark:text-slate-200 dark:group-hover:bg-blue-500">
                      <Icon className="h-5 w-5" />
                    </div>
                    <div>
                      <h3 className="font-display text-base font-bold text-slate-900 dark:text-white">
                        {pillar.title}
                      </h3>
                      <p className="mt-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {pillar.description}
                      </p>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </section>
  );
}
