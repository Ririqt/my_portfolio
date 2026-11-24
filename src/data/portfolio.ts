export type ProjectCategory = 'All' | 'Web App' | 'Full-Stack' | 'UI & Frontend';

export type ProjectVisualType = 'windsurf' | 'cms' | 'weather' | 'calculator';

export interface ProjectItem {
  id: string;
  title: string;
  subtitle: string;
  category: Exclude<ProjectCategory, 'All'>;
  status: 'Live' | 'Completed' | 'In Progress';
  featured?: boolean;
  year: string;
  role: string;
  summary: string;
  highlights: string[];
  tech: string[];
  visualType: ProjectVisualType;
  liveUrl?: string;
  repoUrl?: string;
}

export interface SkillItem {
  name: string;
  level: 'Proficient' | 'Comfortable' | 'Foundational';
  percent: number;
  note: string;
}

export interface SkillCategory {
  id: 'languages' | 'tools' | 'systems';
  label: string;
  description: string;
  items: SkillItem[];
}

export interface ExperienceItem {
  role: string;
  organization: string;
  period: string;
  type: string;
  summary: string;
  bullets: string[];
}

export interface OrganizationItem {
  name: string;
  acronym: string;
  period: string;
  focus: string;
}

export interface CertificationItem {
  title: string;
  issuer: string;
  date: string;
  topic: string;
}

export const PROFILE = {
  fullName: 'Mharc Reinald Rigos',
  firstName: 'Mharc Reinald',
  nickname: 'Rei',
  role: 'Computer Engineering Student & Web Developer',
  status: 'Open to Software & Web Internships',
  location: 'Pasig City, Philippines',
  email: 'mcrei1018@gmail.com',
  university: 'Polytechnic University of the Philippines',
  degree: 'B.S. in Computer Engineering',
  graduation: 'Expected September 2027',
  resumeFile: 'resume.pdf',
  portraitFile: 'images/rei-portrait.jpg',
  /**
   * Optional: Add your Formspree form ID (e.g. "https://formspree.io/f/your_id")
   * to handle contact form submissions directly without a backend server.
   * When left empty, the form validates input and opens a pre-filled email draft.
   */
  formspreeEndpoint: '',
  headlineSummary:
    'Computer Engineering student at PUP combining software development, web design, and hands-on QA testing to build clean, responsive, and dependable applications.',
  aboutParagraphs: [
    "I'm Mharc Reinald Rigos — most people call me Rei. I'm pursuing a Bachelor of Science in Computer Engineering at the Polytechnic University of the Philippines (Class of 2027), with a strong focus on software engineering, web development, and system integration.",
    'From visualizing data structures and sorting algorithms in the browser to testing web applications across devices for broken links and usability issues, I enjoy bridging thoughtful user interfaces with solid engineering fundamentals.',
  ],
  socials: {
    github: 'https://github.com/Ririqt',
    linkedin: 'https://www.linkedin.com/in/reirigos1018/',
    facebook: 'https://www.facebook.com/Mcreingggg218',
  },
} as const;

export const QUICK_METRICS = [
  { label: 'Degree Track', value: 'BS CpE', sublabel: 'PUP · Class of 2027' },
  { label: 'Core Focus', value: 'Web & QA', sublabel: 'Frontend, Python & Testing' },
  { label: 'Student Orgs', value: '3 Active', sublabel: 'GDSC, AWS Cloud, MRSP' },
] as const;

export const COURSEWORK = [
  'Data Structures & Algorithms',
  'Object-Oriented Programming',
  'Operating Systems',
  'Programming, Logic & Design',
  'Computer Hardware Fundamentals',
  'Engineering Data Analysis',
  'Electrical & Electronic Circuits',
  'Computer-Aided Drafting',
] as const;

export const SKILL_CATEGORIES: SkillCategory[] = [
  {
    id: 'languages',
    label: 'Languages & Web',
    description: 'Core programming languages and web fundamentals used across academic and personal builds.',
    items: [
      { name: 'HTML5 & Semantic Markup', level: 'Proficient', percent: 85, note: 'Accessible structure, forms, SEO metadata' },
      { name: 'CSS3 & Responsive Design', level: 'Proficient', percent: 80, note: 'Flexbox, CSS Grid, Tailwind CSS, mobile-first layouts' },
      { name: 'Python', level: 'Comfortable', percent: 72, note: 'Scripting, data structures, algorithm tooling' },
      { name: 'JavaScript / TypeScript', level: 'Comfortable', percent: 68, note: 'DOM interactivity, React + Vite UI architecture' },
      { name: 'Java', level: 'Comfortable', percent: 65, note: 'Object-oriented design & academic engineering labs' },
      { name: 'PHP & MySQL', level: 'Foundational', percent: 55, note: 'Server-side CRUD, session auth, relational schemas' },
      { name: 'C++', level: 'Foundational', percent: 45, note: 'Core syntax, logic, and systems coursework' },
    ],
  },
  {
    id: 'tools',
    label: 'Tools & Platforms',
    description: 'Daily development environments, design utilities, and engineering CAD software.',
    items: [
      { name: 'Visual Studio Code', level: 'Proficient', percent: 90, note: 'Primary code editor, debugging & extensions' },
      { name: 'Git & GitHub', level: 'Comfortable', percent: 75, note: 'Branching workflows, repositories & GitHub Pages' },
      { name: 'PyCharm', level: 'Comfortable', percent: 75, note: 'Python project development & testing' },
      { name: 'TinkerCAD & AutoCAD', level: 'Comfortable', percent: 70, note: 'Circuit simulation & computer-aided drafting' },
      { name: 'MS Office Suite', level: 'Proficient', percent: 85, note: 'Technical documentation, QA reports & presentations' },
    ],
  },
  {
    id: 'systems',
    label: 'QA, Systems & Hardware',
    description: 'Practical testing, OS maintenance, and computer engineering laboratory skills.',
    items: [
      { name: 'Website QA & Cross-Browser Testing', level: 'Proficient', percent: 85, note: 'Functional checks, link audits, UX bug reporting' },
      { name: 'Windows OS Setup & Troubleshooting', level: 'Proficient', percent: 85, note: 'OS installation, configuration & PC maintenance' },
      { name: 'Circuit Analysis & Breadboarding', level: 'Comfortable', percent: 70, note: 'Electrical/electronic prototyping & diagnostics' },
      { name: 'Photo & Video Editing', level: 'Comfortable', percent: 70, note: 'Visual media prep for web and presentations' },
    ],
  },
];

export const PROJECT_CATEGORIES: ProjectCategory[] = [
  'All',
  'Web App',
  'Full-Stack',
  'UI & Frontend',
];

export const PROJECTS: ProjectItem[] = [
  {
    id: 'windsurf-dsa',
    title: 'Windsurf — DSA Visualizer',
    subtitle: 'Interactive Data Structure & Sorting Algorithm Web App',
    category: 'Web App',
    status: 'Live',
    featured: true,
    year: '2025',
    role: 'Front-end & Back-end Developer',
    summary:
      'A web-based learning platform that animates core data structures and sorting algorithms step-by-step to make computer science fundamentals intuitive.',
    highlights: [
      'Visualizes Linked Lists, Stacks, Queues, Graphs, and Binary Trees interactively.',
      'Animates Quick Sort, Merge Sort, Insertion Sort, Selection Sort, and Bubble Sort.',
      'Led UI design improvements, layout structure, and UX feature ideas.',
    ],
    tech: ['HTML5', 'CSS3', 'JavaScript', 'Python', 'Render'],
    visualType: 'windsurf',
    liveUrl: 'https://windsurf.onrender.com/',
    repoUrl: 'https://github.com/Ririqt',
  },
  {
    id: 'portfolio-cms',
    title: 'Portfolio & Full-Stack CMS',
    subtitle: 'Personal Portfolio + Admin Dashboard Architecture',
    category: 'Full-Stack',
    status: 'Completed',
    featured: true,
    year: '2025–2026',
    role: 'Full-Stack Developer',
    summary:
      'Started as a PHP & MySQL portfolio CMS with user authentication, activity logs, inquiry pagination, and CRUD modules—now modernized into a blazing-fast Vite + React + TypeScript build.',
    highlights: [
      'Implemented multi-user session authentication, About/Skills/Projects CRUD, and file uploads in PHP & MySQL.',
      'Re-architected the public showcase using React, TypeScript, Tailwind CSS, and GitHub Actions CI/CD.',
      'Eliminated 15+ MB of unused assets for instant static loading on GitHub Pages, Vercel, or Netlify.',
    ],
    tech: ['React', 'TypeScript', 'Vite', 'Tailwind CSS', 'PHP', 'MySQL'],
    visualType: 'cms',
    repoUrl: 'https://github.com/Ririqt/my_portfolio',
  },
  {
    id: 'weather-dashboard',
    title: 'Weather Forecasting System',
    subtitle: 'Clean Real-Time & Multi-Day Forecast Interface',
    category: 'UI & Frontend',
    status: 'In Progress',
    year: '2025',
    role: 'Front-end Developer',
    summary:
      'A sleek weather monitoring UI structured around current conditions, air metrics (wind, humidity, feels-like), hourly intervals, and a weekly outlook.',
    highlights: [
      'Designed clear visual hierarchy for rapid scanning of temperature and forecast cards.',
      'Structured modular forecast rows and search-ready city lookup flow.',
      'Responsive grid architecture built for desktop and mobile viewports.',
    ],
    tech: ['HTML5', 'CSS3', 'JavaScript', 'UI Design'],
    visualType: 'weather',
    repoUrl: 'https://github.com/Ririqt',
  },
  {
    id: 'simple-calculator',
    title: 'Interactive Web Calculator',
    subtitle: 'Responsive Arithmetic Utility with Tactile UI',
    category: 'UI & Frontend',
    status: 'Completed',
    year: '2025',
    role: 'Front-end Developer',
    summary:
      'A focused, functional web calculator emphasizing crisp spacing, instant button feedback, and clean expression display.',
    highlights: [
      'Clean state handling for chained operations, decimals, and clear/reset actions.',
      'High-contrast keypad grid crafted for both mouse clicks and touch screens.',
    ],
    tech: ['HTML5', 'CSS3', 'JavaScript'],
    visualType: 'calculator',
    repoUrl: 'https://github.com/Ririqt',
  },
];

export const EXPERIENCE: ExperienceItem[] = [
  {
    role: 'Website Tester / QA',
    organization: 'CITYSTYLE IT Solutions Corp.',
    period: '2025',
    type: 'Quality Assurance',
    summary:
      'Evaluated web applications end-to-end to safeguard user experience, performance, and cross-browser stability before release.',
    bullets: [
      'Audited websites for functional defects, broken navigation links, and layout inconsistencies.',
      'Verified cross-browser and multi-device responsiveness across desktop and mobile viewports.',
      'Documented clear, reproducible issue reports to help developers resolve defects quickly.',
    ],
  },
];

export const ORGANIZATIONS: OrganizationItem[] = [
  {
    name: 'Google Developer Student Club',
    acronym: 'GDSC',
    period: 'April 2023 – Present',
    focus: 'Developer community, web technologies & peer workshops',
  },
  {
    name: 'AWS Cloud Club PUP',
    acronym: 'AWS',
    period: 'October 2024 – Present',
    focus: 'Cloud architecture, modern deployment & developer tooling',
  },
  {
    name: 'Mechatronics & Robotics Society of the Philippines PUP',
    acronym: 'MRSP',
    period: 'October 2024 – Present',
    focus: 'Embedded hardware, robotics & interdisciplinary engineering',
  },
];

export const CERTIFICATIONS: CertificationItem[] = [
  {
    title: 'The Future is Now: Seminar 2025',
    issuer: 'Cybersecurity & UI/UX Track',
    date: '2025',
    topic: 'Security & UI/UX',
  },
  {
    title: 'Mechatronics and Robotics for Sustainable Nuclear Energy Management',
    issuer: 'Engineering Seminar',
    date: 'October 2024',
    topic: 'Robotics & Systems',
  },
  {
    title: 'Web Development and Introduction to HTML',
    issuer: 'DICT (Dept. of Information and Communications Technology)',
    date: 'Training',
    topic: 'Web Foundations',
  },
];
