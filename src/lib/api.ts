import {
  PROFILE as DEFAULT_PROFILE,
  PROJECTS as DEFAULT_PROJECTS,
  SKILL_CATEGORIES as DEFAULT_SKILL_CATEGORIES,
  type ProjectItem,
  type SkillCategory,
  type SkillItem,
} from '../data/portfolio';

export type BackendMode = 'php' | 'preview';

export interface PortfolioProfileData {
  fullName: string;
  firstName: string;
  nickname: string;
  role: string;
  status: string;
  location: string;
  email: string;
  university: string;
  degree: string;
  graduation: string;
  resumeFile: string;
  portraitFile: string;
  formspreeEndpoint: string;
  headlineSummary: string;
  aboutParagraphs: string[];
  socials: {
    github: string;
    linkedin: string;
    facebook: string;
  };
}

export interface DynamicSkill extends SkillItem {
  id: number;
  category: SkillCategory['id'];
}

export interface DynamicProject extends ProjectItem {
  dbId?: number;
  imageUrl?: string | null;
}

export interface InquiryItem {
  id: number;
  name: string;
  email: string;
  subject: string;
  message: string;
  isRead: boolean;
  createdAt: string;
}

export interface ActivityLogItem {
  id: number;
  action: string;
  ipAddress: string | null;
  createdAt: string;
}

export interface AdminUser {
  id: number;
  name: string;
  email: string;
}

export interface AuthStateResponse {
  authenticated: boolean;
  user: AdminUser | null;
  csrfToken: string;
}

export interface PortfolioSnapshot {
  profile: PortfolioProfileData;
  skills: DynamicSkill[];
  projects: DynamicProject[];
}

export interface PreviewStoreData extends PortfolioSnapshot {
  inquiries: InquiryItem[];
  logs: ActivityLogItem[];
}

const PREVIEW_STORAGE_KEY = 'rei-portfolio-cms-preview-v1';
const PORTFOLIO_SYNC_CHANNEL = 'rei-portfolio-sync';
export const PORTFOLIO_SYNC_STORAGE_KEY = 'rei-portfolio-updated-at';

export function notifyPortfolioUpdated(): void {
  try {
    localStorage.setItem(PORTFOLIO_SYNC_STORAGE_KEY, String(Date.now()));
  } catch {
    // Ignore storage restrictions
  }
  try {
    if (typeof BroadcastChannel !== 'undefined') {
      const channel = new BroadcastChannel(PORTFOLIO_SYNC_CHANNEL);
      channel.postMessage({ type: 'portfolio-updated', at: Date.now() });
      channel.close();
    }
  } catch {
    // Ignore unsupported BroadcastChannel environments
  }
}

export function subscribePortfolioUpdates(onUpdate: () => void): () => void {
  const onStorage = (event: StorageEvent) => {
    if (event.key === PORTFOLIO_SYNC_STORAGE_KEY) {
      onUpdate();
    }
  };
  window.addEventListener('storage', onStorage);

  let channel: BroadcastChannel | null = null;
  try {
    if (typeof BroadcastChannel !== 'undefined') {
      channel = new BroadcastChannel(PORTFOLIO_SYNC_CHANNEL);
      channel.onmessage = () => onUpdate();
    }
  } catch {
    channel = null;
  }

  return () => {
    window.removeEventListener('storage', onStorage);
    channel?.close();
  };
}

export const API_BASE = (import.meta.env.VITE_API_BASE_URL as string | undefined) || '/api';

export function getDefaultPortfolioSnapshot(): PortfolioSnapshot {
  let skillId = 1;
  const flatSkills: DynamicSkill[] = DEFAULT_SKILL_CATEGORIES.flatMap((cat) =>
    cat.items.map((item) => ({
      ...item,
      id: skillId++,
      category: cat.id,
    })),
  );

  const projects: DynamicProject[] = DEFAULT_PROJECTS.map((proj, index) => ({
    ...proj,
    dbId: index + 1,
    imageUrl: null,
  }));

  return {
    profile: {
      fullName: DEFAULT_PROFILE.fullName,
      firstName: DEFAULT_PROFILE.firstName,
      nickname: DEFAULT_PROFILE.nickname,
      role: DEFAULT_PROFILE.role,
      status: DEFAULT_PROFILE.status,
      location: DEFAULT_PROFILE.location,
      email: DEFAULT_PROFILE.email,
      university: DEFAULT_PROFILE.university,
      degree: DEFAULT_PROFILE.degree,
      graduation: DEFAULT_PROFILE.graduation,
      resumeFile: DEFAULT_PROFILE.resumeFile,
      portraitFile: DEFAULT_PROFILE.portraitFile,
      formspreeEndpoint: DEFAULT_PROFILE.formspreeEndpoint,
      headlineSummary: DEFAULT_PROFILE.headlineSummary,
      aboutParagraphs: [...DEFAULT_PROFILE.aboutParagraphs],
      socials: { ...DEFAULT_PROFILE.socials },
    },
    skills: flatSkills,
    projects,
  };
}

export function groupSkillsByCategory(skills: DynamicSkill[]): SkillCategory[] {
  return DEFAULT_SKILL_CATEGORIES.map((meta) => ({
    id: meta.id,
    label: meta.label,
    description: meta.description,
    items: skills
      .filter((skill) => skill.category === meta.id)
      .map(({ name, level, percent, note }) => ({ name, level, percent, note })),
  }));
}

export function resolveAssetUrl(path: string): string {
  if (!path) return '';
  if (/^https?:\/\//i.test(path) || path.startsWith('data:') || path.startsWith('/')) {
    return path;
  }
  return `${import.meta.env.BASE_URL}${path}`;
}

export function loadPreviewStore(): PreviewStoreData {
  const defaults = getDefaultPortfolioSnapshot();
  const fallback: PreviewStoreData = {
    ...defaults,
    inquiries: [
      {
        id: 1,
        name: 'Sample Recruiter',
        email: 'hiring@example.com',
        subject: 'Software Engineering Internship Inquiry',
        message:
          'Hi Rei! We saw your Windsurf DSA Visualizer and QA background. Would love to connect about an internship role.',
        isRead: false,
        createdAt: new Date().toISOString(),
      },
    ],
    logs: [
      {
        id: 1,
        action: 'Preview session initialized (start PHP + MySQL for live persistence)',
        ipAddress: '127.0.0.1',
        createdAt: new Date().toISOString(),
      },
    ],
  };

  try {
    const raw = localStorage.getItem(PREVIEW_STORAGE_KEY);
    if (!raw) return fallback;
    const parsed = JSON.parse(raw) as Partial<PreviewStoreData>;
    return {
      profile: parsed.profile ? { ...defaults.profile, ...parsed.profile } : defaults.profile,
      skills: Array.isArray(parsed.skills) ? parsed.skills : defaults.skills,
      projects: Array.isArray(parsed.projects) ? parsed.projects : defaults.projects,
      inquiries: Array.isArray(parsed.inquiries) ? parsed.inquiries : fallback.inquiries,
      logs: Array.isArray(parsed.logs) ? parsed.logs : fallback.logs,
    };
  } catch {
    return fallback;
  }
}

export function savePreviewStore(data: PreviewStoreData): void {
  try {
    localStorage.setItem(PREVIEW_STORAGE_KEY, JSON.stringify(data));
  } catch {
    // Ignore storage quota issues in restricted browsers
  }
}

export function resetPreviewStore(): PreviewStoreData {
  try {
    localStorage.removeItem(PREVIEW_STORAGE_KEY);
  } catch {
    // Ignore
  }
  return loadPreviewStore();
}

export class ApiError extends Error {
  status: number;
  constructor(message: string, status: number) {
    super(message);
    this.status = status;
  }
}

async function apiFetch<T>(
  path: string,
  options: RequestInit & { csrfToken?: string; timeoutMs?: number } = {},
): Promise<T> {
  const { csrfToken, timeoutMs = 6000, headers, ...rest } = options;
  const controller = new AbortController();
  const timer = window.setTimeout(() => controller.abort(), timeoutMs);

  const mergedHeaders = new Headers(headers);
  if (!(rest.body instanceof FormData) && !mergedHeaders.has('Content-Type')) {
    mergedHeaders.set('Content-Type', 'application/json');
  }
  if (csrfToken) {
    mergedHeaders.set('X-CSRF-Token', csrfToken);
  }

  try {
    const response = await fetch(`${API_BASE}${path}`, {
      credentials: 'include',
      cache: 'no-store',
      signal: controller.signal,
      headers: mergedHeaders,
      ...rest,
    });

    const contentType = response.headers.get('content-type') || '';
    if (!contentType.includes('application/json')) {
      throw new ApiError('PHP API did not return JSON.', response.status || 503);
    }

    const data = (await response.json()) as T & { error?: string };
    if (!response.ok) {
      throw new ApiError(data.error || `Request failed (${response.status})`, response.status);
    }

    return data;
  } finally {
    window.clearTimeout(timer);
  }
}

export async function fetchPublicPortfolio(): Promise<{
  mode: BackendMode;
  snapshot: PortfolioSnapshot;
}> {
  try {
    const data = await apiFetch<{
      seeded: boolean;
      profile: Partial<PortfolioProfileData> | null;
      skills: DynamicSkill[];
      projects: DynamicProject[];
    }>('/portfolio.php', { method: 'GET', timeoutMs: 8000 });

    const defaults = getDefaultPortfolioSnapshot();
    if (!data.seeded || !data.profile) {
      return { mode: 'php', snapshot: defaults };
    }

    return {
      mode: 'php',
      snapshot: {
        profile: {
          ...defaults.profile,
          ...data.profile,
          socials: {
            ...defaults.profile.socials,
            ...(data.profile.socials ?? {}),
          },
          aboutParagraphs:
            Array.isArray(data.profile.aboutParagraphs) && data.profile.aboutParagraphs.length > 0
              ? data.profile.aboutParagraphs
              : defaults.profile.aboutParagraphs,
        },
        skills: data.skills,
        projects: data.projects,
      },
    };
  } catch {
    const preview = loadPreviewStore();
    return {
      mode: 'preview',
      snapshot: {
        profile: preview.profile,
        skills: preview.skills,
        projects: preview.projects,
      },
    };
  }
}

export async function checkAuthSession(): Promise<AuthStateResponse> {
  return apiFetch<AuthStateResponse>('/auth.php?action=me', { method: 'GET', timeoutMs: 3000 });
}

export async function loginAdmin(
  email: string,
  password: string,
  csrfToken: string,
): Promise<AuthStateResponse> {
  return apiFetch<AuthStateResponse>('/auth.php?action=login', {
    method: 'POST',
    csrfToken,
    body: JSON.stringify({ email, password }),
  });
}

export async function logoutAdmin(csrfToken: string): Promise<void> {
  await apiFetch('/auth.php?action=logout', {
    method: 'POST',
    csrfToken,
  });
}

export async function changeAdminPassword(
  currentPassword: string,
  newPassword: string,
  csrfToken: string,
): Promise<{ message: string }> {
  return apiFetch<{ message: string }>('/auth.php?action=change_password', {
    method: 'POST',
    csrfToken,
    body: JSON.stringify({ currentPassword, newPassword }),
  });
}

export async function saveProfileApi(
  profile: PortfolioProfileData,
  csrfToken: string,
): Promise<{ message: string }> {
  return apiFetch<{ message: string }>('/profile.php', {
    method: 'PUT',
    csrfToken,
    body: JSON.stringify({
      fullName: profile.fullName,
      firstName: profile.firstName,
      nickname: profile.nickname,
      role: profile.role,
      status: profile.status,
      location: profile.location,
      email: profile.email,
      university: profile.university,
      degree: profile.degree,
      graduation: profile.graduation,
      headlineSummary: profile.headlineSummary,
      aboutBio1: profile.aboutParagraphs[0] ?? '',
      aboutBio2: profile.aboutParagraphs[1] ?? '',
      githubUrl: profile.socials.github,
      linkedinUrl: profile.socials.linkedin,
      facebookUrl: profile.socials.facebook,
      portraitFile: profile.portraitFile,
    }),
  });
}

export async function fetchAdminSkills(): Promise<DynamicSkill[]> {
  const res = await apiFetch<{ skills: DynamicSkill[] }>('/skills.php', { method: 'GET' });
  return res.skills;
}

export async function createSkillApi(
  skill: Omit<DynamicSkill, 'id'>,
  csrfToken: string,
): Promise<DynamicSkill> {
  const res = await apiFetch<{ skill: DynamicSkill }>('/skills.php', {
    method: 'POST',
    csrfToken,
    body: JSON.stringify(skill),
  });
  return res.skill;
}

export async function updateSkillApi(
  id: number,
  skill: Omit<DynamicSkill, 'id'>,
  csrfToken: string,
): Promise<void> {
  await apiFetch(`/skills.php?id=${id}`, {
    method: 'PUT',
    csrfToken,
    body: JSON.stringify(skill),
  });
}

export async function deleteSkillApi(id: number, csrfToken: string): Promise<void> {
  await apiFetch(`/skills.php?id=${id}`, {
    method: 'DELETE',
    csrfToken,
  });
}

export async function fetchAdminProjects(): Promise<DynamicProject[]> {
  const res = await apiFetch<{ projects: DynamicProject[] }>('/projects.php', { method: 'GET' });
  return res.projects;
}

export async function createProjectApi(
  project: Omit<DynamicProject, 'id' | 'dbId'>,
  csrfToken: string,
): Promise<DynamicProject> {
  const res = await apiFetch<{ project: DynamicProject }>('/projects.php', {
    method: 'POST',
    csrfToken,
    body: JSON.stringify(project),
  });
  return res.project;
}

export async function updateProjectApi(
  id: number,
  project: Omit<DynamicProject, 'id' | 'dbId'>,
  csrfToken: string,
): Promise<void> {
  await apiFetch(`/projects.php?id=${id}`, {
    method: 'PUT',
    csrfToken,
    body: JSON.stringify(project),
  });
}

export async function deleteProjectApi(id: number, csrfToken: string): Promise<void> {
  await apiFetch(`/projects.php?id=${id}`, {
    method: 'DELETE',
    csrfToken,
  });
}

export async function submitPublicInquiry(payload: {
  name: string;
  email: string;
  subject: string;
  message: string;
  website?: string;
}): Promise<{ id: number; message: string }> {
  return apiFetch<{ id: number; message: string }>('/inquiries.php', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
}

export async function fetchAdminInquiries(page = 1, perPage = 10): Promise<{
  inquiries: InquiryItem[];
  total: number;
  unread: number;
  page: number;
  totalPages: number;
}> {
  return apiFetch(`/inquiries.php?page=${page}&per_page=${perPage}`, { method: 'GET' });
}

export async function toggleInquiryReadApi(
  id: number,
  isRead: boolean,
  csrfToken: string,
): Promise<void> {
  await apiFetch(`/inquiries.php?id=${id}`, {
    method: 'PATCH',
    csrfToken,
    body: JSON.stringify({ isRead }),
  });
}

export async function deleteInquiryApi(id: number, csrfToken: string): Promise<void> {
  await apiFetch(`/inquiries.php?id=${id}`, {
    method: 'DELETE',
    csrfToken,
  });
}

export async function fetchAdminLogs(page = 1, perPage = 15): Promise<{
  logs: ActivityLogItem[];
  total: number;
  page: number;
  totalPages: number;
}> {
  return apiFetch(`/logs.php?page=${page}&per_page=${perPage}`, { method: 'GET' });
}

export async function uploadImageApi(file: File, csrfToken: string): Promise<{ url: string }> {
  const formData = new FormData();
  formData.append('image', file);
  return apiFetch<{ url: string }>('/upload.php', {
    method: 'POST',
    csrfToken,
    body: formData,
  });
}
