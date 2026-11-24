import { type FormEvent, useState } from 'react';
import {
  ArrowLeft,
  Database,
  Eye,
  EyeOff,
  FlaskConical,
  Lock,
  Moon,
  RefreshCw,
  ShieldCheck,
  Sun,
} from 'lucide-react';
import type { BackendMode } from '../../lib/api';

interface AdminLoginProps {
  apiAvailable: boolean;
  isDark: boolean;
  onToggleTheme: () => void;
  onBackToPortfolio: () => void;
  onLogin: (email: string, password: string) => Promise<void>;
  onEnterPreview: () => void;
  onRetryConnection: () => Promise<void>;
  retryingConnection: boolean;
  error: string;
  loading: boolean;
  mode: BackendMode;
}

export function AdminLogin({
  apiAvailable,
  isDark,
  onToggleTheme,
  onBackToPortfolio,
  onLogin,
  onEnterPreview,
  onRetryConnection,
  retryingConnection,
  error,
  loading,
}: AdminLoginProps) {
  const [email, setEmail] = useState('mcrei1018@gmail.com');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    await onLogin(email.trim(), password);
  }

  return (
    <div className="relative flex min-h-screen flex-col justify-between bg-cream-50 px-4 py-8 text-cocoa-950 dark:bg-cocoa-950 dark:text-cream-50">
      <div
        aria-hidden="true"
        className="bg-engineering-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_80%)]"
      />

      <div className="relative mx-auto flex w-full max-w-5xl items-center justify-between">
        <button
          type="button"
          onClick={onBackToPortfolio}
          className="inline-flex items-center gap-2 rounded-xl border border-cream-200 bg-white px-3.5 py-2 text-xs font-semibold text-cocoa-800 shadow-xs transition-colors hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cream-100 dark:hover:border-cocoa-600"
        >
          <ArrowLeft className="h-4 w-4" />
          <span>Back to Portfolio</span>
        </button>

        <button
          type="button"
          onClick={onToggleTheme}
          aria-label={isDark ? 'Switch to light theme' : 'Switch to dark theme'}
          className="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-cream-200 bg-white text-cocoa-700 transition-colors hover:border-cocoa-400 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200"
        >
          {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
        </button>
      </div>

      <main className="relative mx-auto my-8 w-full max-w-md">
        <div className="rounded-3xl border border-cream-200 bg-white p-7 shadow-xl shadow-cocoa-950/6 sm:p-9 dark:border-cocoa-800 dark:bg-cocoa-900">
          <div className="flex items-center justify-between gap-3">
            <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-cocoa-900 font-display text-base font-extrabold text-cream-50 dark:bg-cocoa-600">
              R<span className="text-cocoa-300 dark:text-cream-100">.</span>
            </div>
            <span
              className={`inline-flex items-center gap-1.5 rounded-full border px-3 py-1 font-mono text-[11px] font-semibold ${
                apiAvailable
                  ? 'border-cocoa-300 bg-cocoa-50 text-cocoa-800 dark:border-cocoa-600 dark:bg-cocoa-800 dark:text-cream-100'
                  : 'border-cream-300 bg-cream-100 text-cocoa-700 dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-cocoa-300'
              }`}
            >
              <Database className="h-3.5 w-3.5" />
              <span>{apiAvailable ? 'PHP + MySQL Connected' : 'PHP API Offline'}</span>
            </span>
          </div>

          <h1 className="mt-6 font-display text-2xl font-bold tracking-tight text-cocoa-950 dark:text-white">
            Owner Admin Portal
          </h1>
          <p className="mt-1.5 text-sm text-cocoa-600 dark:text-cocoa-300">
            Sign in to dynamically manage your profile, skills, projects, inquiries, and activity
            logs.
          </p>

          <form onSubmit={handleSubmit} className="mt-6 space-y-4">
            <div>
              <label
                htmlFor="admin-email"
                className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200"
              >
                Owner Email
              </label>
              <input
                id="admin-email"
                type="email"
                required
                autoComplete="username"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                disabled={loading}
                className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm text-cocoa-950 outline-none transition-colors focus:border-cocoa-600 focus:bg-white disabled:opacity-60 dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-white dark:focus:border-cocoa-400"
              />
            </div>

            <div>
              <label
                htmlFor="admin-password"
                className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200"
              >
                Password
              </label>
              <div className="relative mt-1.5">
                <input
                  id="admin-password"
                  type={showPassword ? 'text' : 'password'}
                  required
                  autoComplete="current-password"
                  placeholder="Enter owner password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  disabled={loading}
                  className="block w-full rounded-xl border border-cream-300 bg-cream-50 py-2.5 pl-4 pr-11 text-sm text-cocoa-950 outline-none transition-colors focus:border-cocoa-600 focus:bg-white disabled:opacity-60 dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-white dark:focus:border-cocoa-400"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword((prev) => !prev)}
                  aria-label={showPassword ? 'Hide password' : 'Show password'}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-cocoa-500 hover:text-cocoa-800 dark:text-cocoa-300"
                >
                  {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                </button>
              </div>
            </div>

            {error && (
              <div
                role="alert"
                className="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-medium text-rose-800 dark:border-rose-500/30 dark:bg-rose-950/40 dark:text-rose-200"
              >
                {error}
              </div>
            )}

            <button
              type="submit"
              disabled={loading}
              className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-cocoa-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-cocoa-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-cocoa-500 dark:hover:bg-cocoa-400"
            >
              <Lock className="h-4 w-4" />
              <span>{loading ? 'Signing in...' : 'Sign In to MySQL CMS'}</span>
            </button>
          </form>

          {!apiAvailable && (
            <div className="mt-6 rounded-2xl border border-cream-300 bg-cream-50 p-4 dark:border-cocoa-700 dark:bg-cocoa-950/80">
              <div className="flex items-start gap-2.5">
                <FlaskConical className="mt-0.5 h-4 w-4 shrink-0 text-cocoa-600 dark:text-cocoa-300" />
                <div className="text-xs leading-relaxed text-cocoa-700 dark:text-cocoa-200">
                  <p className="font-semibold text-cocoa-900 dark:text-white">
                    PHP + MySQL server is not running right now
                  </p>
                  <p className="mt-1">
                    Start MySQL in XAMPP and run <code className="font-mono">php -S 127.0.0.1:8000</code>{' '}
                    in the project folder, then click Retry—or test the full Admin UI in Local
                    Preview Mode.
                  </p>
                </div>
              </div>
              <div className="mt-3.5 grid grid-cols-1 gap-2 sm:grid-cols-2">
                <button
                  type="button"
                  onClick={onRetryConnection}
                  disabled={retryingConnection}
                  className="inline-flex items-center justify-center gap-1.5 rounded-xl border border-cocoa-300 bg-white px-3 py-2 text-xs font-semibold text-cocoa-900 transition-colors hover:bg-cocoa-100 disabled:opacity-60 dark:border-cocoa-600 dark:bg-cocoa-900 dark:text-cream-100 dark:hover:bg-cocoa-800"
                >
                  <RefreshCw className={`h-3.5 w-3.5 ${retryingConnection ? 'animate-spin' : ''}`} />
                  <span>{retryingConnection ? 'Checking...' : 'Retry PHP Connection'}</span>
                </button>
                <button
                  type="button"
                  onClick={onEnterPreview}
                  className="inline-flex items-center justify-center gap-1.5 rounded-xl bg-cocoa-800 px-3 py-2 text-xs font-semibold text-white transition-colors hover:bg-cocoa-700 dark:bg-cocoa-600 dark:hover:bg-cocoa-500"
                >
                  <FlaskConical className="h-3.5 w-3.5" />
                  <span>Local Preview Mode</span>
                </button>
              </div>
            </div>
          )}

          <div className="mt-6 flex items-center gap-2 border-t border-cream-200/80 pt-4 text-[11px] text-cocoa-500 dark:border-cocoa-800 dark:text-cocoa-400">
            <ShieldCheck className="h-4 w-4 shrink-0 text-cocoa-600 dark:text-cocoa-300" />
            <span>
              Owner-only access · Protected by PDO prepared statements, bcrypt &amp; CSRF tokens.
            </span>
          </div>
        </div>
      </main>

      <p className="relative text-center font-mono text-[11px] text-cocoa-500 dark:text-cocoa-400">
        Mharc Reinald Rigos · Owner Portfolio CMS
      </p>
    </div>
  );
}
