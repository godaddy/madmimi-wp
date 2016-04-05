<?php
/**
 * Plugin Name: GoDaddy Email Marketing Signup Forms
 * Plugin URI: https://gem.godaddy.com/
 * Description: Add the GoDaddy Email Marketing signup form to your WordPress site! Easy to set up, the plugin allows your site visitors to subscribe to your email lists.
 * Version: 1.0.3
 * Author: GoDaddy
 * Author URI: https://gem.godaddy.com/
 * Text Domain: godaddy-email-marketing
 * Domain Path: /languages
 *
 * Copyright © 2016 GoDaddy Operating Company, LLC. All Rights Reserved.
 */

class GEM_Official {

	private static $instance;
	private static $basename;

	public $settings;
	public $debug;

	public static function instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
			self::$instance->setup_constants();
			self::$instance->requirements();
			self::$instance->setup_actions();
		}

		return self::$instance;

	}

	private function setup_actions() {

		add_action( 'plugins_loaded', array( $this, 'i18n' ) );
		add_action( 'init',           array( $this, 'init' ) );
		add_action( 'widgets_init',   array( $this, 'register_widget' ) );
		add_action( 'init',           array( $this, 'register_shortcode' ), 20 );
		add_action( 'admin_notices',  array( $this, 'action_admin_notices' ) );
		add_filter( 'plugin_action_links_' . self::$basename, array( $this, 'action_links' ), 10 );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

	}

	private function setup_constants() {

		// Plugin's main directory
		defined( 'GEM_PLUGIN_DIR' )
			or define( 'GEM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

		// Absolute URL to plugin's dir
		defined( 'GEM_PLUGIN_URL' )
			or define( 'GEM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

		// Absolute URL to plugin's dir
		defined( 'GEM_PLUGIN_BASE' )
			or define( 'GEM_PLUGIN_BASE', plugin_basename( __FILE__ ) );

		// Plugin's main directory
		defined( 'GEM_VERSION' )
			or define( 'GEM_VERSION', '1.1' );

		// Set up the base name
		isset( self::$basename ) || self::$basename = plugin_basename( __FILE__ );

	}

	// @todo include only some on is_admin()
	private function requirements() {

		require_once GEM_PLUGIN_DIR . 'includes/class-dispatcher.php';

		// the shortcode
		require_once GEM_PLUGIN_DIR . 'includes/class-shortcode.php';

		// the file renders the form
		require_once GEM_PLUGIN_DIR . 'includes/render.php';

		// the main widget
		require_once GEM_PLUGIN_DIR . 'includes/widget.php';

		// settings page, creds validation
		require_once GEM_PLUGIN_DIR . 'includes/settings.php';

	}

	public function i18n() {

		load_plugin_textdomain( 'godaddy-email-marketing', false, dirname( self::$basename ) . '/languages' );

	}

	public function init() {

		// enable debug mode?
		$this->debug = (bool) apply_filters( 'gem_debug', false );

		// initialize settings
		if ( is_admin() ) {
			$this->settings = new GEM_Settings;
		}

		// enqueue scripts n styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

	}

	public function register_shortcode() {

		// register shortcode
		add_shortcode( 'gem', array( 'GEM_Shortcode', 'render' ) );
		add_shortcode( 'GEM', array( 'GEM_Shortcode', 'render' ) );

	}

	public function register_widget() {
		register_widget( 'GEM_Form_Widget' );
	}

	public function enqueue() {

		// main JavaScript file
		wp_enqueue_script( 'gem-main', plugins_url( 'js/gem.js', __FILE__ ), array( 'jquery' ), GEM_VERSION, true );

		// datepicker JavaScript file
		wp_enqueue_script( 'function', plugins_url( 'js/function.js', __FILE__ ), array( 'jquery' ), GEM_VERSION, true );

		// JQuery-ui
		wp_enqueue_script( 'jquery-ui', 'http://code.jquery.com/ui/1.11.4/jquery-ui.js', array( 'jquery' ), '1.11.4' );

		// assistance CSS
		wp_enqueue_style( 'gem-base', plugins_url( 'css/gem.css', __FILE__ ), false, GEM_VERSION );

		// datepicker CSS
		wp_enqueue_style( 'jquery-ui', plugins_url( 'css/jquery-ui.css', __FILE__ ), true, GEM_VERSION );

		// help strings
		wp_localize_script( 'gem-main', 'GEM', array(
			'thankyou' 				=> _x( 'Thank you for signing up!', 'ajax response', 'godaddy-email-marketing' ),
			'thankyou_suppressed' 	=> _x( 'Thank you for signing up! Please check your email to confirm your subscription.', 'ajax response', 'godaddy-email-marketing' ),
			'oops' 					=> _x( 'Oops! There was a problem. Please try again.', 'ajax response', 'godaddy-email-marketing' ),
			'fix' 					=> _x( 'There was a problem. Please fill all required fields.', 'ajax response', 'godaddy-email-marketing' ),
		) );

	}

	public function action_links( $actions ) {

		return array_merge(
			array(
				'settings' => sprintf( '<a href="%s">%s</a>', menu_page_url( 'gem-settings', false ), __( 'Settings', 'godaddy-email-marketing' ) ),
			),
			$actions
		);

	}

	public function activate() {
		// nothing to do here (for now)
	}

	public function deactivate() {
		delete_option( 'gem-version' );
	}

	public function action_admin_notices() {

		$screen = get_current_screen();

		if ( 'plugins' != $screen->id ) {
			return;
		}

		$version = get_option( 'gem-version' );

		if ( ! $version ) {

			update_option( 'gem-version', GEM_VERSION ); ?>

			<div class="updated fade">
				<p>
					<strong><?php esc_html_e( 'GoDaddy Email Marketing is almost ready.', 'godaddy-email-marketing' ); ?></strong> <?php esc_html_e( 'You must enter your username &amp; API key for it to work.', 'godaddy-email-marketing' ); ?> &nbsp;
					<a class="button" href="<?php menu_page_url( 'gem-settings' ); ?>"><?php esc_html_e( "Let's do it!", 'godaddy-email-marketing' ); ?></a>
				</p>
			</div>

			<?php
		}
	}
}

function gem() {
	return GEM_Official::instance();
}
add_action( 'plugins_loaded', 'gem' );
