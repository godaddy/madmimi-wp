<?php

// if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// delete all options and transients that contain mimi
delete_option( 'madmimi-version' );
delete_option( 'mad-mimi-settings' );