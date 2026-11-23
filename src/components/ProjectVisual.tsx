import type { ProjectVisualType } from '../data/portfolio';

interface ProjectVisualProps {
  type: ProjectVisualType;
}

export function ProjectVisual({ type }: ProjectVisualProps) {
  if (type === 'windsurf') {
    const bars = [34, 68, 46, 90, 58, 82, 40, 74];
    return (
      <div className="relative flex h-52 w-full flex-col justify-between overflow-hidden bg-cocoa-950 p-5 text-cream-50">
        <div
          aria-hidden="true"
          className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(204,164,126,0.26),transparent_60%)]"
        />
        <div className="relative flex items-center justify-between">
          <div className="flex items-center gap-1.5">
            <span className="h-2.5 w-2.5 rounded-full bg-cocoa-400/90" />
            <span className="h-2.5 w-2.5 rounded-full bg-cocoa-300/90" />
            <span className="h-2.5 w-2.5 rounded-full bg-cream-200/90" />
            <span className="ml-2 font-mono text-[11px] text-cocoa-300">
              windsurf.onrender.com
            </span>
          </div>
          <span className="rounded-md bg-cocoa-500/25 px-2 py-0.5 font-mono text-[10px] font-semibold text-cocoa-200">
            Quick Sort · O(n log n)
          </span>
        </div>

        <div className="relative grid grid-cols-12 items-end gap-4 pt-3">
          <div className="col-span-8 flex h-28 items-end gap-1.5 rounded-xl border border-cocoa-800 bg-cocoa-900/90 p-3">
            {bars.map((height, idx) => (
              <div
                key={idx}
                className={`flex-1 rounded-t-md transition-transform duration-300 group-hover:scale-y-105 ${
                  idx === 3
                    ? 'bg-cream-100 shadow-[0_0_16px_rgba(244,237,227,0.35)]'
                    : idx === 5
                      ? 'bg-cocoa-300'
                      : 'bg-cocoa-700'
                }`}
                style={{ height: `${height}%`, transformOrigin: 'bottom' }}
              />
            ))}
          </div>

          <div className="col-span-4 flex h-28 items-center justify-center rounded-xl border border-cocoa-800 bg-cocoa-900/90 p-2">
            <svg viewBox="0 0 90 76" className="h-full w-full" aria-hidden="true">
              <line x1="45" y1="16" x2="22" y2="42" stroke="#734426" strokeWidth="2" />
              <line x1="45" y1="16" x2="68" y2="42" stroke="#734426" strokeWidth="2" />
              <line x1="22" y1="42" x2="12" y2="64" stroke="#5A341D" strokeWidth="2" />
              <line x1="22" y1="42" x2="34" y2="64" stroke="#5A341D" strokeWidth="2" />
              <line x1="68" y1="42" x2="78" y2="64" stroke="#CCA47E" strokeWidth="2" />
              <circle cx="45" cy="16" r="8" fill="#B27E53" />
              <circle cx="22" cy="42" r="7" fill="#28160D" stroke="#CCA47E" strokeWidth="2" />
              <circle cx="68" cy="42" r="7" fill="#CCA47E" />
              <circle cx="12" cy="64" r="6" fill="#28160D" stroke="#734426" strokeWidth="1.5" />
              <circle cx="34" cy="64" r="6" fill="#28160D" stroke="#734426" strokeWidth="1.5" />
              <circle cx="78" cy="64" r="6" fill="#FAF7F2" />
            </svg>
          </div>
        </div>
      </div>
    );
  }

  if (type === 'cms') {
    return (
      <div className="relative flex h-52 w-full flex-col justify-between overflow-hidden bg-cocoa-900 p-5 text-cream-50">
        <div
          aria-hidden="true"
          className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(178,126,83,0.28),transparent_60%)]"
        />
        <div className="relative flex items-center justify-between">
          <span className="font-mono text-[11px] font-semibold text-cocoa-200">
            Portfolio CMS → Vite v2
          </span>
          <span className="rounded-md bg-cream-100/15 px-2 py-0.5 font-mono text-[10px] text-cream-100">
            CI/CD Ready
          </span>
        </div>

        <div className="relative grid grid-cols-12 gap-3 pt-3">
          <div className="col-span-4 space-y-1.5 rounded-xl border border-cocoa-800 bg-cocoa-950/90 p-2.5 font-mono text-[10px] text-cocoa-300">
            <div className="rounded bg-cocoa-600/45 px-2 py-1 text-cream-50">Dashboard</div>
            <div className="px-2 py-1">About</div>
            <div className="px-2 py-1">Skills</div>
            <div className="px-2 py-1">Projects</div>
          </div>

          <div className="col-span-8 space-y-2.5">
            <div className="grid grid-cols-2 gap-2">
              <div className="rounded-xl border border-cocoa-800 bg-cocoa-950/90 p-2.5">
                <div className="font-mono text-[9px] uppercase text-cocoa-400">Bundle</div>
                <div className="mt-0.5 font-display text-sm font-bold text-cream-50">
                  Vite Static
                </div>
              </div>
              <div className="rounded-xl border border-cocoa-800 bg-cocoa-950/90 p-2.5">
                <div className="font-mono text-[9px] uppercase text-cocoa-400">Legacy DB</div>
                <div className="mt-0.5 font-display text-sm font-bold text-cocoa-300">
                  PHP/MySQL
                </div>
              </div>
            </div>
            <div className="space-y-1.5 rounded-xl border border-cocoa-800 bg-cocoa-950/90 p-2.5">
              <div className="h-2 w-3/4 rounded-full bg-cocoa-400/80" />
              <div className="h-2 w-1/2 rounded-full bg-cocoa-700" />
            </div>
          </div>
        </div>
      </div>
    );
  }

  if (type === 'weather') {
    return (
      <div className="relative flex h-52 w-full flex-col justify-between overflow-hidden bg-gradient-to-br from-cocoa-800 via-cocoa-900 to-cocoa-950 p-5 text-cream-50">
        <div className="flex items-center justify-between">
          <div>
            <p className="font-mono text-[10px] uppercase tracking-wider text-cocoa-300">
              Current Weather
            </p>
            <p className="mt-0.5 font-display text-2xl font-bold">29°C · Pasig</p>
          </div>
          <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-cocoa-300/20 ring-1 ring-cocoa-300/35">
            <svg
              viewBox="0 0 24 24"
              className="h-6 w-6 text-cream-100"
              fill="none"
              stroke="currentColor"
              strokeWidth="1.8"
              aria-hidden="true"
            >
              <circle cx="12" cy="12" r="4" />
              <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
            </svg>
          </div>
        </div>

        <div className="grid grid-cols-4 gap-2 pt-3">
          {[
            { day: 'Tue', temp: '29°' },
            { day: 'Wed', temp: '31°' },
            { day: 'Thu', temp: '27°' },
            { day: 'Fri', temp: '28°' },
          ].map((slot) => (
            <div
              key={slot.day}
              className="rounded-xl border border-cream-200/15 bg-cream-50/10 p-2.5 text-center backdrop-blur-xs"
            >
              <div className="font-mono text-[10px] text-cocoa-200">{slot.day}</div>
              <div className="mt-1 font-display text-sm font-bold">{slot.temp}</div>
            </div>
          ))}
        </div>
      </div>
    );
  }

  return (
    <div className="relative flex h-52 w-full items-center justify-center overflow-hidden bg-cocoa-900 p-5 text-cream-50">
      <div
        aria-hidden="true"
        className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(204,164,126,0.22),transparent_65%)]"
      />
      <div className="relative w-56 rounded-2xl border border-cocoa-700/80 bg-cocoa-950 p-3.5 shadow-xl">
        <div className="mb-2.5 rounded-xl bg-cocoa-900 px-3 py-2 text-right font-mono">
          <div className="text-[10px] text-cocoa-300">1024 + 512</div>
          <div className="text-lg font-bold text-cream-50">1,536</div>
        </div>
        <div className="grid grid-cols-4 gap-1.5 font-mono text-[11px] font-semibold">
          {['C', '±', '%', '÷', '7', '8', '9', '×', '4', '5', '6', '='].map((key) => (
            <div
              key={key}
              className={`flex h-7 items-center justify-center rounded-lg ${
                key === '='
                  ? 'bg-cocoa-500 text-white'
                  : ['÷', '×'].includes(key)
                    ? 'bg-cocoa-800 text-cocoa-200'
                    : 'bg-cocoa-900/90 text-cream-100'
              }`}
            >
              {key}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
