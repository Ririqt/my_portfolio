import { ArrowRight, Download, Github, Linkedin, Mail, MapPin } from 'lucide-react';
import { PROFILE, QUICK_METRICS } from '../data/portfolio';

export function Hero() {
  const portraitSrc = `${import.meta.env.BASE_URL}${PROFILE.portraitFile}`;
  const resumeHref = `${import.meta.env.BASE_URL}${PROFILE.resumeFile}`;

  return (
    <section
      id="top"
      className="relative overflow-hidden border-b border-slate-200/80 pt-24 pb-16 sm:pt-32 sm:pb-24 dark:border-slate-800/80"
    >
      <div
        aria-hidden="true"
        className="bg-engineering-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_top,black_45%,transparent_85%)]"
      />
      <div
        aria-hidden="true"
        className="pointer-events-none absolute -top-24 right-0 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl dark:bg-blue-500/15"
      />

      <div className="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="grid items-center gap-12 lg:grid-cols-12 lg:gap-10">
          <div className="lg:col-span-7">
            <div className="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50/90 px-3.5 py-1.5 text-xs font-semibold text-blue-700 shadow-xs dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300">
              <span className="relative flex h-2 w-2">
                <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                <span className="relative inline-flex h-2 w-2 rounded-full bg-emerald-500" />
              </span>
              <span>{PROFILE.status}</span>
            </div>

            <h1 className="font-display text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-[3.35rem] lg:leading-[1.1] dark:text-white">
              Hi, I&apos;m{' '}
              <span className="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent dark:from-blue-400 dark:to-cyan-300">
                {PROFILE.fullName}
              </span>
            </h1>

            <p className="mt-4 font-mono text-sm font-medium text-slate-600 sm:text-base dark:text-slate-300">
              {PROFILE.role} · {PROFILE.university}
            </p>

            <p className="mt-5 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg dark:text-slate-300">
              {PROFILE.headlineSummary}
            </p>

            <div className="mt-8 flex flex-wrap items-center gap-3.5">
              <a
                href="#projects"
                className="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-md"
              >
                <span>Explore Projects</span>
                <ArrowRight className="h-4 w-4" />
              </a>

              <a
                href={resumeHref}
                download="Mharc_Reinald_Rigos_Resume.pdf"
                className="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-800 shadow-xs transition-all hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:border-slate-600 dark:hover:bg-slate-800"
              >
                <Download className="h-4 w-4" />
                <span>Download Resume</span>
              </a>

              <div className="flex items-center gap-2 pl-1">
                <a
                  href={PROFILE.socials.github}
                  target="_blank"
                  rel="noreferrer"
                  aria-label="GitHub profile"
                  className="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 transition-colors hover:border-blue-300 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-500/40 dark:hover:text-blue-400"
                >
                  <Github className="h-5 w-5" />
                </a>
                <a
                  href={PROFILE.socials.linkedin}
                  target="_blank"
                  rel="noreferrer"
                  aria-label="LinkedIn profile"
                  className="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 transition-colors hover:border-blue-300 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-500/40 dark:hover:text-blue-400"
                >
                  <Linkedin className="h-5 w-5" />
                </a>
                <a
                  href={`mailto:${PROFILE.email}`}
                  aria-label="Send email"
                  className="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 transition-colors hover:border-blue-300 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-500/40 dark:hover:text-blue-400"
                >
                  <Mail className="h-5 w-5" />
                </a>
              </div>
            </div>

            <div className="mt-10 grid grid-cols-1 gap-3 sm:grid-cols-3">
              {QUICK_METRICS.map((metric) => (
                <div
                  key={metric.label}
                  className="rounded-2xl border border-slate-200/90 bg-white/85 p-4 backdrop-blur-xs dark:border-slate-800 dark:bg-slate-900/70"
                >
                  <div className="font-mono text-[11px] font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    {metric.label}
                  </div>
                  <div className="mt-1 font-display text-lg font-bold text-slate-900 dark:text-white">
                    {metric.value}
                  </div>
                  <div className="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    {metric.sublabel}
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="lg:col-span-5">
            <div className="relative mx-auto max-w-sm lg:max-w-md">
              <div
                aria-hidden="true"
                className="absolute -inset-3 rounded-[2rem] bg-gradient-to-tr from-blue-600/20 via-cyan-500/10 to-transparent blur-xl dark:from-blue-500/25"
              />
              <div className="relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-2.5 shadow-xl shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900">
                <div className="relative aspect-[4/5] overflow-hidden rounded-[1.25rem] bg-slate-100 dark:bg-slate-800">
                  <img
                    src={portraitSrc}
                    alt={`${PROFILE.fullName} portrait`}
                    className="h-full w-full object-cover object-top"
                    loading="eager"
                  />
                  <div className="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-slate-950/75 via-slate-950/25 to-transparent" />
                  <div className="absolute inset-x-4 bottom-4 flex items-center justify-between gap-2 text-white">
                    <div>
                      <p className="font-display text-sm font-bold leading-tight">
                        {PROFILE.nickname} ({PROFILE.firstName})
                      </p>
                      <p className="mt-0.5 inline-flex items-center gap-1 text-xs text-slate-200">
                        <MapPin className="h-3.5 w-3.5 text-blue-300" />
                        <span>{PROFILE.location}</span>
                      </p>
                    </div>
                    <span className="rounded-lg bg-white/15 px-2.5 py-1 font-mono text-[11px] font-medium backdrop-blur-md">
                      BS CpE &apos;27
                    </span>
                  </div>
                </div>
              </div>

              <div className="animate-subtle-float absolute -right-2 -top-4 hidden rounded-2xl border border-slate-200 bg-white/95 px-3.5 py-2.5 shadow-lg backdrop-blur-md sm:block dark:border-slate-700 dark:bg-slate-900/95">
                <p className="font-mono text-[11px] font-semibold text-blue-600 dark:text-blue-400">
                  &gt; current_focus
                </p>
                <p className="mt-0.5 text-xs font-medium text-slate-700 dark:text-slate-200">
                  Web Apps · DSA · QA Testing
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
