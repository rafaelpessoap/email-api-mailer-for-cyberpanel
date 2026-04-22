<?php
/**
 * Uninstall cleanup for Email API Mailer for Cyberpanel.
 *
 * Runs when the user explicitly deletes the plugin from the Plugins screen.
 * Removes every persisted option, clears cron events and deletes the protected
 * log directory created under uploads.
 *
 * @package Email_API_Mailer_For_Cyberpanel
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$options_to_delete = array(
	'cyberpanel_email_api_key',
	'cyberpanel_email_from_email',
	'cyberpanel_email_from_name',
	'cyberpanel_email_enabled',
	'cyberpanel_email_pending_messages',
	'cyberpanel_email_account_stats',
	'cyberpanel_email_migrated_from_legacy',
	// Legacy option names (pre-2.0.0). Kept here so that users upgrading
	// from 1.x and uninstalling immediately do not leave orphan rows behind.
	'cyberpersons_api_key',
	'cyberpersons_from_email',
	'cyberpersons_from_name',
	'cyberpersons_enabled',
	'cyberpersons_pending_messages',
	'cyberpersons_account_stats',
);

foreach ( $options_to_delete as $option ) {
	delete_option( $option );
	delete_site_option( $option );
}

$cron_hooks = array( 'cyberpanel_email_check_delivery', 'cyberpersons_check_delivery' );
foreach ( $cron_hooks as $hook ) {
	$next = wp_next_scheduled( $hook );
	if ( $next ) {
		wp_unschedule_event( $next, $hook );
	}
	wp_clear_scheduled_hook( $hook );
}

$uploads = wp_upload_dir( null, false );
if ( ! empty( $uploads['basedir'] ) ) {
	$log_dir = trailingslashit( $uploads['basedir'] ) . 'cyberpanel-email';
	if ( is_dir( $log_dir ) ) {
		// Remove files, including dotfiles (.htaccess) and the PHP-guarded log.
		foreach ( array( '*', '.htaccess' ) as $pattern ) {
			$files = glob( $log_dir . '/' . $pattern );
			if ( is_array( $files ) ) {
				foreach ( $files as $file ) {
					if ( is_file( $file ) ) {
						@unlink( $file );
					}
				}
			}
		}
		@rmdir( $log_dir );
	}
}

$legacy_log = WP_CONTENT_DIR . '/cyberpersons-mailer.log';
if ( file_exists( $legacy_log ) ) {
	@unlink( $legacy_log );
}
