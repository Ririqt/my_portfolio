import { Award, Briefcase, CheckCircle2, Users } from 'lucide-react';
import { CERTIFICATIONS, EXPERIENCE, ORGANIZATIONS } from '../data/portfolio';

export function ExperienceSection() {
  return (
    <section
      id="experience"
      className="border-y border-slate-200/80 bg-white py-20 sm:py-24 dark:border-slate-800/80 dark:bg-slate-900/40"
    >
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="max-w-2xl">
          <p className="font-mono text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">
            04 · Experience &amp; Community
          </p>
          <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
            Hands-on QA experience, tech orgs &amp; continuous learning.
          </h2>
        </div>

        <div className="mt-10 grid gap-6 lg:grid-cols-12">
          <div className="space-y-6 lg:col-span-7">
            <div className="rounded-3xl border border-slate-200 bg-slate-50/70 p-6 sm:p-8 dark:border-slate-800 dark:bg-slate-900/80">
              <div className="flex items-center gap-2.5 text-blue-600 dark:text-blue-400">
                <Briefcase className="h-5 w-5" />
                <h3 className="font-mono text-xs font-semibold uppercase tracking-wider">
                  Work Experience
                </h3>
              </div>

              <div className="mt-6 space-y-6">
                {EXPERIENCE.map((item) => (
                  <div key={`${item.role}-${item.organization}`}>
                    <div className="flex flex-wrap items-start justify-between gap-2">
                      <div>
                        <h4 className="font-display text-xl font-bold text-slate-900 dark:text-white">
                          {item.role}
                        </h4>
                        <p className="mt-0.5 text-sm font-medium text-blue-600 dark:text-blue-400">
                          {item.organization}
                        </p>
                      </div>
                      <span className="rounded-full border border-slate-200 bg-white px-3 py-1 font-mono text-xs font-medium text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                        {item.type} · {item.period}
                      </span>
                    </div>

                    <p className="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                      {item.summary}
                    </p>

                    <ul className="mt-4 space-y-2.5">
                      {item.bullets.map((bullet) => (
                        <li
                          key={bullet}
                          className="flex items-start gap-2.5 text-sm text-slate-700 dark:text-slate-200"
                        >
                          <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-blue-600 dark:text-blue-400" />
                          <span>{bullet}</span>
                        </li>
                      ))}
                    </ul>
                  </div>
                ))}
              </div>
            </div>

            <div className="rounded-3xl border border-slate-200 bg-slate-50/70 p-6 sm:p-8 dark:border-slate-800 dark:bg-slate-900/80">
              <div className="flex items-center gap-2.5 text-blue-600 dark:text-blue-400">
                <Award className="h-5 w-5" />
                <h3 className="font-mono text-xs font-semibold uppercase tracking-wider">
                  Certifications &amp; Seminars
                </h3>
              </div>

              <div className="mt-5 divide-y divide-slate-200/80 dark:divide-slate-800">
                {CERTIFICATIONS.map((cert) => (
                  <div
                    key={cert.title}
                    className="flex flex-col justify-between gap-2 py-4 first:pt-0 last:pb-0 sm:flex-row sm:items-center"
                  >
                    <div>
                      <h4 className="font-display text-sm font-bold text-slate-900 sm:text-base dark:text-white">
                        {cert.title}
                      </h4>
                      <p className="mt-0.5 text-xs text-slate-600 dark:text-slate-400">
                        {cert.issuer} · {cert.date}
                      </p>
                    </div>
                    <span className="w-fit shrink-0 rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1 font-mono text-[11px] font-medium text-blue-700 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300">
                      {cert.topic}
                    </span>
                  </div>
                ))}
              </div>
            </div>
          </div>

          <div className="rounded-3xl border border-slate-200 bg-slate-50/70 p-6 sm:p-8 lg:col-span-5 dark:border-slate-800 dark:bg-slate-900/80">
            <div className="flex items-center gap-2.5 text-blue-600 dark:text-blue-400">
              <Users className="h-5 w-5" />
              <h3 className="font-mono text-xs font-semibold uppercase tracking-wider">
                Student Organizations
              </h3>
            </div>

            <div className="mt-6 space-y-4">
              {ORGANIZATIONS.map((org) => (
                <div
                  key={org.name}
                  className="rounded-2xl border border-slate-200 bg-white p-4 transition-colors hover:border-blue-300 dark:border-slate-800 dark:bg-slate-950/70 dark:hover:border-blue-500/40"
                >
                  <div className="flex items-start gap-3.5">
                    <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-900 font-mono text-xs font-bold text-white dark:bg-blue-600">
                      {org.acronym}
                    </div>
                    <div className="min-w-0">
                      <h4 className="font-display text-sm font-bold leading-snug text-slate-900 sm:text-base dark:text-white">
                        {org.name}
                      </h4>
                      <p className="mt-0.5 font-mono text-[11px] font-medium text-blue-600 dark:text-blue-400">
                        {org.period}
                      </p>
                      <p className="mt-1.5 text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {org.focus}
                      </p>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
