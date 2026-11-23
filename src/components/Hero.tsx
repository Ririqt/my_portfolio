import { ArrowRight, Download, Github, Linkedin, Mail, MapPin } from 'lucide-react';
import { QUICK_METRICS } from '../data/portfolio';
import { resolveAssetUrl, type PortfolioProfileData } from '../lib/api';

interface HeroProps {
  profile: PortfolioProfileData;
}

export function Hero({ profile }: HeroProps) {
  const portraitSrc = resolveAssetUrl(profile.portraitFile);
  const resumeHref = resolveAssetUrl(profile.resumeFile);

  return (
    <section
      id="top"
      className="relative overflow-hidden border-b border-cream-200/90 pt-24 pb-16 sm:pt-32 sm:pb-24 dark:border-cocoa-800/80"
    >
      <div
        aria-hidden="true"
        className="bg-engineering-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_top,black_45%,transparent_85%)]"
      />
      <div
        aria-hidden="true"
        className="pointer-events-none absolute -top-24 right-0 h-80 w-80 rounded-full bg-cocoa-300/20 blur-3xl dark:bg-cocoa-600/20"
      />

      <div className="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="grid items-center gap-12 lg:grid-cols-12 lg:gap-10">
          <div className="lg:col-span-7">
            <div className="mb-6 inline-flex items-center gap-2 rounded-full border border-cocoa-200 bg-white/95 px-3.5 py-1.5 text-xs font-semibold text-cocoa-700 shadow-xs dark:border-cocoa-700/80 dark:bg-cocoa-900/90 dark:text-cocoa-200">
              <span className="relative flex h-2 w-2">
                <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-cocoa-400 opacity-75" />
                <span className="relative inline-flex h-2 w-2 rounded-full bg-cocoa-600 dark:bg-cocoa-300" />
              </span>
              <span>{profile.status}</span>
            </div>

            <h1 className="font-display text-4xl font-extrabold tracking-tight text-cocoa-950 sm:text-5xl lg:text-[3.35rem] lg:leading-[1.1] dark:text-white">
              Hi, I&apos;m{' '}
              <span className="bg-gradient-to-r from-cocoa-800 via-cocoa-600 to-cocoa-400 bg-clip-text text-transparent dark:from-cream-100 dark:via-cocoa-300 dark:to-cocoa-400">
                {profile.fullName}
              </span>
            </h1>

            <p className="mt-4 font-mono text-sm font-medium text-cocoa-600 sm:text-base dark:text-cocoa-300">
              {profile.role} · {profile.university}
            </p>

            <p className="mt-5 max-w-2xl text-base leading-relaxed text-cocoa-800/85 sm:text-lg dark:text-cream-100/80">
              {profile.headlineSummary}
            </p>

            <div className="mt-8 flex flex-wrap items-center gap-3.5">
              <a
                href="#projects"
                className="inline-flex items-center gap-2 rounded-xl bg-cocoa-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-cocoa-800 hover:shadow-md dark:bg-cocoa-500 dark:hover:bg-cocoa-400"
              >
                <span>Explore Projects</span>
                <ArrowRight className="h-4 w-4" />
              </a>

              <a
                href={resumeHref}
                download="Mharc_Reinald_Rigos_Resume.pdf"
                className="inline-flex items-center gap-2 rounded-xl border border-cocoa-200 bg-white px-5 py-3 text-sm font-semibold text-cocoa-900 shadow-xs transition-all hover:-translate-y-0.5 hover:border-cocoa-400 hover:bg-cream-50 dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-cream-50 dark:hover:border-cocoa-500 dark:hover:bg-cocoa-800"
              >
                <Download className="h-4 w-4" />
                <span>Download Resume</span>
              </a>

              <div className="flex items-center gap-2 pl-1">
                {profile.socials.github && (
                  <a
                    href={profile.socials.github}
                    target="_blank"
                    rel="noreferrer"
                    aria-label="GitHub profile"
                    className="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-cream-200 bg-white text-cocoa-700 transition-colors hover:border-cocoa-400 hover:text-cocoa-900 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200 dark:hover:border-cocoa-500 dark:hover:text-white"
                  >
                    <Github className="h-5 w-5" />
                  </a>
                )}
                {profile.socials.linkedin && (
                  <a
                    href={profile.socials.linkedin}
                    target="_blank"
                    rel="noreferrer"
                    aria-label="LinkedIn profile"
                    className="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-cream-200 bg-white text-cocoa-700 transition-colors hover:border-cocoa-400 hover:text-cocoa-900 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200 dark:hover:border-cocoa-500 dark:hover:text-white"
                  >
                    <Linkedin className="h-5 w-5" />
                  </a>
                )}
                <a
                  href={`mailto:${profile.email}`}
                  aria-label="Send email"
                  className="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-cream-200 bg-white text-cocoa-700 transition-colors hover:border-cocoa-400 hover:text-cocoa-900 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200 dark:hover:border-cocoa-500 dark:hover:text-white"
                >
                  <Mail className="h-5 w-5" />
                </a>
              </div>
            </div>

            <div className="mt-10 grid grid-cols-1 gap-3 sm:grid-cols-3">
              {QUICK_METRICS.map((metric) => (
                <div
                  key={metric.label}
                  className="rounded-2xl border border-cream-200 bg-white/90 p-4 backdrop-blur-xs dark:border-cocoa-800 dark:bg-cocoa-900/80"
                >
                  <div className="font-mono text-[11px] font-medium uppercase tracking-wider text-cocoa-500 dark:text-cocoa-300">
                    {metric.label}
                  </div>
                  <div className="mt-1 font-display text-lg font-bold text-cocoa-950 dark:text-white">
                    {metric.value}
                  </div>
                  <div className="mt-0.5 text-xs text-cocoa-600/85 dark:text-cocoa-300/85">
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
                className="absolute -inset-3 rounded-[2rem] bg-gradient-to-tr from-cocoa-500/20 via-cocoa-300/15 to-transparent blur-xl dark:from-cocoa-500/25"
              />
              <div className="relative overflow-hidden rounded-[1.75rem] border border-cream-200 bg-white p-2.5 shadow-xl shadow-cocoa-950/8 dark:border-cocoa-800 dark:bg-cocoa-900">
                <div className="relative aspect-[4/5] overflow-hidden rounded-[1.25rem] bg-cream-100 dark:bg-cocoa-800">
                  <img
                    src={portraitSrc}
                    alt={`${profile.fullName} portrait`}
                    className="h-full w-full object-cover object-top"
                    loading="eager"
                  />
                  <div className="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-cocoa-950/85 via-cocoa-950/35 to-transparent" />
                  <div className="absolute inset-x-4 bottom-4 flex items-center justify-between gap-2 text-white">
                    <div>
                      <p className="font-display text-sm font-bold leading-tight">
                        {profile.nickname} ({profile.firstName})
                      </p>
                      <p className="mt-0.5 inline-flex items-center gap-1 text-xs text-cream-100">
                        <MapPin className="h-3.5 w-3.5 text-cocoa-300" />
                        <span>{profile.location}</span>
                      </p>
                    </div>
                    <span className="rounded-lg border border-white/20 bg-cocoa-950/40 px-2.5 py-1 font-mono text-[11px] font-medium backdrop-blur-md">
                      BS CpE &apos;27
                    </span>
                  </div>
                </div>
              </div>

              <div className="animate-subtle-float absolute -right-2 -top-4 hidden rounded-2xl border border-cream-200 bg-white/95 px-3.5 py-2.5 shadow-lg shadow-cocoa-950/8 backdrop-blur-md sm:block dark:border-cocoa-700 dark:bg-cocoa-900/95">
                <p className="font-mono text-[11px] font-semibold text-cocoa-600 dark:text-cocoa-300">
                  &gt; current_focus
                </p>
                <p className="mt-0.5 text-xs font-medium text-cocoa-900 dark:text-cream-100">
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
