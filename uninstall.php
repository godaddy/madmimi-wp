<?php
/**
 * Uninstall routine
 *
 * @package Mad_Mimi
 */

// @codeCoverageIgnoreStart

// If uninstall not called from WordPress exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Delete all options and transients that contain mimi.
delete_option( 'mimi-version' );
delete_option( 'mimi-settings' );

// @codeCoverageIgnoreEnd
