import { Award, Briefcase, CheckCircle2, Users } from 'lucide-react';
import { CERTIFICATIONS, EXPERIENCE, ORGANIZATIONS } from '../data/portfolio';

export function ExperienceSection() {
  return (
    <section
      id="experience"
      className="border-y border-cream-200/90 bg-white py-20 sm:py-24 dark:border-cocoa-800/80 dark:bg-cocoa-900/45"
    >
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="max-w-2xl">
          <p className="font-mono text-xs font-semibold uppercase tracking-widest text-cocoa-600 dark:text-cocoa-300">
            04 · Experience &amp; Community
          </p>
          <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-cocoa-950 sm:text-4xl dark:text-white">
            Hands-on QA experience, tech orgs &amp; continuous learning.
          </h2>
        </div>

        <div className="mt-10 grid gap-6 lg:grid-cols-12">
          <div className="space-y-6 lg:col-span-7">
            <div className="rounded-3xl border border-cream-200 bg-cream-50/75 p-6 sm:p-8 dark:border-cocoa-800 dark:bg-cocoa-900/85">
              <div className="flex items-center gap-2.5 text-cocoa-600 dark:text-cocoa-300">
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
                        <h4 className="font-display text-xl font-bold text-cocoa-950 dark:text-white">
                          {item.role}
                        </h4>
                        <p className="mt-0.5 text-sm font-medium text-cocoa-600 dark:text-cocoa-300">
                          {item.organization}
                        </p>
                      </div>
                      <span className="rounded-full border border-cream-200 bg-white px-3 py-1 font-mono text-xs font-medium text-cocoa-700 dark:border-cocoa-700 dark:bg-cocoa-800 dark:text-cocoa-200">
                        {item.type} · {item.period}
                      </span>
                    </div>

                    <p className="mt-3 text-sm leading-relaxed text-cocoa-800/85 dark:text-cream-100/80">
                      {item.summary}
                    </p>

                    <ul className="mt-4 space-y-2.5">
                      {item.bullets.map((bullet) => (
                        <li
                          key={bullet}
                          className="flex items-start gap-2.5 text-sm text-cocoa-800 dark:text-cream-100"
                        >
                          <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-cocoa-600 dark:text-cocoa-300" />
                          <span>{bullet}</span>
                        </li>
                      ))}
                    </ul>
                  </div>
                ))}
              </div>
            </div>

            <div className="rounded-3xl border border-cream-200 bg-cream-50/75 p-6 sm:p-8 dark:border-cocoa-800 dark:bg-cocoa-900/85">
              <div className="flex items-center gap-2.5 text-cocoa-600 dark:text-cocoa-300">
                <Award className="h-5 w-5" />
                <h3 className="font-mono text-xs font-semibold uppercase tracking-wider">
                  Certifications &amp; Seminars
                </h3>
              </div>

              <div className="mt-5 divide-y divide-cream-200/90 dark:divide-cocoa-800">
                {CERTIFICATIONS.map((cert) => (
                  <div
                    key={cert.title}
                    className="flex flex-col justify-between gap-2 py-4 first:pt-0 last:pb-0 sm:flex-row sm:items-center"
                  >
                    <div>
                      <h4 className="font-display text-sm font-bold text-cocoa-950 sm:text-base dark:text-white">
                        {cert.title}
                      </h4>
                      <p className="mt-0.5 text-xs text-cocoa-600 dark:text-cocoa-300">
                        {cert.issuer} · {cert.date}
                      </p>
                    </div>
                    <span className="w-fit shrink-0 rounded-lg border border-cocoa-200 bg-cocoa-100/80 px-2.5 py-1 font-mono text-[11px] font-medium text-cocoa-800 dark:border-cocoa-700 dark:bg-cocoa-800 dark:text-cocoa-200">
                      {cert.topic}
                    </span>
                  </div>
                ))}
              </div>
            </div>
          </div>

          <div className="rounded-3xl border border-cream-200 bg-cream-50/75 p-6 sm:p-8 lg:col-span-5 dark:border-cocoa-800 dark:bg-cocoa-900/85">
            <div className="flex items-center gap-2.5 text-cocoa-600 dark:text-cocoa-300">
              <Users className="h-5 w-5" />
              <h3 className="font-mono text-xs font-semibold uppercase tracking-wider">
                Student Organizations
              </h3>
            </div>

            <div className="mt-6 space-y-4">
              {ORGANIZATIONS.map((org) => (
                <div
                  key={org.name}
                  className="rounded-2xl border border-cream-200 bg-white p-4 transition-colors hover:border-cocoa-300 dark:border-cocoa-800 dark:bg-cocoa-950/70 dark:hover:border-cocoa-600"
                >
                  <div className="flex items-start gap-3.5">
                    <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cocoa-800 font-mono text-xs font-bold text-cream-50 dark:bg-cocoa-600">
                      {org.acronym}
                    </div>
                    <div className="min-w-0">
                      <h4 className="font-display text-sm font-bold leading-snug text-cocoa-950 sm:text-base dark:text-white">
                        {org.name}
                      </h4>
                      <p className="mt-0.5 font-mono text-[11px] font-medium text-cocoa-600 dark:text-cocoa-300">
                        {org.period}
                      </p>
                      <p className="mt-1.5 text-xs leading-relaxed text-cocoa-700/85 dark:text-cocoa-200/80">
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
