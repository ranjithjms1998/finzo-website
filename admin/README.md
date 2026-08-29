# Finzo Admin Panel

A separate CodeIgniter 4 application for managing leads (Apply Now submissions) and contact messages from the public Finzo Financial Services website. Lives entirely in this `admin/` folder — the public site has no dependency on it beyond writing rows into the same database (see "How it connects to the public site" below).

## Access

- **URL:** `http://localhost/finzo_website/admin/public/` (redirects to `/login` if not signed in)
- Also linked from the public site's footer ("Admin Login", bottom-right of the footer bar on every page).

## Default login

```
Username: admin
Password: <shared with you separately — not committed to this repo>
```

**Change this password immediately after first login** via Account Settings in the sidebar.

If you don't have the initial password, reset it directly against the database:

```
php -r "echo password_hash('YourNewPassword', PASSWORD_BCRYPT), PHP_EOL;"
```

```sql
UPDATE admin_users SET password_hash = '<paste hash here>' WHERE username = 'admin';
```

## Demo data

The database currently contains **18 sample leads and 10 sample contact messages** (clearly fake data — every email ends in `@example.com`) spread over the last ~35 days, with realistic status distributions and full status-history trails, purely so the dashboard/reports charts and lead timeline have something to show for client demos. To clear it before going live with real data:

```sql
DELETE FROM lead_status_history;
DELETE FROM leads;
DELETE FROM contact_messages;
```

(Run via `"D:/xampp/mysql/bin/mysql.exe" -u root finzo_admin -e "<the above>"`, or through the panel's own list pages if a delete action is added later — currently there is no delete UI, only status updates, by design, since deleting a real customer's enquiry record isn't something the panel should make easy.)

## Tech stack

- **CodeIgniter 4.7** (installed via Composer — see `composer.json` / `vendor/`)
- **MySQL / MariaDB** (via XAMPP) — database name `finzo_admin`
- **Bootstrap 5.3.3 + Bootstrap Icons** (CDN) — same versions as the public site, for a consistent look
- **Chart.js 4.4.4** (CDN) — dashboard and report charts
- Custom navy/gold theme (`public/assets/css/admin.css`) matching the public site's brand identity

## Features

- **Login** — session-based auth, bcrypt password hashing, brute-force throttling (8 attempts / 5 min per IP), CSRF protection on all forms.
- **Dashboard** — stat cards (total leads, new leads, approved leads, contact messages), a 14-day leads trend chart, leads-by-status and leads-by-loan-type charts, contact-messages-by-status chart, and recent-activity tables.
- **Leads** — searchable/filterable (status, loan type, date range) paginated list; a detail page per lead with all submitted fields, a status-update form (New → Contacted → In Progress → Approved / Rejected → Closed), and a **status history timeline** so you can see exactly how a lead progressed and who changed what, when.
- **Contact Messages** — same filtering/pagination pattern; detail page with quick Call/Email actions and a status workflow (New → Read → Responded → Closed).
- **Reports** — date-range-scoped stat cards (totals, approval rate, average loan amount requested), 5 charts, and one-click **CSV export** for both leads and contact messages (respecting the selected date range).
- **Account Settings** — change your own password.

## Database

Schema is in `database_schema.sql` at the root of this folder — run it once against MySQL to (re)create the `finzo_admin` database and its 4 tables (`admin_users`, `leads`, `lead_status_history`, `contact_messages`) if setting this up fresh elsewhere:

```
"D:/xampp/mysql/bin/mysql.exe" -u root < admin/database_schema.sql
```

Connection settings live in `.env` (`database.default.*`) — currently `root` with no password, matching XAMPP's default local MySQL setup.

To seed a first admin user by hand (e.g. on a fresh install), generate a bcrypt hash and insert it:

```
php -r "echo password_hash('YourNewPassword', PASSWORD_BCRYPT), PHP_EOL;"
```

```sql
INSERT INTO admin_users (username, email, password_hash, full_name, role, is_active)
VALUES ('admin', 'you@example.com', '<paste hash here>', 'Your Name', 'admin', 1);
```

## How it connects to the public site

The public site (outside this `admin/` folder) is entirely unaware of CodeIgniter — it only writes rows into the same `finzo_admin` MySQL database:

- `mail/db.php` (public site) — a small standalone `mysqli` connector.
- `mail/send-apply.php` — after validating an Apply Now submission and before sending email, inserts a row into `leads` (status `new`) and a matching first entry into `lead_status_history`. This is **best-effort**: if the database is unreachable, the error is logged and the request continues on to email sending as before — a lead is never silently lost on both fronts unless email also fails (which is already surfaced to the site visitor).
- `mail/send-contact.php` — same pattern, inserts into `contact_messages`.

This means leads/messages appear in the admin panel independently of whether email delivery succeeds, and independently of whether this CodeIgniter app is even running at that moment — it's a one-way write from the public site into shared storage that the admin panel later reads.

## Security notes

- `admin/.htaccess` denies direct web access to everything in this folder except `admin/public/` (which re-grants access via its own `.htaccess`) — this prevents `.env`, `composer.json`, `vendor/`, etc. from being downloadable. **This matters**: it was initially missing and `.env` (containing DB credentials) was directly fetchable over HTTP before this was added — verify it's still in place if you ever restructure this folder.
- `CI_ENVIRONMENT` in `.env` is set to `production`, which disables CodeIgniter's debug toolbar and verbose error pages. Only switch it to `development` for local debugging, never on anything reachable outside your own machine.
- Sessions regenerate their ID on login (session-fixation protection); passwords are bcrypt-hashed (`password_hash`/`password_verify`), never stored or logged in plain text anywhere in this app.
- All user-supplied output (lead/contact names, messages, notes) is escaped with CI4's `esc()` in every view — these fields are attacker-controllable via the public website's forms.

## Folder structure

```
admin/
├── .env                       Environment config (DB, baseURL, environment) — NOT web-accessible
├── .htaccess                  Blocks direct access to everything except public/
├── database_schema.sql        Reference schema for (re)creating the database
├── app/
│   ├── Config/                Routes, Filters (auth), App, Database
│   ├── Controllers/           Auth, Dashboard, Leads, Contacts, Reports, Account
│   ├── Filters/                AuthFilter.php — protects every route except /login
│   ├── Models/                  AdminUserModel, LeadModel, LeadStatusHistoryModel, ContactMessageModel
│   └── Views/
│       ├── layout/              auth.php (login shell), admin.php (sidebar + topbar shell)
│       ├── auth/, dashboard/, leads/, contacts/, reports/, account/
├── public/                    Web root — public/index.php is the front controller
│   └── assets/css/admin.css   Navy/gold admin theme
└── vendor/                    Composer dependencies (CodeIgniter framework)
```
