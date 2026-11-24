import { type FormEvent, useCallback, useEffect, useState } from 'react';
import {
  Activity,
  ArrowLeft,
  Check,
  Code2,
  Database,
  ExternalLink,
  FolderKanban,
  Inbox,
  KeyRound,
  LayoutDashboard,
  LogOut,
  MailOpen,
  Moon,
  Pencil,
  Plus,
  RotateCcw,
  Save,
  Sun,
  Trash2,
  Upload,
  User,
} from 'lucide-react';
import type { ProjectItem, SkillCategory, SkillItem } from '../../data/portfolio';
import {
  changeAdminPassword,
  checkAuthSession,
  createProjectApi,
  createSkillApi,
  deleteInquiryApi,
  deleteProjectApi,
  deleteSkillApi,
  fetchAdminInquiries,
  fetchAdminLogs,
  fetchAdminProjects,
  fetchAdminSkills,
  fetchPublicPortfolio,
  loadPreviewStore,
  loginAdmin,
  logoutAdmin,
  resetPreviewStore,
  resolveAssetUrl,
  savePreviewStore,
  saveProfileApi,
  toggleInquiryReadApi,
  updateProjectApi,
  updateSkillApi,
  uploadImageApi,
  type ActivityLogItem,
  type AdminUser,
  type BackendMode,
  type DynamicProject,
  type DynamicSkill,
  type InquiryItem,
  type PortfolioProfileData,
} from '../../lib/api';
import { AdminLogin } from './AdminLogin';

type AdminTab = 'overview' | 'profile' | 'skills' | 'projects' | 'inquiries' | 'logs';

interface AdminPortalProps {
  isDark: boolean;
  onToggleTheme: () => void;
  onBackToPortfolio: () => void;
  onPortfolioChanged: () => void;
}

const EMPTY_SKILL_FORM: Omit<DynamicSkill, 'id'> = {
  category: 'languages',
  name: '',
  level: 'Comfortable',
  percent: 75,
  note: '',
};

interface ProjectFormState {
  title: string;
  subtitle: string;
  category: ProjectItem['category'];
  status: ProjectItem['status'];
  featured: boolean;
  year: string;
  role: string;
  summary: string;
  highlightsText: string;
  techText: string;
  visualType: ProjectItem['visualType'];
  liveUrl: string;
  repoUrl: string;
  imageUrl: string;
}

const EMPTY_PROJECT_FORM: ProjectFormState = {
  title: '',
  subtitle: '',
  category: 'Web App',
  status: 'Completed',
  featured: false,
  year: String(new Date().getFullYear()),
  role: 'Full-Stack Developer',
  summary: '',
  highlightsText: '',
  techText: 'React, TypeScript, PHP, MySQL',
  visualType: 'windsurf',
  liveUrl: '',
  repoUrl: '',
  imageUrl: '',
};

export function AdminPortal({
  isDark,
  onToggleTheme,
  onBackToPortfolio,
  onPortfolioChanged,
}: AdminPortalProps) {
  const [checking, setChecking] = useState(true);
  const [retryingConnection, setRetryingConnection] = useState(false);
  const [apiAvailable, setApiAvailable] = useState(false);
  const [mode, setMode] = useState<BackendMode>('preview');
  const [authenticated, setAuthenticated] = useState(false);
  const [user, setUser] = useState<AdminUser | null>(null);
  const [csrfToken, setCsrfToken] = useState('');
  const [authError, setAuthError] = useState('');
  const [authLoading, setAuthLoading] = useState(false);

  const [activeTab, setActiveTab] = useState<AdminTab>('overview');
  const [toast, setToast] = useState<{ kind: 'ok' | 'err'; text: string } | null>(null);

  // CMS data state
  const [profile, setProfile] = useState<PortfolioProfileData | null>(null);
  const [skills, setSkills] = useState<DynamicSkill[]>([]);
  const [projects, setProjects] = useState<DynamicProject[]>([]);
  const [inquiries, setInquiries] = useState<InquiryItem[]>([]);
  const [unreadCount, setUnreadCount] = useState(0);
  const [logs, setLogs] = useState<ActivityLogItem[]>([]);

  // Form states
  const [editingSkillId, setEditingSkillId] = useState<number | null>(null);
  const [skillForm, setSkillForm] = useState<Omit<DynamicSkill, 'id'>>(EMPTY_SKILL_FORM);

  const [editingProjectId, setEditingProjectId] = useState<number | null>(null);
  const [projectForm, setProjectForm] = useState<ProjectFormState>(EMPTY_PROJECT_FORM);

  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [saving, setSaving] = useState(false);

  function showToast(kind: 'ok' | 'err', text: string) {
    setToast({ kind, text });
    window.setTimeout(() => {
      setToast((current) => (current?.text === text ? null : current));
    }, 3500);
  }

  const loadAllCmsData = useCallback(async (currentMode: BackendMode) => {
    if (currentMode === 'php') {
      const [pub, adminSkills, adminProjects, inqRes, logsRes] = await Promise.all([
        fetchPublicPortfolio(),
        fetchAdminSkills(),
        fetchAdminProjects(),
        fetchAdminInquiries(1, 25),
        fetchAdminLogs(1, 30),
      ]);
      setProfile(pub.snapshot.profile);
      setSkills(adminSkills);
      setProjects(adminProjects);
      setInquiries(inqRes.inquiries);
      setUnreadCount(inqRes.unread);
      setLogs(logsRes.logs);
      return;
    }

    const preview = loadPreviewStore();
    setProfile(preview.profile);
    setSkills(preview.skills);
    setProjects(preview.projects);
    setInquiries(preview.inquiries);
    setUnreadCount(preview.inquiries.filter((i) => !i.isRead).length);
    setLogs(preview.logs);
  }, []);

  useEffect(() => {
    let active = true;
    (async () => {
      try {
        const auth = await checkAuthSession();
        if (!active) return;
        setApiAvailable(true);
        setMode('php');
        setCsrfToken(auth.csrfToken);
        if (auth.authenticated && auth.user) {
          setAuthenticated(true);
          setUser(auth.user);
          await loadAllCmsData('php');
        }
      } catch {
        if (!active) return;
        setApiAvailable(false);
        setMode('preview');
      } finally {
        if (active) setChecking(false);
      }
    })();
    return () => {
      active = false;
    };
  }, [loadAllCmsData]);

  async function handleRetryConnection() {
    setRetryingConnection(true);
    setAuthError('');
    try {
      const auth = await checkAuthSession();
      setApiAvailable(true);
      setMode('php');
      setCsrfToken(auth.csrfToken);
      if (auth.authenticated && auth.user) {
        setAuthenticated(true);
        setUser(auth.user);
        await loadAllCmsData('php');
      }
    } catch {
      setApiAvailable(false);
      setMode('preview');
      setAuthError(
        'PHP + MySQL API is still unreachable on /api. Make sure MySQL is running and PHP is listening on 127.0.0.1:8000.',
      );
    } finally {
      setRetryingConnection(false);
    }
  }

  async function handleLogin(email: string, password: string) {
    setAuthError('');
    setAuthLoading(true);
    try {
      // Always synchronize with the browser's current settled PHP session cookie before login
      const freshSession = await checkAuthSession();
      setApiAvailable(true);
      setMode('php');
      const token = freshSession.csrfToken;
      setCsrfToken(token);

      if (freshSession.authenticated && freshSession.user) {
        setUser(freshSession.user);
        setAuthenticated(true);
        await loadAllCmsData('php');
        showToast('ok', `Welcome back, ${freshSession.user.name}!`);
        return;
      }

      const res = await loginAdmin(email, password, token);
      setCsrfToken(res.csrfToken);
      setUser(res.user);
      setAuthenticated(true);
      setMode('php');
      await loadAllCmsData('php');
      showToast('ok', `Welcome back, ${res.user?.name ?? 'Admin'}!`);
    } catch (err) {
      if (!apiAvailable) {
        setAuthError(
          'PHP + MySQL server is offline. Start PHP on 127.0.0.1:8000 first, or click "Local Preview Mode" below.',
        );
      } else {
        setAuthError(err instanceof Error ? err.message : 'Unable to sign in.');
      }
    } finally {
      setAuthLoading(false);
    }
  }

  async function handleEnterPreview() {
    setMode('preview');
    setAuthenticated(true);
    setUser({ id: 0, name: 'Mharc Reinald Rigos (Preview)', email: 'mcrei1018@gmail.com' });
    await loadAllCmsData('preview');
  }

  async function handleLogout() {
    if (mode === 'php') {
      try {
        await logoutAdmin(csrfToken);
        const refreshed = await checkAuthSession();
        setCsrfToken(refreshed.csrfToken);
      } catch {
        // Proceed to local sign-out
      }
    }
    setAuthenticated(false);
    setUser(null);
    setActiveTab('overview');
  }

  function appendPreviewLog(store: ReturnType<typeof loadPreviewStore>, action: string) {
    const nextLogId = store.logs.reduce((m, item) => Math.max(m, item.id), 0) + 1;
    store.logs.unshift({
      id: nextLogId,
      action,
      ipAddress: '127.0.0.1',
      createdAt: new Date().toISOString(),
    });
  }

  // Profile Save
  async function handleSaveProfile(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!profile) return;
    setSaving(true);
    try {
      if (mode === 'php') {
        await saveProfileApi(profile, csrfToken);
        await loadAllCmsData('php');
      } else {
        const store = loadPreviewStore();
        store.profile = profile;
        appendPreviewLog(store, 'Updated profile & about details');
        savePreviewStore(store);
        await loadAllCmsData('preview');
      }
      onPortfolioChanged();
      showToast('ok', 'Profile saved and published to portfolio.');
    } catch (err) {
      showToast('err', err instanceof Error ? err.message : 'Failed to save profile.');
    } finally {
      setSaving(false);
    }
  }

  async function handlePortraitUpload(file: File | null) {
    if (!file || !profile) return;
    if (mode === 'php') {
      setSaving(true);
      try {
        const res = await uploadImageApi(file, csrfToken);
        setProfile({ ...profile, portraitFile: res.url });
        showToast('ok', 'Portrait uploaded! Click "Save Profile" to publish.');
      } catch (err) {
        showToast('err', err instanceof Error ? err.message : 'Upload failed.');
      } finally {
        setSaving(false);
      }
      return;
    }

    const reader = new FileReader();
    reader.onload = () => {
      if (typeof reader.result === 'string') {
        setProfile({ ...profile, portraitFile: reader.result });
        showToast('ok', 'Preview portrait loaded! Click "Save Profile" to apply.');
      }
    };
    reader.readAsDataURL(file);
  }

  // Skills CRUD
  async function handleSkillSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!skillForm.name.trim()) return;
    setSaving(true);
    try {
      if (mode === 'php') {
        if (editingSkillId !== null) {
          await updateSkillApi(editingSkillId, skillForm, csrfToken);
          showToast('ok', `Updated skill "${skillForm.name}".`);
        } else {
          await createSkillApi(skillForm, csrfToken);
          showToast('ok', `Added skill "${skillForm.name}".`);
        }
        await loadAllCmsData('php');
      } else {
        const store = loadPreviewStore();
        if (editingSkillId !== null) {
          store.skills = store.skills.map((item) =>
            item.id === editingSkillId ? { ...skillForm, id: editingSkillId } : item,
          );
          appendPreviewLog(store, `Updated skill: ${skillForm.name}`);
          showToast('ok', `Updated skill "${skillForm.name}".`);
        } else {
          const nextId = store.skills.reduce((m, s) => Math.max(m, s.id), 0) + 1;
          store.skills.push({ ...skillForm, id: nextId });
          appendPreviewLog(store, `Added skill: ${skillForm.name}`);
          showToast('ok', `Added skill "${skillForm.name}".`);
        }
        savePreviewStore(store);
        await loadAllCmsData('preview');
      }
      setEditingSkillId(null);
      setSkillForm(EMPTY_SKILL_FORM);
      onPortfolioChanged();
    } catch (err) {
      showToast('err', err instanceof Error ? err.message : 'Could not save skill.');
    } finally {
      setSaving(false);
    }
  }

  async function handleDeleteSkill(skill: DynamicSkill) {
    if (!window.confirm(`Delete skill "${skill.name}"?`)) return;
    try {
      if (mode === 'php') {
        await deleteSkillApi(skill.id, csrfToken);
        await loadAllCmsData('php');
      } else {
        const store = loadPreviewStore();
        store.skills = store.skills.filter((s) => s.id !== skill.id);
        appendPreviewLog(store, `Deleted skill: ${skill.name}`);
        savePreviewStore(store);
        await loadAllCmsData('preview');
      }
      if (editingSkillId === skill.id) {
        setEditingSkillId(null);
        setSkillForm(EMPTY_SKILL_FORM);
      }
      onPortfolioChanged();
      showToast('ok', `Deleted skill "${skill.name}".`);
    } catch (err) {
      showToast('err', err instanceof Error ? err.message : 'Failed to delete skill.');
    }
  }

  // Projects CRUD
  async function handleProjectSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!projectForm.title.trim() || !projectForm.summary.trim()) return;
    setSaving(true);

    const payload: Omit<DynamicProject, 'id' | 'dbId'> = {
      title: projectForm.title.trim(),
      subtitle: projectForm.subtitle.trim(),
      category: projectForm.category,
      status: projectForm.status,
      featured: projectForm.featured,
      year: projectForm.year.trim() || String(new Date().getFullYear()),
      role: projectForm.role.trim() || 'Developer',
      summary: projectForm.summary.trim(),
      highlights: projectForm.highlightsText
        .split('\n')
        .map((line) => line.trim())
        .filter(Boolean),
      tech: projectForm.techText
        .split(',')
        .map((t) => t.trim())
        .filter(Boolean),
      visualType: projectForm.visualType,
      imageUrl: projectForm.imageUrl.trim() || null,
      liveUrl: projectForm.liveUrl.trim() || undefined,
      repoUrl: projectForm.repoUrl.trim() || undefined,
    };

    try {
      if (mode === 'php') {
        if (editingProjectId !== null) {
          await updateProjectApi(editingProjectId, payload, csrfToken);
          showToast('ok', `Updated project "${payload.title}".`);
        } else {
          await createProjectApi(payload, csrfToken);
          showToast('ok', `Created project "${payload.title}".`);
        }
        await loadAllCmsData('php');
      } else {
        const store = loadPreviewStore();
        if (editingProjectId !== null) {
          store.projects = store.projects.map((p) =>
            p.dbId === editingProjectId ? { ...payload, id: p.id, dbId: editingProjectId } : p,
          );
          appendPreviewLog(store, `Updated project: ${payload.title}`);
          showToast('ok', `Updated project "${payload.title}".`);
        } else {
          const nextId = store.projects.reduce((m, p) => Math.max(m, p.dbId ?? 0), 0) + 1;
          store.projects.unshift({
            ...payload,
            id: `project-${nextId}`,
            dbId: nextId,
          });
          appendPreviewLog(store, `Created project: ${payload.title}`);
          showToast('ok', `Created project "${payload.title}".`);
        }
        savePreviewStore(store);
        await loadAllCmsData('preview');
      }
      setEditingProjectId(null);
      setProjectForm(EMPTY_PROJECT_FORM);
      onPortfolioChanged();
    } catch (err) {
      showToast('err', err instanceof Error ? err.message : 'Could not save project.');
    } finally {
      setSaving(false);
    }
  }

  function startEditProject(project: DynamicProject) {
    setEditingProjectId(project.dbId ?? Number(project.id));
    setProjectForm({
      title: project.title,
      subtitle: project.subtitle,
      category: project.category,
      status: project.status,
      featured: Boolean(project.featured),
      year: project.year,
      role: project.role,
      summary: project.summary,
      highlightsText: project.highlights.join('\n'),
      techText: project.tech.join(', '),
      visualType: project.visualType,
      liveUrl: project.liveUrl ?? '',
      repoUrl: project.repoUrl ?? '',
      imageUrl: project.imageUrl ?? '',
    });
  }

  async function handleDeleteProject(project: DynamicProject) {
    const id = project.dbId ?? Number(project.id);
    if (!window.confirm(`Delete project "${project.title}"?`)) return;
    try {
      if (mode === 'php') {
        await deleteProjectApi(id, csrfToken);
        await loadAllCmsData('php');
      } else {
        const store = loadPreviewStore();
        store.projects = store.projects.filter((p) => (p.dbId ?? Number(p.id)) !== id);
        appendPreviewLog(store, `Deleted project: ${project.title}`);
        savePreviewStore(store);
        await loadAllCmsData('preview');
      }
      if (editingProjectId === id) {
        setEditingProjectId(null);
        setProjectForm(EMPTY_PROJECT_FORM);
      }
      onPortfolioChanged();
      showToast('ok', `Deleted project "${project.title}".`);
    } catch (err) {
      showToast('err', err instanceof Error ? err.message : 'Could not delete project.');
    }
  }

  // Inquiries actions
  async function handleToggleInquiryRead(inquiry: InquiryItem) {
    const nextRead = !inquiry.isRead;
    try {
      if (mode === 'php') {
        await toggleInquiryReadApi(inquiry.id, nextRead, csrfToken);
        await loadAllCmsData('php');
      } else {
        const store = loadPreviewStore();
        store.inquiries = store.inquiries.map((i) =>
          i.id === inquiry.id ? { ...i, isRead: nextRead } : i,
        );
        appendPreviewLog(
          store,
          nextRead ? `Marked inquiry #${inquiry.id} as read` : `Marked inquiry #${inquiry.id} unread`,
        );
        savePreviewStore(store);
        await loadAllCmsData('preview');
      }
    } catch (err) {
      showToast('err', err instanceof Error ? err.message : 'Failed to update inquiry.');
    }
  }

  async function handleDeleteInquiry(inquiry: InquiryItem) {
    if (!window.confirm(`Delete inquiry from ${inquiry.name}?`)) return;
    try {
      if (mode === 'php') {
        await deleteInquiryApi(inquiry.id, csrfToken);
        await loadAllCmsData('php');
      } else {
        const store = loadPreviewStore();
        store.inquiries = store.inquiries.filter((i) => i.id !== inquiry.id);
        appendPreviewLog(store, `Deleted inquiry from ${inquiry.name}`);
        savePreviewStore(store);
        await loadAllCmsData('preview');
      }
      showToast('ok', 'Inquiry deleted.');
    } catch (err) {
      showToast('err', err instanceof Error ? err.message : 'Could not delete inquiry.');
    }
  }

  // Password change (PHP mode)
  async function handleChangePassword(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (mode !== 'php') return;
    setSaving(true);
    try {
      const res = await changeAdminPassword(currentPassword, newPassword, csrfToken);
      setCurrentPassword('');
      setNewPassword('');
      await loadAllCmsData('php');
      showToast('ok', res.message);
    } catch (err) {
      showToast('err', err instanceof Error ? err.message : 'Password update failed.');
    } finally {
      setSaving(false);
    }
  }

  async function handleResetPreview() {
    if (!window.confirm('Reset local preview edits back to portfolio defaults?')) return;
    resetPreviewStore();
    await loadAllCmsData('preview');
    onPortfolioChanged();
    showToast('ok', 'Local preview data reset to defaults.');
  }

  if (checking) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-cream-50 text-cocoa-700 dark:bg-cocoa-950 dark:text-cocoa-200">
        <p className="font-mono text-sm">Checking Admin CMS session...</p>
      </div>
    );
  }

  if (!authenticated) {
    return (
      <AdminLogin
        apiAvailable={apiAvailable}
        isDark={isDark}
        onToggleTheme={onToggleTheme}
        onBackToPortfolio={onBackToPortfolio}
        onLogin={handleLogin}
        onEnterPreview={handleEnterPreview}
        onRetryConnection={handleRetryConnection}
        retryingConnection={retryingConnection}
        error={authError}
        loading={authLoading}
        mode={mode}
      />
    );
  }

  const tabs: { id: AdminTab; label: string; icon: typeof LayoutDashboard; badge?: number }[] = [
    { id: 'overview', label: 'Overview', icon: LayoutDashboard },
    { id: 'profile', label: 'Profile & About', icon: User },
    { id: 'skills', label: 'Skills', icon: Code2, badge: skills.length },
    { id: 'projects', label: 'Projects', icon: FolderKanban, badge: projects.length },
    { id: 'inquiries', label: 'Inquiries', icon: Inbox, badge: unreadCount || undefined },
    { id: 'logs', label: 'Activity Logs', icon: Activity },
  ];

  return (
    <div className="min-h-screen bg-cream-50 text-cocoa-950 dark:bg-cocoa-950 dark:text-cream-50">
      <header className="sticky top-0 z-40 border-b border-cream-200 bg-white/95 backdrop-blur-md dark:border-cocoa-800 dark:bg-cocoa-950/95">
        <div className="mx-auto flex h-16 max-w-6xl items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">
          <div className="flex items-center gap-3">
            <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-cocoa-900 font-display text-sm font-extrabold text-cream-50 dark:bg-cocoa-600">
              R<span className="text-cocoa-300">.</span>
            </span>
            <div>
              <p className="font-display text-sm font-bold text-cocoa-950 dark:text-white">
                Owner Admin CMS
              </p>
              <p className="font-mono text-[11px] text-cocoa-500 dark:text-cocoa-300">
                {user?.email}
              </p>
            </div>
            <span className="hidden items-center gap-1 rounded-full border border-cream-200 bg-cream-100 px-2.5 py-0.5 font-mono text-[10px] font-semibold text-cocoa-700 sm:inline-flex dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-cocoa-200">
              <Database className="h-3 w-3" />
              <span>{mode === 'php' ? 'MySQL Live' : 'Local Preview'}</span>
            </span>
          </div>

          <div className="flex items-center gap-2">
            {mode === 'preview' && (
              <button
                type="button"
                onClick={handleResetPreview}
                title="Reset local preview changes"
                className="hidden items-center gap-1.5 rounded-lg border border-cream-200 bg-cream-50 px-3 py-1.5 text-xs font-semibold text-cocoa-700 transition-colors hover:border-cocoa-400 sm:inline-flex dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200"
              >
                <RotateCcw className="h-3.5 w-3.5" />
                <span>Reset Preview</span>
              </button>
            )}
            <button
              type="button"
              onClick={onBackToPortfolio}
              className="inline-flex items-center gap-1.5 rounded-lg border border-cream-200 bg-white px-3 py-1.5 text-xs font-semibold text-cocoa-800 transition-colors hover:border-cocoa-400 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cream-100"
            >
              <ArrowLeft className="h-3.5 w-3.5" />
              <span className="hidden sm:inline">View Portfolio</span>
            </button>
            <button
              type="button"
              onClick={onToggleTheme}
              aria-label="Toggle theme"
              className="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-cream-200 bg-white text-cocoa-700 dark:border-cocoa-800 dark:bg-cocoa-900 dark:text-cocoa-200"
            >
              {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
            </button>
            <button
              type="button"
              onClick={handleLogout}
              className="inline-flex items-center gap-1.5 rounded-lg bg-cocoa-800 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-cocoa-700 dark:bg-cocoa-600 dark:hover:bg-cocoa-500"
            >
              <LogOut className="h-3.5 w-3.5" />
              <span className="hidden sm:inline">Sign Out</span>
            </button>
          </div>
        </div>
      </header>

      <div className="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        {toast && (
          <div
            role="status"
            className={`mb-6 flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-medium ${
              toast.kind === 'err'
                ? 'border-rose-200 bg-rose-50 text-rose-800 dark:border-rose-500/30 dark:bg-rose-950/50 dark:text-rose-200'
                : 'border-cocoa-200 bg-white text-cocoa-900 shadow-xs dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-cream-100'
            }`}
          >
            <span>{toast.text}</span>
            <Check className="h-4 w-4 shrink-0" />
          </div>
        )}

        <nav
          aria-label="Admin sections"
          className="mb-8 flex flex-wrap gap-2 rounded-2xl border border-cream-200 bg-white p-2 dark:border-cocoa-800 dark:bg-cocoa-900"
        >
          {tabs.map((tab) => {
            const Icon = tab.icon;
            const active = activeTab === tab.id;
            return (
              <button
                key={tab.id}
                type="button"
                onClick={() => setActiveTab(tab.id)}
                className={`inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all sm:text-sm ${
                  active
                    ? 'bg-cocoa-700 text-white shadow-xs dark:bg-cocoa-500'
                    : 'text-cocoa-700 hover:bg-cream-100 dark:text-cocoa-200 dark:hover:bg-cocoa-800'
                }`}
              >
                <Icon className="h-4 w-4" />
                <span>{tab.label}</span>
                {tab.badge !== undefined && (
                  <span
                    className={`rounded-full px-1.5 py-0.5 font-mono text-[10px] ${
                      active
                        ? 'bg-white/20 text-white'
                        : 'bg-cream-100 text-cocoa-700 dark:bg-cocoa-800 dark:text-cocoa-200'
                    }`}
                  >
                    {tab.badge}
                  </span>
                )}
              </button>
            );
          })}
        </nav>

        {/* OVERVIEW TAB */}
        {activeTab === 'overview' && (
          <div className="space-y-8">
            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
              {[
                { label: 'Total Skills', value: skills.length, tab: 'skills' as AdminTab },
                { label: 'Total Projects', value: projects.length, tab: 'projects' as AdminTab },
                {
                  label: 'Unread Inquiries',
                  value: unreadCount,
                  tab: 'inquiries' as AdminTab,
                },
                { label: 'Logged Actions', value: logs.length, tab: 'logs' as AdminTab },
              ].map((stat) => (
                <button
                  key={stat.label}
                  type="button"
                  onClick={() => setActiveTab(stat.tab)}
                  className="rounded-2xl border border-cream-200 bg-white p-5 text-left transition-all hover:border-cocoa-400 hover:shadow-sm dark:border-cocoa-800 dark:bg-cocoa-900"
                >
                  <p className="font-mono text-xs uppercase tracking-wider text-cocoa-500 dark:text-cocoa-300">
                    {stat.label}
                  </p>
                  <p className="mt-2 font-display text-3xl font-extrabold text-cocoa-950 dark:text-white">
                    {stat.value}
                  </p>
                </button>
              ))}
            </div>

            <div className="grid gap-6 lg:grid-cols-12">
              <div className="rounded-3xl border border-cream-200 bg-white p-6 lg:col-span-7 dark:border-cocoa-800 dark:bg-cocoa-900">
                <div className="flex items-center justify-between">
                  <h2 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
                    Recent Activity
                  </h2>
                  <button
                    type="button"
                    onClick={() => setActiveTab('logs')}
                    className="text-xs font-semibold text-cocoa-600 hover:underline dark:text-cocoa-300"
                  >
                    View all
                  </button>
                </div>
                <ul className="mt-4 divide-y divide-cream-200/80 dark:divide-cocoa-800">
                  {logs.slice(0, 6).map((log) => (
                    <li key={log.id} className="flex items-center justify-between gap-4 py-3 text-sm">
                      <span className="text-cocoa-900 dark:text-cream-100">{log.action}</span>
                      <span className="shrink-0 font-mono text-[11px] text-cocoa-500 dark:text-cocoa-400">
                        {new Date(log.createdAt).toLocaleString()}
                      </span>
                    </li>
                  ))}
                  {logs.length === 0 && (
                    <li className="py-4 text-sm text-cocoa-500">No activity recorded yet.</li>
                  )}
                </ul>
              </div>

              <div className="rounded-3xl border border-cream-200 bg-white p-6 lg:col-span-5 dark:border-cocoa-800 dark:bg-cocoa-900">
                <div className="flex items-center gap-2 text-cocoa-700 dark:text-cocoa-200">
                  <KeyRound className="h-4 w-4" />
                  <h2 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
                    Owner Security
                  </h2>
                </div>
                {mode === 'php' ? (
                  <form onSubmit={handleChangePassword} className="mt-4 space-y-3">
                    <input
                      type="password"
                      required
                      placeholder="Current password"
                      value={currentPassword}
                      onChange={(e) => setCurrentPassword(e.target.value)}
                      className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm text-cocoa-950 outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-white"
                    />
                    <input
                      type="password"
                      required
                      minLength={8}
                      placeholder="New password (8+ chars, letters & numbers)"
                      value={newPassword}
                      onChange={(e) => setNewPassword(e.target.value)}
                      className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm text-cocoa-950 outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950 dark:text-white"
                    />
                    <button
                      type="submit"
                      disabled={saving}
                      className="w-full rounded-xl bg-cocoa-700 px-4 py-2 text-xs font-semibold text-white transition-colors hover:bg-cocoa-800 disabled:opacity-60"
                    >
                      Update Owner Password
                    </button>
                  </form>
                ) : (
                  <p className="mt-3 text-xs leading-relaxed text-cocoa-600 dark:text-cocoa-300">
                    You are currently exploring <strong>Local Preview Mode</strong>. Edits save to
                    your browser so you can test the dynamic portfolio immediately. Connect MySQL +
                    PHP (<code className="font-mono">database/schema.sql</code> &amp;{' '}
                    <code className="font-mono">api/.env</code>) to enable real bcrypt login and
                    password management.
                  </p>
                )}
              </div>
            </div>
          </div>
        )}

        {/* PROFILE TAB */}
        {activeTab === 'profile' && profile && (
          <form
            onSubmit={handleSaveProfile}
            className="rounded-3xl border border-cream-200 bg-white p-6 shadow-xs sm:p-8 dark:border-cocoa-800 dark:bg-cocoa-900"
          >
            <div className="flex flex-wrap items-center justify-between gap-4 border-b border-cream-200 pb-5 dark:border-cocoa-800">
              <div>
                <h2 className="font-display text-xl font-bold text-cocoa-950 dark:text-white">
                  Profile &amp; About Details
                </h2>
                <p className="mt-0.5 text-xs text-cocoa-600 dark:text-cocoa-300">
                  Changes publish directly to your public Hero, About, Contact, and Footer sections.
                </p>
              </div>
              <button
                type="submit"
                disabled={saving}
                className="inline-flex items-center gap-2 rounded-xl bg-cocoa-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-cocoa-800 disabled:opacity-60 dark:bg-cocoa-500 dark:hover:bg-cocoa-400"
              >
                <Save className="h-4 w-4" />
                <span>{saving ? 'Saving...' : 'Save Profile'}</span>
              </button>
            </div>

            <div className="mt-6 grid gap-5 sm:grid-cols-2">
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  Full Name
                </label>
                <input
                  type="text"
                  required
                  value={profile.fullName}
                  onChange={(e) => setProfile({ ...profile, fullName: e.target.value })}
                  className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  Nickname
                </label>
                <input
                  type="text"
                  required
                  value={profile.nickname}
                  onChange={(e) => setProfile({ ...profile, nickname: e.target.value })}
                  className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  Role / Headline
                </label>
                <input
                  type="text"
                  required
                  value={profile.role}
                  onChange={(e) => setProfile({ ...profile, role: e.target.value })}
                  className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  Availability Badge
                </label>
                <input
                  type="text"
                  value={profile.status}
                  onChange={(e) => setProfile({ ...profile, status: e.target.value })}
                  className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  Public Email
                </label>
                <input
                  type="email"
                  required
                  value={profile.email}
                  onChange={(e) => setProfile({ ...profile, email: e.target.value })}
                  className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  Location (City &amp; Country)
                </label>
                <input
                  type="text"
                  value={profile.location}
                  onChange={(e) => setProfile({ ...profile, location: e.target.value })}
                  className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  University
                </label>
                <input
                  type="text"
                  value={profile.university}
                  onChange={(e) => setProfile({ ...profile, university: e.target.value })}
                  className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  Degree &amp; Graduation
                </label>
                <div className="mt-1.5 grid grid-cols-2 gap-2">
                  <input
                    type="text"
                    value={profile.degree}
                    onChange={(e) => setProfile({ ...profile, degree: e.target.value })}
                    className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                  <input
                    type="text"
                    value={profile.graduation}
                    onChange={(e) => setProfile({ ...profile, graduation: e.target.value })}
                    className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                </div>
              </div>
            </div>

            <div className="mt-5 space-y-4">
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                  Hero Intro Summary
                </label>
                <textarea
                  rows={2}
                  value={profile.headlineSummary}
                  onChange={(e) => setProfile({ ...profile, headlineSummary: e.target.value })}
                  className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
              </div>

              <div className="grid gap-4 sm:grid-cols-2">
                <div>
                  <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                    About Paragraph 1
                  </label>
                  <textarea
                    rows={4}
                    value={profile.aboutParagraphs[0] ?? ''}
                    onChange={(e) =>
                      setProfile({
                        ...profile,
                        aboutParagraphs: [e.target.value, profile.aboutParagraphs[1] ?? ''],
                      })
                    }
                    className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                    About Paragraph 2
                  </label>
                  <textarea
                    rows={4}
                    value={profile.aboutParagraphs[1] ?? ''}
                    onChange={(e) =>
                      setProfile({
                        ...profile,
                        aboutParagraphs: [profile.aboutParagraphs[0] ?? '', e.target.value],
                      })
                    }
                    className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                </div>
              </div>

              <div className="grid gap-4 sm:grid-cols-2">
                <div>
                  <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                    GitHub URL
                  </label>
                  <input
                    type="url"
                    value={profile.socials.github}
                    onChange={(e) =>
                      setProfile({
                        ...profile,
                        socials: { ...profile.socials, github: e.target.value },
                      })
                    }
                    className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                    LinkedIn URL
                  </label>
                  <input
                    type="url"
                    value={profile.socials.linkedin}
                    onChange={(e) =>
                      setProfile({
                        ...profile,
                        socials: { ...profile.socials, linkedin: e.target.value },
                      })
                    }
                    className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-4 py-2.5 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                </div>
              </div>

              <div className="flex flex-wrap items-center gap-4 rounded-2xl border border-cream-200 bg-cream-50 p-4 dark:border-cocoa-800 dark:bg-cocoa-950/60">
                <img
                  src={resolveAssetUrl(profile.portraitFile)}
                  alt="Current portrait"
                  className="h-16 w-16 rounded-2xl object-cover object-top"
                />
                <div className="flex-1">
                  <p className="text-sm font-semibold text-cocoa-950 dark:text-white">
                    Portrait Photo
                  </p>
                  <p className="text-xs text-cocoa-600 dark:text-cocoa-300">
                    Upload JPG, PNG, or WebP (max 3 MB).
                  </p>
                </div>
                <label className="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-cocoa-300 bg-white px-4 py-2 text-xs font-semibold text-cocoa-800 transition-colors hover:bg-cocoa-100 dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-cream-100">
                  <Upload className="h-3.5 w-3.5" />
                  <span>Upload Image</span>
                  <input
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    className="hidden"
                    onChange={(e) => handlePortraitUpload(e.target.files?.[0] ?? null)}
                  />
                </label>
              </div>
            </div>
          </form>
        )}

        {/* SKILLS TAB */}
        {activeTab === 'skills' && (
          <div className="grid gap-6 lg:grid-cols-12">
            <form
              onSubmit={handleSkillSubmit}
              className="h-fit rounded-3xl border border-cream-200 bg-white p-6 lg:col-span-5 dark:border-cocoa-800 dark:bg-cocoa-900"
            >
              <h2 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
                {editingSkillId !== null ? 'Edit Skill' : 'Add New Skill'}
              </h2>

              <div className="mt-4 space-y-4">
                <div>
                  <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                    Skill Name
                  </label>
                  <input
                    type="text"
                    required
                    placeholder="e.g. TypeScript, Laravel, Docker"
                    value={skillForm.name}
                    onChange={(e) => setSkillForm({ ...skillForm, name: e.target.value })}
                    className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                </div>

                <div className="grid grid-cols-2 gap-3">
                  <div>
                    <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                      Category
                    </label>
                    <select
                      value={skillForm.category}
                      onChange={(e) =>
                        setSkillForm({
                          ...skillForm,
                          category: e.target.value as SkillCategory['id'],
                        })
                      }
                      className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                    >
                      <option value="languages">Languages &amp; Web</option>
                      <option value="tools">Tools &amp; Platforms</option>
                      <option value="systems">QA, Systems &amp; Hardware</option>
                    </select>
                  </div>

                  <div>
                    <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                      Level
                    </label>
                    <select
                      value={skillForm.level}
                      onChange={(e) =>
                        setSkillForm({ ...skillForm, level: e.target.value as SkillItem['level'] })
                      }
                      className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                    >
                      <option value="Proficient">Proficient</option>
                      <option value="Comfortable">Comfortable</option>
                      <option value="Foundational">Foundational</option>
                    </select>
                  </div>
                </div>

                <div>
                  <div className="flex items-center justify-between text-xs font-semibold text-cocoa-700 dark:text-cocoa-200">
                    <span>Proficiency Meter</span>
                    <span className="font-mono">{skillForm.percent}%</span>
                  </div>
                  <input
                    type="range"
                    min={10}
                    max={100}
                    step={5}
                    value={skillForm.percent}
                    onChange={(e) =>
                      setSkillForm({ ...skillForm, percent: Number(e.target.value) })
                    }
                    className="mt-2 w-full accent-cocoa-700 dark:accent-cocoa-400"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold uppercase tracking-wider text-cocoa-700 dark:text-cocoa-200">
                    Short Practical Note
                  </label>
                  <input
                    type="text"
                    placeholder="What you use this skill for..."
                    value={skillForm.note}
                    onChange={(e) => setSkillForm({ ...skillForm, note: e.target.value })}
                    className="mt-1.5 block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                </div>

                <div className="flex gap-2 pt-2">
                  <button
                    type="submit"
                    disabled={saving}
                    className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-cocoa-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-cocoa-800 dark:bg-cocoa-500 dark:hover:bg-cocoa-400"
                  >
                    <Plus className="h-4 w-4" />
                    <span>{editingSkillId !== null ? 'Save Skill' : 'Add Skill'}</span>
                  </button>
                  {editingSkillId !== null && (
                    <button
                      type="button"
                      onClick={() => {
                        setEditingSkillId(null);
                        setSkillForm(EMPTY_SKILL_FORM);
                      }}
                      className="rounded-xl border border-cream-300 px-3.5 py-2 text-xs font-semibold text-cocoa-700 dark:border-cocoa-700 dark:text-cocoa-200"
                    >
                      Cancel
                    </button>
                  )}
                </div>
              </div>
            </form>

            <div className="rounded-3xl border border-cream-200 bg-white p-6 lg:col-span-7 dark:border-cocoa-800 dark:bg-cocoa-900">
              <h2 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
                Current Skills ({skills.length})
              </h2>
              <div className="mt-4 divide-y divide-cream-200/80 dark:divide-cocoa-800">
                {skills.map((skill) => (
                  <div
                    key={skill.id}
                    className="flex flex-wrap items-center justify-between gap-3 py-3.5"
                  >
                    <div className="min-w-0 flex-1">
                      <div className="flex flex-wrap items-center gap-2">
                        <span className="font-display text-sm font-bold text-cocoa-950 dark:text-white">
                          {skill.name}
                        </span>
                        <span className="rounded-full bg-cream-100 px-2 py-0.5 font-mono text-[10px] font-semibold text-cocoa-700 dark:bg-cocoa-800 dark:text-cocoa-200">
                          {skill.category} · {skill.level} ({skill.percent}%)
                        </span>
                      </div>
                      {skill.note && (
                        <p className="mt-0.5 text-xs text-cocoa-600 dark:text-cocoa-300">
                          {skill.note}
                        </p>
                      )}
                    </div>
                    <div className="flex items-center gap-1.5">
                      <button
                        type="button"
                        onClick={() => {
                          setEditingSkillId(skill.id);
                          setSkillForm({
                            category: skill.category,
                            name: skill.name,
                            level: skill.level,
                            percent: skill.percent,
                            note: skill.note,
                          });
                        }}
                        aria-label={`Edit ${skill.name}`}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-cream-200 text-cocoa-700 hover:border-cocoa-400 dark:border-cocoa-700 dark:text-cocoa-200"
                      >
                        <Pencil className="h-3.5 w-3.5" />
                      </button>
                      <button
                        type="button"
                        onClick={() => handleDeleteSkill(skill)}
                        aria-label={`Delete ${skill.name}`}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-cream-200 text-rose-700 hover:border-rose-400 dark:border-cocoa-700 dark:text-rose-300"
                      >
                        <Trash2 className="h-3.5 w-3.5" />
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}

        {/* PROJECTS TAB */}
        {activeTab === 'projects' && (
          <div className="grid gap-6 lg:grid-cols-12">
            <form
              onSubmit={handleProjectSubmit}
              className="h-fit rounded-3xl border border-cream-200 bg-white p-6 lg:col-span-5 dark:border-cocoa-800 dark:bg-cocoa-900"
            >
              <h2 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
                {editingProjectId !== null ? 'Edit Project' : 'Add New Project'}
              </h2>

              <div className="mt-4 space-y-3.5">
                <input
                  type="text"
                  required
                  placeholder="Project Title"
                  value={projectForm.title}
                  onChange={(e) => setProjectForm({ ...projectForm, title: e.target.value })}
                  className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />
                <input
                  type="text"
                  placeholder="Subtitle (one-line tagline)"
                  value={projectForm.subtitle}
                  onChange={(e) => setProjectForm({ ...projectForm, subtitle: e.target.value })}
                  className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />

                <div className="grid grid-cols-2 gap-2.5">
                  <select
                    value={projectForm.category}
                    onChange={(e) =>
                      setProjectForm({
                        ...projectForm,
                        category: e.target.value as ProjectItem['category'],
                      })
                    }
                    className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  >
                    <option value="Web App">Web App</option>
                    <option value="Full-Stack">Full-Stack</option>
                    <option value="UI & Frontend">UI &amp; Frontend</option>
                  </select>

                  <select
                    value={projectForm.status}
                    onChange={(e) =>
                      setProjectForm({
                        ...projectForm,
                        status: e.target.value as ProjectItem['status'],
                      })
                    }
                    className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  >
                    <option value="Live">Live</option>
                    <option value="Completed">Completed</option>
                    <option value="In Progress">In Progress</option>
                  </select>
                </div>

                <div className="grid grid-cols-2 gap-2.5">
                  <input
                    type="text"
                    placeholder="Year (e.g. 2026)"
                    value={projectForm.year}
                    onChange={(e) => setProjectForm({ ...projectForm, year: e.target.value })}
                    className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                  <select
                    value={projectForm.visualType}
                    onChange={(e) =>
                      setProjectForm({
                        ...projectForm,
                        visualType: e.target.value as ProjectItem['visualType'],
                      })
                    }
                    className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  >
                    <option value="windsurf">Visual: DSA / Charts</option>
                    <option value="cms">Visual: Dashboard CMS</option>
                    <option value="weather">Visual: Weather Cards</option>
                    <option value="calculator">Visual: Utility App</option>
                  </select>
                </div>

                <textarea
                  rows={3}
                  required
                  placeholder="Project summary..."
                  value={projectForm.summary}
                  onChange={(e) => setProjectForm({ ...projectForm, summary: e.target.value })}
                  className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />

                <textarea
                  rows={3}
                  placeholder="Highlights (one bullet per line)"
                  value={projectForm.highlightsText}
                  onChange={(e) =>
                    setProjectForm({ ...projectForm, highlightsText: e.target.value })
                  }
                  className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />

                <input
                  type="text"
                  placeholder="Tech tags (comma-separated: React, PHP, MySQL)"
                  value={projectForm.techText}
                  onChange={(e) => setProjectForm({ ...projectForm, techText: e.target.value })}
                  className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3.5 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                />

                <div className="grid grid-cols-2 gap-2.5">
                  <input
                    type="url"
                    placeholder="Live URL (optional)"
                    value={projectForm.liveUrl}
                    onChange={(e) => setProjectForm({ ...projectForm, liveUrl: e.target.value })}
                    className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                  <input
                    type="url"
                    placeholder="GitHub Repo URL"
                    value={projectForm.repoUrl}
                    onChange={(e) => setProjectForm({ ...projectForm, repoUrl: e.target.value })}
                    className="block w-full rounded-xl border border-cream-300 bg-cream-50 px-3 py-2 text-sm outline-none focus:border-cocoa-600 dark:border-cocoa-700 dark:bg-cocoa-950"
                  />
                </div>

                <label className="flex items-center gap-2 text-xs font-semibold text-cocoa-700 dark:text-cocoa-200">
                  <input
                    type="checkbox"
                    checked={projectForm.featured}
                    onChange={(e) =>
                      setProjectForm({ ...projectForm, featured: e.target.checked })
                    }
                    className="h-4 w-4 accent-cocoa-700"
                  />
                  <span>Mark as Featured project</span>
                </label>

                <div className="flex gap-2 pt-2">
                  <button
                    type="submit"
                    disabled={saving}
                    className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-cocoa-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-cocoa-800 dark:bg-cocoa-500 dark:hover:bg-cocoa-400"
                  >
                    <Plus className="h-4 w-4" />
                    <span>{editingProjectId !== null ? 'Save Project' : 'Create Project'}</span>
                  </button>
                  {editingProjectId !== null && (
                    <button
                      type="button"
                      onClick={() => {
                        setEditingProjectId(null);
                        setProjectForm(EMPTY_PROJECT_FORM);
                      }}
                      className="rounded-xl border border-cream-300 px-3.5 py-2 text-xs font-semibold text-cocoa-700 dark:border-cocoa-700 dark:text-cocoa-200"
                    >
                      Cancel
                    </button>
                  )}
                </div>
              </div>
            </form>

            <div className="rounded-3xl border border-cream-200 bg-white p-6 lg:col-span-7 dark:border-cocoa-800 dark:bg-cocoa-900">
              <h2 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
                Projects ({projects.length})
              </h2>
              <div className="mt-4 space-y-3">
                {projects.map((project) => (
                  <div
                    key={project.id}
                    className="rounded-2xl border border-cream-200 bg-cream-50/70 p-4 dark:border-cocoa-800 dark:bg-cocoa-950/60"
                  >
                    <div className="flex items-start justify-between gap-3">
                      <div>
                        <div className="flex flex-wrap items-center gap-2">
                          <h3 className="font-display text-base font-bold text-cocoa-950 dark:text-white">
                            {project.title}
                          </h3>
                          <span className="rounded-full bg-cocoa-100 px-2 py-0.5 font-mono text-[10px] font-semibold text-cocoa-800 dark:bg-cocoa-800 dark:text-cocoa-200">
                            {project.status}
                          </span>
                          {project.featured && (
                            <span className="rounded-full bg-cocoa-800 px-2 py-0.5 font-mono text-[10px] font-semibold text-cream-50">
                              Featured
                            </span>
                          )}
                        </div>
                        <p className="mt-1 text-xs text-cocoa-700 dark:text-cocoa-300">
                          {project.summary}
                        </p>
                      </div>
                      <div className="flex shrink-0 items-center gap-1.5">
                        <button
                          type="button"
                          onClick={() => startEditProject(project)}
                          aria-label={`Edit ${project.title}`}
                          className="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-cream-200 bg-white text-cocoa-700 hover:border-cocoa-400 dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-cocoa-200"
                        >
                          <Pencil className="h-3.5 w-3.5" />
                        </button>
                        <button
                          type="button"
                          onClick={() => handleDeleteProject(project)}
                          aria-label={`Delete ${project.title}`}
                          className="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-cream-200 bg-white text-rose-700 hover:border-rose-400 dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-rose-300"
                        >
                          <Trash2 className="h-3.5 w-3.5" />
                        </button>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}

        {/* INQUIRIES TAB */}
        {activeTab === 'inquiries' && (
          <div className="rounded-3xl border border-cream-200 bg-white p-6 dark:border-cocoa-800 dark:bg-cocoa-900">
            <div className="flex items-center justify-between gap-4">
              <div>
                <h2 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
                  Contact Inquiries ({inquiries.length})
                </h2>
                <p className="text-xs text-cocoa-600 dark:text-cocoa-300">
                  Messages submitted from your public portfolio contact form.
                </p>
              </div>
            </div>

            <div className="mt-5 space-y-3">
              {inquiries.map((inquiry) => (
                <div
                  key={inquiry.id}
                  className={`rounded-2xl border p-4 transition-colors ${
                    inquiry.isRead
                      ? 'border-cream-200 bg-cream-50/50 dark:border-cocoa-800 dark:bg-cocoa-950/40'
                      : 'border-cocoa-300 bg-cocoa-50/70 dark:border-cocoa-600 dark:bg-cocoa-800/60'
                  }`}
                >
                  <div className="flex flex-wrap items-start justify-between gap-3">
                    <div>
                      <div className="flex items-center gap-2">
                        {!inquiry.isRead && (
                          <span className="h-2 w-2 rounded-full bg-cocoa-600 dark:bg-cocoa-300" />
                        )}
                        <h3 className="font-display text-sm font-bold text-cocoa-950 dark:text-white">
                          {inquiry.subject}
                        </h3>
                      </div>
                      <p className="mt-0.5 font-mono text-xs text-cocoa-600 dark:text-cocoa-300">
                        From {inquiry.name} ({inquiry.email}) ·{' '}
                        {new Date(inquiry.createdAt).toLocaleString()}
                      </p>
                      <p className="mt-2.5 text-sm leading-relaxed text-cocoa-800 dark:text-cream-100">
                        {inquiry.message}
                      </p>
                    </div>

                    <div className="flex items-center gap-1.5">
                      <a
                        href={`mailto:${inquiry.email}?subject=${encodeURIComponent(`Re: ${inquiry.subject}`)}`}
                        className="inline-flex items-center gap-1 rounded-lg border border-cream-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-cocoa-800 hover:border-cocoa-400 dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-cream-100"
                      >
                        <ExternalLink className="h-3.5 w-3.5" />
                        <span>Reply</span>
                      </a>
                      <button
                        type="button"
                        onClick={() => handleToggleInquiryRead(inquiry)}
                        className="inline-flex items-center gap-1 rounded-lg border border-cream-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-cocoa-700 hover:border-cocoa-400 dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-cocoa-200"
                      >
                        <MailOpen className="h-3.5 w-3.5" />
                        <span>{inquiry.isRead ? 'Mark Unread' : 'Mark Read'}</span>
                      </button>
                      <button
                        type="button"
                        onClick={() => handleDeleteInquiry(inquiry)}
                        aria-label="Delete inquiry"
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-cream-200 bg-white text-rose-700 hover:border-rose-400 dark:border-cocoa-700 dark:bg-cocoa-900 dark:text-rose-300"
                      >
                        <Trash2 className="h-3.5 w-3.5" />
                      </button>
                    </div>
                  </div>
                </div>
              ))}
              {inquiries.length === 0 && (
                <p className="py-6 text-center text-sm text-cocoa-500">
                  No inquiries received yet.
                </p>
              )}
            </div>
          </div>
        )}

        {/* ACTIVITY LOGS TAB */}
        {activeTab === 'logs' && (
          <div className="rounded-3xl border border-cream-200 bg-white p-6 dark:border-cocoa-800 dark:bg-cocoa-900">
            <h2 className="font-display text-lg font-bold text-cocoa-950 dark:text-white">
              Activity Audit Trail ({logs.length})
            </h2>
            <div className="mt-4 overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead>
                  <tr className="border-b border-cream-200 font-mono text-xs uppercase text-cocoa-500 dark:border-cocoa-800 dark:text-cocoa-300">
                    <th className="py-3 pr-4">Action</th>
                    <th className="py-3 pr-4">IP</th>
                    <th className="py-3">Timestamp</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-cream-200/80 dark:divide-cocoa-800">
                  {logs.map((log) => (
                    <tr key={log.id}>
                      <td className="py-3 pr-4 font-medium text-cocoa-900 dark:text-cream-100">
                        {log.action}
                      </td>
                      <td className="py-3 pr-4 font-mono text-xs text-cocoa-500 dark:text-cocoa-400">
                        {log.ipAddress ?? '—'}
                      </td>
                      <td className="py-3 font-mono text-xs text-cocoa-600 dark:text-cocoa-300">
                        {new Date(log.createdAt).toLocaleString()}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
