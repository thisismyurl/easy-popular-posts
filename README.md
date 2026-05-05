# Easy Popular Posts

Display your most popular posts by pageviews or comment count. No external services, no tracking scripts — all data stored locally in WordPress post meta.

[![WordPress Plugin](https://img.shields.io/badge/WordPress-6.0%2B-blue)](https://wordpress.org/plugins/easy-popular-posts/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4)](https://php.net/)
[![License: GPL v2](https://img.shields.io/badge/License-GPLv2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

## Features

- Widget and `[thisismyurl_easy_popular_posts]` shortcode
- Rank by pageviews or comment count
- Optional featured images, excerpts, and `rel="nofollow"` support
- Multilingual: English, German, French (France), French (Canada)
- No external API calls, no cookies, no GDPR concerns

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher

## Installation

1. Upload to `/wp-content/plugins/easy-popular-posts/`.
2. Activate through **Plugins › Installed Plugins**.
3. Add the **Easy Popular Posts** widget to a sidebar, or use the shortcode in any post or page.

## How It Works

On every single-post page load the plugin increments a counter in post meta (`_easy-popular-posts-pageviews`). Comment counts read directly from WordPress core data. No cookies, no external APIs.

## Shortcode

```
[thisismyurl_easy_popular_posts]
```

## Template Tag

```php
<?php thisismyurl_easy_popular_posts(); ?>
```

## Support

- **WordPress.org forum:** https://wordpress.org/support/plugin/easy-popular-posts/
- **Bug reports / feature requests:** [GitHub Issues](https://github.com/thisismyurl/easy-popular-posts/issues)

## Changelog

See [releases](https://github.com/thisismyurl/easy-popular-posts/releases) or [readme.txt](readme.txt).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).

---

**Author:** [Christopher Ross](https://thisismyurl.com) — WordPress specialist since 2007.  
**Sponsor:** https://github.com/sponsors/thisismyurl
