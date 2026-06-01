# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What This Is

A standalone WordPress theme named **News 1** (text domain: `news-1`, function prefix: `news1_`). It is deployed by dropping this directory into `wp-content/themes/`. There is no build step — no npm, no webpack, no compilation.

**Requirements:** PHP 7.4+, WordPress 5.8+, Classic Editor plugin, Rank Math SEO plugin (handles all meta tags — the theme does not emit its own). The PHP **GD extension** must be enabled on the host for image resizing to work.

## Theme Architecture

| File / Dir | Role |
|---|---|
| `functions.php` | Theme setup, enqueue, helpers, comment config, CPT registration |
| `inc/stats-telegram.php` | Visitor tracking + daily Telegram report via WP-Cron |
| `front-page.php` | Homepage: hero grid → latest posts → per-category sections |
| `single.php` | Article: sticky related sidebar, share buttons, breadcrumb, comments |
| `header.php` | Top bar (date/social), logo/search, primary nav, secondary nav (trending marquee + latest links), breadcrumb |
| `footer.php` | Footer menus, social links, two widget columns |
| `template-parts/card-post.php` | Reusable post card (thumbnail + category label + title + meta) |
| `assets/js/main.js` | jQuery-dependent: hamburger menu, copy-link button, comment load-more |
| `assets/js/priority-nav.js` | Responsive nav overflow handler |
| `style.css` | All theme CSS; CSS custom properties defined in `:root` |

## Key Conventions

**Function / hook prefix:** all theme functions use `news1_` (e.g. `news1_setup`, `news1_get_thumbnail`).

**Image sizes registered:**
- `news-hero` 800×420 (cropped) — main hero slot
- `news-card` 420×250 (cropped) — grid cards
- `news-thumb` 160×100 (cropped) — related / list items
- `news-wide` 1200×500 (cropped) — single post featured image

Always use `news1_get_thumbnail( $post_id, $size, $attr )` rather than `get_the_post_thumbnail()` — it falls back to a placeholder when no thumbnail exists.

**Post view counting:** `_post_views_count` (all-time int) and `_post_views_YYYYMMDD` (daily int) are stored as post meta. `news1_count_views()` is called on `wp_head` for singular posts. `news1_trending_query( $count )` merges by-view-count posts with latest posts to fill any gap.

**Visitor stats:** tracked on every front-end page load in `news1_track_visit()`. Stored in WordPress options under keys `news1_stats_YYYYMMDD` (daily, array with `pageviews`, `unique`, `countries`) and `news1_stats_monthly_YYYYMM`. Geo lookup uses `ip-api.com` with a 30-day transient cache per IP.

**Telegram daily report:** `news1_send_daily_report()` is scheduled via WP-Cron at 8 AM site timezone. Bot token and chat ID are defined as constants in `inc/stats-telegram.php`. A manual trigger page is available under WP Admin → Tools → Telegram Stats.

## WordPress Admin Setup Needed

After activating the theme, configure these in wp-admin:

- **Appearance → Menus:** create menus and assign to `primary` and `footer` locations
- **Appearance → Customize → Social Media Links:** set Facebook, X (Twitter), Reddit URLs
- **Appearance → Widgets:** populate `Footer Column 1` and `Footer Column 2` sidebars
- **Settings → Reading:** set front page to a static page; assign "News 1 Front Page" template
- Create static pages for `terms-of-use`, `privacy-policy`, and `contact` (use the `page-contact.php` template for contact)
- **Contact page template** (`page-contact.php`) saves submissions as the `contact_message` CPT — viewable under the "Contact Messages" admin menu

## Comment System

Comments require only a name and message body (email, URL, cookies fields are removed). No login required. Auto-approved (no moderation queue). Threaded to depth 5. Load-more (10 at a time) is handled in `assets/js/main.js`.
