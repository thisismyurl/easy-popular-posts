=== Easy Popular Posts ===
Contributors: christopherross
Tags: popular posts, popular, sidebar, widget
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 26.05.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An easy-to-use WordPress widget and shortcode to add popular posts to any theme.

== Description ==

**Easy Popular Posts** lets you display your most popular posts in any sidebar or widget area. Popularity is ranked by pageviews or comment count — no external service required.

Features:

* Sidebar widget and `[thisismyurl_easy_popular_posts]` shortcode
* Rank by pageviews or comment count
* Optional featured image, excerpt, and nofollow support
* No external API calls — all data stored locally in post meta
* Multilingual (en, de_DE, fr_FR, fr_CA)

== Installation ==

1. Upload the plugin to `/wp-content/plugins/`.
2. Activate it through **Plugins > Installed Plugins**.
3. Add the **Easy Popular Posts** widget to a sidebar or use the shortcode `[thisismyurl_easy_popular_posts]`.

== Frequently Asked Questions ==

= How is popularity tracked? =

On every single-post page load, the plugin increments a pageview counter stored in post meta (`_easy-popular-posts-pageviews`). Comment counts are read directly from WordPress.

= Can I style the output? =

Yes. The widget outputs a `<ul>` with class `widget_thisismyurl_popular_posts`. Add CSS in your theme.

== Changelog ==

= 26.05.0 =
* Removed all dead social-share API calls (Twitter/Facebook/LinkedIn/StumbleUpon APIs are shut down).
* Removed social display methods from widget dropdown; ranking is now pageviews or comments only.
* Migrated widget to `parent::__construct()` and `$this->get_field_id()` / `$this->get_field_name()`.
* Fixed `posts_per_page` typo (was `post_per_page`) in `get_posts()` args.
* Fixed variable reference in featured-image lookup.
* Hardened widget `update()` with `sanitize_text_field`, `absint`, and allowlist for `display_method`.
* Replaced `extract($args)` in widget `widget()` with direct array access.
* Renamed `langs/` to `languages/`; fixed `load_plugin_textdomain()` path.
* Added fr_CA translation.
* Updated all HTTP URIs to HTTPS.
* Bumped "Requires at least" to 6.0 and "Requires PHP" to 7.4.

= 15.01.12 =
* Minor maintenance release.

= 15.01 =
* Initial public release.

== Upgrade Notice ==

= 26.05.0 =
This release removes all deprecated social-share API integrations. Existing pageview and comment counts are preserved.

== Screenshots ==

1. Widget settings panel in the WordPress customiser.

