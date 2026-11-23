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
          <p className="font-mono text-xs font-semibold uppercase tracking-widest text-cocoa-600 dark:text-cocoa-300">
            01 · About Me
          </p>
          <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-cocoa-950 sm:text-4xl dark:text-white">
            Engineering fundamentals paired with thoughtful web craft.
          </h2>
        </div>

        <div className="mt-10 grid gap-8 lg:grid-cols-12">
          <div className="space-y-6 lg:col-span-7">
            <div className="space-y-4 text-base leading-relaxed text-cocoa-800/85 dark:text-cream-100/80">
              {PROFILE.aboutParagraphs.map((paragraph) => (
                <p key={paragraph}>{paragraph}</p>
              ))}
            </div>

            <div className="rounded-2xl border border-cream-200 bg-white p-6 shadow-xs dark:border-cocoa-800 dark:bg-cocoa-900/80">
              <div className="flex items-start gap-3.5">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cocoa-100 text-cocoa-700 dark:bg-cocoa-800 dark:text-cocoa-300">
                  <GraduationCap className="h-5 w-5" />
                </div>
                <div>
                  <h3 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
                    {PROFILE.degree}
                  </h3>
                  <p className="text-sm font-medium text-cocoa-700 dark:text-cocoa-200">
                    {PROFILE.university}
                  </p>
                  <p className="mt-1 font-mono text-xs text-cocoa-600 dark:text-cocoa-300">
                    {PROFILE.graduation}
                  </p>
                </div>
              </div>

              <div className="mt-5 border-t border-cream-200/80 pt-4 dark:border-cocoa-800">
                <p className="mb-2.5 font-mono text-xs font-semibold uppercase tracking-wider text-cocoa-500 dark:text-cocoa-300">
                  Relevant Coursework
                </p>
                <div className="flex flex-wrap gap-2">
                  {COURSEWORK.map((course) => (
                    <span
                      key={course}
                      className="rounded-lg border border-cream-200 bg-cream-50 px-2.5 py-1 text-xs font-medium text-cocoa-800 dark:border-cocoa-700/80 dark:bg-cocoa-800/70 dark:text-cream-100"
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
                  className="group rounded-2xl border border-cream-200 bg-white p-5 transition-all hover:border-cocoa-300 hover:shadow-md hover:shadow-cocoa-950/5 dark:border-cocoa-800 dark:bg-cocoa-900/80 dark:hover:border-cocoa-600"
                >
                  <div className="flex items-start gap-3.5">
                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cream-100 text-cocoa-700 transition-colors group-hover:bg-cocoa-700 group-hover:text-white dark:bg-cocoa-800 dark:text-cocoa-200 dark:group-hover:bg-cocoa-500 dark:group-hover:text-white">
                      <Icon className="h-5 w-5" />
                    </div>
                    <div>
                      <h3 className="font-display text-base font-bold text-cocoa-950 dark:text-white">
                        {pillar.title}
                      </h3>
                      <p className="mt-1 text-sm leading-relaxed text-cocoa-700/85 dark:text-cocoa-200/80">
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
