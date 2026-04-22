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

/**
 * Uninstall routine wrapped in a function so variables stay in local scope
 * (WordPress coding standards require plugin-scope variables in top-level
 * files to be prefixed; wrapping avoids the prefix noise here).
 */
function cyberpanel_email_run_uninstall() {
	$options_to_delete = array(
		'cyberpanel_email_api_key',
		'cyberpanel_email_from_email',
		'cyberpanel_email_from_name',
		'cyberpanel_email_enabled',
		'cyberpanel_email_pending_messages',
		'cyberpanel_email_account_stats',
		'cyberpanel_email_migrated_from_legacy',
		// Legacy option names (pre-2.0.0). Kept here so users upgrading
		// from 1.x and uninstalling immediately do not leave orphan rows behind.
		'cyberpersons_api_key',
		'cyberpersons_from_email',
		'cyberpersons_from_name',
		'cyberpersons_enabled',
		'cyberpersons_pending_messages',
		'cyberpersons_account_stats',
	);

	foreach ( $options_to_delete as $option_name ) {
		delete_option( $option_name );
		delete_site_option( $option_name );
	}

	$cron_hooks = array( 'cyberpanel_email_check_delivery', 'cyberpersons_check_delivery' );
	foreach ( $cron_hooks as $hook_name ) {
		$next_event = wp_next_scheduled( $hook_name );
		if ( $next_event ) {
			wp_unschedule_event( $next_event, $hook_name );
		}
		wp_clear_scheduled_hook( $hook_name );
	}

	// Clear per-user admin notice transients.
	$users = get_users( array( 'fields' => 'ID' ) );
	foreach ( $users as $user_id ) {
		delete_transient( 'cp_email_notice_' . (int) $user_id );
	}

	$uploads = wp_upload_dir( null, false );
	if ( ! empty( $uploads['basedir'] ) ) {
		$log_dir = trailingslashit( $uploads['basedir'] ) . 'cyberpanel-email';
		if ( is_dir( $log_dir ) ) {
			// Delete the directory and its contents recursively via WP_Filesystem.
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			if ( WP_Filesystem() ) {
				global $wp_filesystem;
				$wp_filesystem->delete( $log_dir, true, 'd' );
			}
		}
	}

	$legacy_log = WP_CONTENT_DIR . '/cyberpersons-mailer.log';
	if ( file_exists( $legacy_log ) ) {
		wp_delete_file( $legacy_log );
	}
}

cyberpanel_email_run_uninstall();
