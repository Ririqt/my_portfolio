import { type FormEvent, useState } from 'react';
import { Check, Copy, Github, Linkedin, Mail, MapPin, Send } from 'lucide-react';
import { PROFILE } from '../data/portfolio';

interface ContactFormState {
  name: string;
  email: string;
  subject: string;
  message: string;
}

const INITIAL_FORM: ContactFormState = {
  name: '',
  email: '',
  subject: '',
  message: '',
};

export function ContactSection() {
  const [form, setForm] = useState<ContactFormState>(INITIAL_FORM);
  const [copied, setCopied] = useState(false);
  const [status, setStatus] = useState<'idle' | 'submitting' | 'sent' | 'mailto' | 'error'>('idle');
  const [feedback, setFeedback] = useState('');

  async function handleCopyEmail() {
    try {
      await navigator.clipboard.writeText(PROFILE.email);
      setCopied(true);
      setTimeout(() => setCopied(false), 2200);
    } catch {
      window.location.href = `mailto:${PROFILE.email}`;
    }
  }

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const trimmed = {
      name: form.name.trim(),
      email: form.email.trim(),
      subject: form.subject.trim(),
      message: form.message.trim(),
    };

    if (!trimmed.name || !trimmed.email || !trimmed.subject || !trimmed.message) {
      setStatus('error');
      setFeedback('Please fill in all fields before sending your message.');
      return;
    }

    if (PROFILE.formspreeEndpoint) {
      setStatus('submitting');
      setFeedback('');
      try {
        const response = await fetch(PROFILE.formspreeEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          body: JSON.stringify(trimmed),
        });

        if (!response.ok) {
          throw new Error('Failed to send message');
        }

        setStatus('sent');
        setFeedback('Thanks for reaching out! Your message has been sent.');
        setForm(INITIAL_FORM);
      } catch {
        setStatus('error');
        setFeedback(
          `Could not submit right now. You can email me directly at ${PROFILE.email}.`,
        );
      }
      return;
    }

    const body = `Hi Rei,\n\n${trimmed.message}\n\n— ${trimmed.name} (${trimmed.email})`;
    const mailtoUrl = `mailto:${PROFILE.email}?subject=${encodeURIComponent(
      trimmed.subject,
    )}&body=${encodeURIComponent(body)}`;

    window.location.href = mailtoUrl;
    setStatus('mailto');
    setFeedback(
      `Your email client has been opened with your message to ${PROFILE.email}. You can also copy my email address directly.`,
    );
  }

  return (
    <section id="contact" className="py-20 sm:py-24">
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div className="max-w-2xl">
          <p className="font-mono text-xs font-semibold uppercase tracking-widest text-cocoa-600 dark:text-cocoa-300">
            05 · Get In Touch
          </p>
          <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-cocoa-950 sm:text-4xl dark:text-white">
            Let&apos;s connect for internships, projects, or collaborations.
          </h2>
          <p className="mt-3 text-base text-cocoa-700/85 dark:text-cream-100/75">
            Have an internship opportunity, web project, or QA need? Drop a message below or reach
            out directly.
          </p>
        </div>

        <div className="mt-10 grid gap-8 lg:grid-cols-12">
          <div className="space-y-4 lg:col-span-5">
            <div className="rounded-3xl border border-cream-200 bg-white p-6 shadow-xs dark:border-cocoa-800 dark:bg-cocoa-900/85">
              <div className="space-y-5">
                <div className="flex items-start gap-3.5">
                  <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cocoa-100 text-cocoa-700 dark:bg-cocoa-800 dark:text-cocoa-300">
                    <Mail className="h-5 w-5" />
                  </div>
                  <div className="min-w-0 flex-1">
                    <p className="font-mono text-xs uppercase tracking-wider text-cocoa-500 dark:text-cocoa-300">
                      Email
                    </p>
                    <a
                      href={`mailto:${PROFILE.email}`}
                      className="mt-0.5 block truncate font-display text-base font-bold text-cocoa-950 hover:text-cocoa-600 dark:text-white dark:hover:text-cocoa-300"
                    >
                      {PROFILE.email}
                    </a>
                    <button
                      type="button"
                      onClick={handleCopyEmail}
                      className="mt-2 inline-flex items-center gap-1.5 rounded-lg border border-cream-200 bg-cream-50 px-2.5 py-1 text-xs font-semibold text-cocoa-800 transition-colors hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-700 dark:bg-cocoa-800 dark:text-cream-100 dark:hover:border-cocoa-500"
                    >
                      {copied ? (
                        <>
                          <Check className="h-3.5 w-3.5 text-cocoa-600 dark:text-cocoa-300" />
                          <span>Copied to clipboard</span>
                        </>
                      ) : (
                        <>
                          <Copy className="h-3.5 w-3.5" />
                          <span>Copy email</span>
                        </>
                      )}
                    </button>
                  </div>
                </div>

                <div className="flex items-start gap-3.5 border-t border-cream-200/80 pt-5 dark:border-cocoa-800">
                  <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cream-100 text-cocoa-700 dark:bg-cocoa-800 dark:text-cocoa-200">
                    <MapPin className="h-5 w-5" />
                  </div>
                  <div>
                    <p className="font-mono text-xs uppercase tracking-wider text-cocoa-500 dark:text-cocoa-300">
                      Location
                    </p>
                    <p className="mt-0.5 font-display text-base font-bold text-cocoa-950 dark:text-white">
                      {PROFILE.location}
                    </p>
                    <p className="mt-0.5 text-xs text-cocoa-600 dark:text-cocoa-300">
                      Open to remote, hybrid &amp; Metro Manila internships
                    </p>
                  </div>
                </div>
              </div>

              <div className="mt-6 border-t border-cream-200/80 pt-5 dark:border-cocoa-800">
                <p className="mb-3 font-mono text-xs uppercase tracking-wider text-cocoa-500 dark:text-cocoa-300">
                  Profiles
                </p>
                <div className="flex flex-wrap gap-2.5">
                  <a
                    href={PROFILE.socials.github}
                    target="_blank"
                    rel="noreferrer"
                    className="inline-flex items-center gap-2 rounded-xl border border-cream-200 bg-cream-50 px-3.5 py-2 text-xs font-semibold text-cocoa-800 transition-colors hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-700 dark:bg-cocoa-800 dark:text-cream-100 dark:hover:border-cocoa-500"
                  >
                    <Github className="h-4 w-4" />
                    <span>GitHub</span>
                  </a>
                  <a
                    href={PROFILE.socials.linkedin}
                    target="_blank"
                    rel="noreferrer"
                    className="inline-flex items-center gap-2 rounded-xl border border-cream-200 bg-cream-50 px-3.5 py-2 text-xs font-semibold text-cocoa-800 transition-colors hover:border-cocoa-400 hover:text-cocoa-950 dark:border-cocoa-700 dark:bg-cocoa-800 dark:text-cream-100 dark:hover:border-cocoa-500"
                  >
                    <Linkedin className="h-4 w-4" />
                    <span>LinkedIn</span>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <form
            onSubmit={handleSubmit}
            noValidate
            className="rounded-3xl border border-cream-200 bg-white p-6 shadow-xs sm:p-8 lg:col-span-7 dark:border-cocoa-800 dark:bg-cocoa-900/85"
          >
            <div className="grid gap-5 sm:grid-cols-2">
              <div>
                <label
                  htmlFor="contact-name"
                  className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200"
                >
                  Name
                </label>
                <input
                  id="contact-name"
                  name="name"
                  type="text"
                  required
                  value={form.name}
                  onChange={(e) => setForm({ ...form, name: e.target.value })}
                  placeholder="Your name"
                  className="mt-2 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm text-cocoa-950 outline-none transition-colors placeholder:text-cocoa-400 focus:border-cocoa-600 focus:bg-white dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-white dark:focus:border-cocoa-400"
                />
              </div>

              <div>
                <label
                  htmlFor="contact-email"
                  className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200"
                >
                  Email
                </label>
                <input
                  id="contact-email"
                  name="email"
                  type="email"
                  required
                  value={form.email}
                  onChange={(e) => setForm({ ...form, email: e.target.value })}
                  placeholder="you@example.com"
                  className="mt-2 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm text-cocoa-950 outline-none transition-colors placeholder:text-cocoa-400 focus:border-cocoa-600 focus:bg-white dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-white dark:focus:border-cocoa-400"
                />
              </div>
            </div>

            <div className="mt-5">
              <label
                htmlFor="contact-subject"
                className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200"
              >
                Subject
              </label>
              <input
                id="contact-subject"
                name="subject"
                type="text"
                required
                value={form.subject}
                onChange={(e) => setForm({ ...form, subject: e.target.value })}
                placeholder="Internship inquiry, collaboration, or project idea"
                className="mt-2 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm text-cocoa-950 outline-none transition-colors placeholder:text-cocoa-400 focus:border-cocoa-600 focus:bg-white dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-white dark:focus:border-cocoa-400"
              />
            </div>

            <div className="mt-5">
              <label
                htmlFor="contact-message"
                className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200"
              >
                Message
              </label>
              <textarea
                id="contact-message"
                name="message"
                rows={4}
                required
                value={form.message}
                onChange={(e) => setForm({ ...form, message: e.target.value })}
                placeholder="Tell me a bit about what you're working on..."
                className="mt-2 block w-full resize-y rounded-xl border border-cream-300 bg-cream-50 px-4 py-3 text-sm text-cocoa-950 outline-none transition-colors placeholder:text-cocoa-400 focus:border-cocoa-600 focus:bg-white dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-white dark:focus:border-cocoa-400"
              />
            </div>

            {feedback && (
              <div
                role="status"
                className={`mt-5 rounded-xl border px-4 py-3 text-xs font-medium sm:text-sm ${
                  status === 'error'
                    ? 'border-rose-200 bg-rose-50 text-rose-800 dark:border-rose-500/30 dark:bg-rose-950/40 dark:text-rose-200'
                    : 'border-cocoa-200 bg-cocoa-50 text-cocoa-800 dark:border-cocoa-600/60 dark:bg-cocoa-800/70 dark:text-cream-100'
                }`}
              >
                {feedback}
              </div>
            )}

            <div className="mt-6 flex items-center justify-between gap-4">
              <p className="text-xs text-cocoa-600 dark:text-cocoa-300">
                Replies usually within 24–48 hours.
              </p>
              <button
                type="submit"
                disabled={status === 'submitting'}
                className="inline-flex items-center gap-2 rounded-xl bg-cocoa-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-cocoa-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-cocoa-500 dark:hover:bg-cocoa-400"
              >
                <Send className="h-4 w-4" />
                <span>{status === 'submitting' ? 'Sending...' : 'Send Message'}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </section>
  );
}
