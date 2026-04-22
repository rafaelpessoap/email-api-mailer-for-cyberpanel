# Contributing

Thanks for your interest in improving **Email API Mailer for Cyberpanel**. Contributions of every size are welcome.

## Ground rules

- Be respectful. This project follows a standard open-source community code of conduct even when one is not shipped verbatim.
- Keep pull requests focused. Small, single-purpose PRs are reviewed and merged faster than sprawling ones.
- Open an issue first when the change is non-trivial so we can align on scope before code is written.

## Development setup

This plugin is intentionally single-file. You do not need Composer or npm to work on it.

```bash
git clone https://github.com/rafaelpessoap/email-api-mailer-for-cyberpanel.git
cd email-api-mailer-for-cyberpanel
```

For local testing, symlink or copy the folder into a WordPress instance's `wp-content/plugins/` directory and activate it from the admin panel.

## Coding standards

- Target **PHP 7.4+** and **WordPress 5.7+**.
- Follow the [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/). A `phpcs.xml.dist` is included for automated checks.
- Every user-facing string must be translatable via `__()`, `esc_html__()`, `esc_attr__()` or `_e()` with the text domain `email-api-mailer-for-cyberpanel`.
- Every output must be escaped at the point of output (`esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()` as appropriate).
- Every `$_GET` / `$_POST` / `$_REQUEST` access must pass through `wp_unslash()` and a sanitization function.
- Every admin action must be guarded by `current_user_can()` and a nonce.

## Running the linter

```bash
composer global require "squizlabs/php_codesniffer=*" "wp-coding-standards/wpcs=*"
phpcs --standard=phpcs.xml.dist email-api-mailer-for-cyberpanel.php uninstall.php
```

The same checks run automatically on every pull request via GitHub Actions.

## Translations

- Source strings live in `email-api-mailer-for-cyberpanel.php` (English).
- The translation template is `languages/email-api-mailer-for-cyberpanel.pot`.
- Per-locale files go in `languages/email-api-mailer-for-cyberpanel-{locale}.po` with the matching compiled `.mo`.

If you are adding a new locale, please submit the `.po` file; maintainers will compile the `.mo` during the release process.

## Commit messages

Use short, imperative commit messages. Include a body when the change needs context. Example:

```
Tighten API key sanitizer

Reject keys shorter than 8 chars and surface the error through
add_settings_error() so the admin sees why the value was not saved.
```

## Pull request checklist

- [ ] Changes follow the coding standards above.
- [ ] New or changed user-facing strings are translatable.
- [ ] New admin actions have capability check + nonce.
- [ ] `CHANGELOG.md` has an entry for user-visible changes.
- [ ] The plugin header `Version` and the `Stable tag` in `readme.txt` are bumped together when a release is being prepared.
- [ ] No personal, server or credential information has been added to the repository.
