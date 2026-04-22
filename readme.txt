=== Email API Mailer for Cyberpanel ===
Contributors: rafaelpessoap
Tags: email, smtp, transactional email, cyberpanel, wp_mail
Requires at least: 5.7
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Send WordPress emails through the Cyberpanel transactional email REST API with delivery tracking and account statistics.

== Description ==

**Email API Mailer for Cyberpanel** replaces the default `wp_mail()` function so every transactional email your WordPress site sends (WooCommerce notifications, password resets, contact-form messages, custom plugin emails) is delivered through the Cyberpanel transactional email REST API instead of the server's default PHP mailer.

I built this plugin after looking for a simple way to connect WordPress to the email service that ships with Cyberpanel and finding no existing plugin that did the job. I needed it for my own sites and decided to share it as open source so anyone running on Cyberpanel can benefit from the same integration.

= Main features =

* **wp_mail() override** — every email leaving WordPress (WooCommerce, forms, notifications, custom plugins) goes through the Cyberpanel API.
* **Smart delivery tracking** — after each send, the plugin schedules a single WP-Cron event to check whether the message was actually delivered, rechecking every 3 minutes until confirmation, bounce, timeout (20 retries) or expiration (48 hours).
* **Account statistics dashboard** — displays your plan, monthly limit, emails sent/remaining, reputation score, rate limits, verified domains and monthly engagement (delivered, bounced, opened, clicked).
* **Colored activity log** — history of the last 30 events: SENT (blue), DELIVERED (green), ERROR/BOUNCE (red), EXPIRED/TIMEOUT (yellow).
* **Built-in test email** — one-click test from the settings screen to confirm the integration is working.
* **Safe fallback** — when the plugin toggle is off, WordPress keeps using its default mailer (PHPMailer) without any side effects.
* **Multiple recipients** — emails with several To/Cc/Bcc addresses are transparently split into one API call per recipient (current API limitation).
* **wp-config.php constant support** — define `CYBERPANEL_EMAIL_API_KEY` in your `wp-config.php` to keep the API key out of the database.
* **Protected log location** — logs are stored under `wp-content/uploads/cyberpanel-email/` in a `.log.php` file that begins with a PHP-exit guard, so even if the web server is configured to serve static files directly (Nginx, LiteSpeed, etc.) any direct HTTP request returns empty output. Additional `.htaccess`, `web.config` and `index.php` guards are created for defense in depth.

= Data sent to third parties =

When enabled, the plugin sends the following data to `platform.cyberpersons.com` (the Cyberpanel email platform) for every outgoing email: the sender, recipient, subject, message body, reply-to, a `source: wp_mail` metadata tag and the site URL in metadata. API responses (delivery status, open/click counts) are fetched back and stored locally. No data is sent to any other third party.

= Disclaimer =

This plugin is an independent, open-source integration. It is **not affiliated with, endorsed by or sponsored by Cyberpanel** (cyberpanel.net) or its operators. "Cyberpanel" is used here only to describe the service this plugin connects to.

== Installation ==

1. Upload the `email-api-mailer-for-cyberpanel` folder to `wp-content/plugins/`, or install the ZIP through **Plugins > Add New > Upload Plugin**.
2. Activate the plugin from the **Plugins** screen.
3. Go to **Settings > Email API Mailer**.
4. Fill in:
   * **API Key** — the `sk_live_...` key generated inside your Cyberpanel account (or define `CYBERPANEL_EMAIL_API_KEY` in `wp-config.php`).
   * **Sender Email** — an address on a domain you already verified inside Cyberpanel.
   * **Sender Name** — the name your recipients will see.
5. Enable the **Enable** toggle and save.
6. Use the **Send Test** button to confirm delivery.

== Frequently Asked Questions ==

= How does delivery tracking work? =

No long-running cron is required. When an email is sent the plugin records the returned `message_id` and schedules a **single** WP-Cron event for 3 minutes later. That event queries `GET /email/v1/messages/{id}` for every pending message and refreshes your account stats. If pending messages remain it reschedules itself; otherwise it stops. A message is abandoned after 20 checks or 48 hours without a final status.

= Can I keep my API key out of the database? =

Yes. Add the following line to your `wp-config.php`:

    define( 'CYBERPANEL_EMAIL_API_KEY', 'sk_live_your_key_here' );

The plugin detects the constant and prefers it over the value stored in the database.

= Are attachments supported? =

Not at this time — the current Cyberpanel email API does not accept attachments. When `wp_mail()` is called with attachments the plugin transparently falls through to the default WordPress mailer via the `pre_wp_mail` filter, so the message still goes out.

= Does the plugin support multisite? =

It has not been explicitly tested on multisite yet. Each site in a network can be configured independently if you activate it per-site rather than network-wide.

= Does the plugin work when the toggle is turned off? =

Yes. When disabled, the plugin uses an internal drop-in that mirrors the standard WordPress `wp_mail()` behavior backed by PHPMailer, so disabling the integration is safe.

= Where are the logs stored? =

Under `wp-content/uploads/cyberpanel-email/cyberpanel-email.log.php`. The file starts with a `<?php exit; ?>` guard so any direct HTTP request returns empty output regardless of the web server in use. Additional `.htaccess`, `web.config` and `index.php` files block direct access as a defense-in-depth measure.

== Screenshots ==

1. Account dashboard with plan, usage, engagement and monthly quota bar.
2. Settings page with API key, sender email and enable toggle.
3. Delivery tracking panel and colored activity log.

== Changelog ==

= 2.0.0 =
* Renamed plugin to "Email API Mailer for Cyberpanel" and published as open source.
* English source strings with bundled Brazilian Portuguese translation (pt_BR).
* Security hardening: capability checks on every admin action, nonces, `wp_unslash()` on all superglobals, sanitize callbacks on every registered option, use of `wp_safe_redirect()` for post-action redirects, escaping on every output.
* Logs moved from `wp-content/cyberpersons-mailer.log` to a protected directory under `wp-content/uploads/cyberpanel-email/` with `.htaccess`, `web.config` and `index.php` guards.
* Added `CYBERPANEL_EMAIL_API_KEY` constant support so the API key can live in `wp-config.php` instead of the database.
* Added `uninstall.php` that performs a full cleanup (options, cron, logs).
* One-time automatic migration from the legacy `cyberpersons_*` option names used in v1.x.

= 1.2.0 =
* Internal release. First working integration with delivery tracking, account stats dashboard and activity log. Portuguese-only strings, options prefixed with `cyberpersons_`.

== Upgrade Notice ==

= 2.0.0 =
Major security and i18n release. Your previous settings are migrated automatically on the first admin page load after upgrading.

== Privacy ==

This plugin transmits the content of your outgoing emails (sender, recipient, subject, message body) to the Cyberpanel email platform at `platform.cyberpersons.com` for the sole purpose of delivering those emails, and fetches back their delivery status for display in the admin panel. Refer to the Cyberpanel privacy policy at https://cyberpanel.net for details on how that service processes the data.
