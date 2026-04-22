# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.1] - 2026-04-22

### Changed

- Bumped the declared compatibility from "Tested up to: 6.7" to "Tested up to: 6.9" so the plugin is eligible for the current WordPress release.
- Uninstall routine now uses `WP_Filesystem()->delete()` instead of direct `rmdir()`/`unlink()` calls, matching WordPress Plugin Directory review guidelines.
- Moved the `pre_wp_mail` filter callback into the main plugin class and the translation loading into the `init` hook. No user-visible change.

### Fixed

- Inline control structures in the settings dashboard replaced with explicit multi-line blocks for readability.
- `handle_test_email()` sanitizes `$_POST['test_to']` at the point of access so every static analyzer can verify the path.

## [2.0.0] - 2026-04-22

### Added

- First public open-source release under the name **Email API Mailer for Cyberpanel**.
- English source strings with bundled Brazilian Portuguese (`pt_BR`) translation.
- `CYBERPANEL_EMAIL_API_KEY` constant support so the API key can live in `wp-config.php` instead of the database.
- `uninstall.php` that performs a full cleanup of options, cron events and log directory.
- Protected log directory under `wp-content/uploads/cyberpanel-email/` with `.htaccess`, `web.config` and `index.php` guards.
- One-time automatic migration from the legacy `cyberpersons_*` option names used in v1.x.
- GitHub Actions workflow running WordPress Coding Standards on every push and pull request.
- `SECURITY.md`, `CONTRIBUTING.md` and `CHANGELOG.md` governance files.

### Changed

- Renamed from "Cyberpersons Mailer" to "Email API Mailer for Cyberpanel" to reflect the brand end users actually interact with (Cyberpanel). Internal option names, class, text domain and file name follow the rename. The underlying REST endpoint remains `platform.cyberpersons.com`, which is the administrative domain exposed by the Cyberpanel email service.
- Moved the activity log from `wp-content/cyberpersons-mailer.log` to the protected directory under `wp-content/uploads/cyberpanel-email/`.
- Admin redirects now use `wp_safe_redirect()` and carry notices through sanitized query args instead of string concatenation.

### Security

- Capability check (`manage_options`) added to every admin entry point (settings page, test email handler, check-now handler).
- `wp_unslash()` added on every superglobal read before sanitization.
- `sanitize_callback` added to every registered option; the API key is validated against a strict character whitelist and surfaces errors through `add_settings_error()`.
- All admin output is escaped at the point of output.

## [1.2.0]

Internal release. First working integration with the Cyberpanel email API (delivery tracking, account stats dashboard, activity log). Portuguese-only strings, options prefixed with `cyberpersons_`. Not published publicly.
