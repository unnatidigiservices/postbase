# Changelog

## 0.12.1 — 2026-09-24

- **Security hardening:** PostBase now recreates its protective `.htaccess` files (`data/`, `uploads/`, `lib/`) whenever they're missing. Dot-files are often silently skipped by FTP clients, zip tools and GitHub's web uploader, which could leave the SQLite database folder unprotected on a fresh install.

## 0.12.0 — 2026-09-24

**Write anywhere, paste without pain.**

- **Paste from anywhere.** A new paste engine (`assets/paste.js`) recognises where the content came from and keeps its structure:
  - **Word:** headings, bold and italic, links, tables, and real (nested) bulleted and numbered lists. Word's fake "·" bullets are rebuilt as lists.
  - **Google Docs:** fixes the whole document pasting as bold, keeps bold and italic from styled text, keeps headings and lists, and removes Google redirect links.
  - **WordPress (Gutenberg):** normal copy and "Copy block" / "Copy all blocks" both work. Embeds become playable videos, and images keep their left/right alignment and captions. Galleries, tables, pullquotes, buttons and columns are converted cleanly.
  - **Web pages and Notion:** YouTube and Vimeo iframes become PostBase videos, and everything else is cleaned to simple HTML.
  - **Markdown** from any editor (VS Code, Obsidian, iA Writer, Typora, ChatGPT, GitHub): headings (the top level maps to H2), bold, italic, strikethrough, highlight, links, images, code and code blocks, quotes, nested lists, task lists, tables and horizontal lines.
  - **Plain text:** paragraphs, line breaks and clickable links. A YouTube or Vimeo link on its own line becomes a video.
- **Pasted images are saved to your blog.** Images from Google Docs, WordPress and web pages, and copied or screenshot images, are copied into `uploads/`, so posts never break when the original link expires. Images that exist only on your computer (Word `file://` links) are flagged, not silently lost. Import is SSRF-safe: public http(s) only, the connection is pinned to the checked IP, redirects are checked at every hop, and size and type are validated.
- **Drag and drop** image files into the editor.
- **Markdown shortcuts while typing:** `## ` heading, `- ` list, `1. ` numbered, `> ` quote, `**bold**`, `*italic*`, `` `code` ``, `~~strike~~`, `---` + Enter for a line, and ```` ``` ```` + Enter for a code block.
- postbase.top is credited in the CSS/JS headers, the inline design CSS, a `generator` meta tag and the RSS `<generator>`.

## 0.11.1 — 2026-09-23

- Project links: the admin footer and Settings → System link to [postbase.top](https://postbase.top) for help and to [GitHub Issues](https://github.com/unnatidigiservices/postbase/issues) for bugs. "Powered by" in the standalone footer points to postbase.top.
- New SECURITY.md; README, CONTRIBUTING and COMMERCIAL-LICENSE updated with the project's origin and support channels.

## 0.11.0 — 2026-09-23

- **Pages.** Any entry can be a *Page* instead of a blog post, for About, Contact, Support and similar. Pages use the same editor, SEO fields and review workflow, get clean URLs (`/about/`), and render without date, author, category or prev/next. They're left out of the post list, RSS, categories and search, but are in the sitemap. Editors and Admins create them from the new **Pages** screen, and published pages appear in Settings → Navigation → Quick add.
- **Pinned posts.** Editors can pin a post as *Featured*. Up to 3 pinned posts show as a large card at the top of the blog home and aren't repeated in the list below. Category pages are unaffected.
- **Domain-root installs** (e.g. `postbase.top`): robots.txt is served when missing, "Add sitemap to robots.txt" writes to the right folder, the default menu is Home and RSS, and `tools/`, `docs/` and `dist/` are blocked.
- Database schema v2, upgraded automatically on the first request.

## 0.10.0 — 2026-09-23

- New Unnati PostBase logo, favicon and wordmark.
- Editor: image options bar with wrap (left, right, centre), size (300, 500, 760 px or full), alt text and caption. Wrapped images float on desktop and stack on phones with automatic height.
- Editor: YouTube and Vimeo embeds now play (they send the referrer YouTube requires), sit in a responsive frame, and can be selected and removed.
- Editor: 10px side padding, a primary-coloured Save button, and the HTML view no longer shows a stray textarea and file-upload box.
- Post cards and share previews use the featured image, else the first image in the post, else the default social image.
- Settings are split into General, Design and Navigation tabs.
  - Design: default social share image, blog favicon, body and heading fonts, text size, and accent, text, background and card colours. Each is optional and falls back to the site theme.
  - Navigation: an editable menu (default Home, Blog, Contact) with reordering, quick-add and new-tab links.
- The RSS feed now carries summaries only (meta description or the first 250 characters) with a permalink, not full posts.
- `tools/build-release.php` builds the checksummed release that GeoRank's Control Center installs from.

## 0.9.0 — 2026-09-23 (first public beta)

- Public blog: post list with pagination, single posts, categories, search, RSS 2.0, XML sitemap, clean URLs with query-string fallback.
- SEO: canonical, meta description, Open Graph and Twitter cards, `Blog` and `BlogPosting` JSON-LD, reading time, prev/next links.
- Editorial workflow: Contributor, Editor and Admin roles; submit, withdraw, approve, request changes (with notes), schedule, unpublish, archive, restore and delete; full per-post activity log.
- Editor: contenteditable toolbar (headings, lists, links, quotes, images, YouTube/Vimeo, HTML view), clean paste, cover image, excerpt, SEO fields, Ctrl+S.
- Security: server-side HTML allowlist sanitizer, CSRF on every form, `password_hash`, login throttling, locked `data/` and `lib/`, non-executable `uploads/`.
- GeoRank integration: shared-session sign-in, the site's header, footer, meta-global and theme assets, theme-token CSS, robots.txt sitemap helper.
- Storage: single-file SQLite with automatic migrations (`PRAGMA user_version`).
