# Contributing to Easy Popular Posts

Thanks for taking the time to contribute!

## How to Report Bugs

Open a [GitHub Issue](https://github.com/thisismyurl/easy-popular-posts/issues) with:
- Plugin version
- WordPress version and PHP version
- Steps to reproduce
- Expected vs actual behaviour

## How to Suggest Features

Open a [GitHub Issue](https://github.com/thisismyurl/easy-popular-posts/issues) labelled **enhancement**. Describe the use case before proposing implementation details.

## Pull Requests

1. Fork the repo and create a feature branch from `main`.
2. Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).
3. Run `php -l` on every changed file before submitting.
4. Write a clear commit message: `what changed and why`.
5. Open a PR against `main` with a description of what was changed and why.

## Code Standards

- PHP 7.4+ compatible syntax only.
- All output must be escaped; all input must be sanitised.
- Translatable strings must use the plugin text domain `easy-popular-posts`.
- No external HTTP calls inside plugin execution.

## Licence

By contributing you agree your code will be licensed under [GPL-2.0-or-later](LICENSE).
