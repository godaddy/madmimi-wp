<?php
/**
 * Plugin Name: Mad Mimi Sign Up Forms Signup Forms
 * Plugin URI: https://mimi.madmimi.com/
 * Description: Add the Mad Mimi Sign Up Forms signup form to your WordPress site! Easy to set up, the plugin allows your site visitors to subscribe to your email lists.
 * Version: 1.0.4
 * Author: Mad Mimi
 * Author URI: https://mimi.madmimi.com/
 * Text Domain: madmimi-email-marketing
 * Domain Path: /languages
 *
 * Copyright © 2016 Mad Mimi Operating Company, LLC. All Rights Reserved.
 *
 * @package Mad_Mimi
 */

/**
 * Mad Mimi Sign Up Forms.
 *
 * @since 1.0
 */
class Mad_Mimi_Official {

	/**
	 * Mad_Mimi_Official instance.
	 *
	 * @var Mad_Mimi_Official
	 */
	private static $instance;

	/**
	 * Plugin basename.
	 *
	 * @var string
	 */
	private static $basename;

	/**
	 * Mad_Mimi_Settings instance.
	 *
	 * @var Mad_Mimi_Settings
	 */
	public $settings;

	/**
	 * Turns on debugging.
	 *
	 * @var bool
	 */
	public $debug;

	/**
	 * Class instance.
	 *
	 * @codeCoverageIgnore
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
			self::$instance->setup_constants();
			self::$instance->requirements();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}

	/**
	 * Adds actions and filters.
	 *
	 * @codeCoverageIgnore
	 */
	private function setup_actions() {
		add_action( 'plugins_loaded', array( $this, 'i18n' ) );
		add_action( 'init',           array( $this, 'init' ) );
		add_action( 'widgets_init',   array( $this, 'register_widget' ) );
		add_action( 'init',           array( $this, 'register_shortcode' ), 20 );
		add_action( 'admin_notices',  array( $this, 'action_admin_notices' ) );

		add_filter( 'plugin_action_links_' . self::$basename, array( $this, 'action_links' ), 10 );

		register_activation_hook( __FILE__,   array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}

	/**
	 * Creates the constants.
	 *
	 * @codeCoverageIgnore
	 */
	private function setup_constants() {

		// Plugin's main directory.
		defined( 'Mad_Mimi_PLUGIN_DIR' )
			or define( 'Mad_Mimi_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

		// Absolute URL to plugin's dir.
		defined( 'Mad_Mimi_PLUGIN_URL' )
			or define( 'Mad_Mimi_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

		// Absolute URL to plugin's dir.
		defined( 'Mad_Mimi_PLUGIN_BASE' )
			or define( 'Mad_Mimi_PLUGIN_BASE', plugin_basename( __FILE__ ) );

		// Plugin's main directory.
		defined( 'Mad_Mimi_VERSION' )
			or define( 'Mad_Mimi_VERSION', '1.0.4' );

		// Set up the base name.
		isset( self::$basename ) || self::$basename = plugin_basename( __FILE__ );
	}

	/**
	 * Loads the PHP files.
	 *
	 * @todo include only some on is_admin()
	 * @codeCoverageIgnore
	 */
	private function requirements() {

		// The Dispatcher.
		require_once Mad_Mimi_PLUGIN_DIR . 'includes/class-dispatcher.php';

		// The shortcode.
		require_once Mad_Mimi_PLUGIN_DIR . 'includes/class-shortcode.php';

		// The file renders the form.
		require_once Mad_Mimi_PLUGIN_DIR . 'includes/render.php';

		// The main widget.
		require_once Mad_Mimi_PLUGIN_DIR . 'includes/widget.php';

		// Settings page, creds validation.
		require_once Mad_Mimi_PLUGIN_DIR . 'includes/settings.php';
	}

	/**
	 * Load translations.
	 */
	public function i18n() {
		load_plugin_textdomain( 'madmimi-email-marketing', false, dirname( self::$basename ) . '/languages' );
	}

	/**
	 * Initializes the plugin.
	 */
	public function init() {

		// Enable debug mode?
		$this->debug = (bool) apply_filters( 'mimi_debug', false );

		// Initialize settings.
		if ( is_admin() ) {
			$this->settings = new Mad_Mimi_Settings;
		}

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Registers the shortcode.
	 */
	public function register_shortcode() {

		// Register shortcode.
		add_shortcode( 'mimi', array( 'Mad_Mimi_Shortcode', 'render' ) );
		add_shortcode( 'Mad_Mimi', array( 'Mad_Mimi_Shortcode', 'render' ) );
	}

	/**
	 * Registers the widget.
	 */
	public function register_widget() {
		register_widget( 'Mad_Mimi_Form_Widget' );
	}

	/**
	 * Enqueues scripts and styles.
	 */
	public function enqueue() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Main JavaScript file.
		wp_enqueue_script( 'mimi-main', plugins_url( "js/mimi{$suffix}.js", __FILE__ ), array( 'jquery' ), Mad_Mimi_VERSION, true );

		// Datepicker JavaScript file.
		wp_enqueue_script( 'function', plugins_url( "js/function{$suffix}.js", __FILE__ ), array( 'jquery' ), Mad_Mimi_VERSION, true );

		// JQuery-ui.
		wp_enqueue_script( 'jquery-ui', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array( 'jquery' ), '1.11.4' );

		// Assistance CSS.
		wp_enqueue_style( 'mimi-base', plugins_url( "css/mimi{$suffix}.css", __FILE__ ), false, Mad_Mimi_VERSION );

		// Datepicker CSS.
		wp_enqueue_style( 'jquery-ui', plugins_url( "css/jquery-ui{$suffix}.css", __FILE__ ), true, Mad_Mimi_VERSION );

		// Help strings.
		wp_localize_script( 'mimi-main', 'Mad_Mimi', array(
			'thankyou'            => _x( 'Thank you for signing up!', 'ajax response', 'madmimi-email-marketing' ),
			'thankyou_suppressed' => _x( 'Thank you for signing up! Please check your email to confirm your subscription.', 'ajax response', 'madmimi-email-marketing' ),
			'oops'                => _x( 'Oops! There was a problem. Please try again.', 'ajax response', 'madmimi-email-marketing' ),
			'fix'                 => _x( 'There was a problem. Please fill all required fields.', 'ajax response', 'madmimi-email-marketing' ),
		) );
	}

	/**
	 * Adds the settings page to the action links.
	 *
	 * @param array $actions An array of plugin action links.
	 */
	public function action_links( $actions ) {
		return array_merge(
			array(
				'settings' => sprintf( '<a href="%s">%s</a>', menu_page_url( 'mimi-settings', false ), __( 'Settings', 'madmimi-email-marketing' ) ),
			),
			$actions
		);
	}

	/**
	 * Nothing to do here (for now).
	 */
	public function activate() {}

	/**
	 * Deletes the mimi version.
	 */
	public function deactivate() {
		delete_option( 'mimi-version' );
	}

	/**
	 * Displays the admin notice.
	 */
	public function action_admin_notices() {
		$screen = get_current_screen();

		if ( 'plugins' != $screen->id ) {
			return;
		}

		$version = get_option( 'mimi-version' );

		if ( ! $version ) {
			update_option( 'mimi-version', Mad_Mimi_VERSION ); ?>

			<div class="updated fade">
				<p>
					<strong><?php esc_html_e( 'Mad Mimi Sign Up Forms is almost ready.', 'madmimi-email-marketing' ); ?></strong> <?php esc_html_e( 'You must enter your username &amp; API key for it to work.', 'madmimi-email-marketing' ); ?> &nbsp;
					<a class="button" href="<?php menu_page_url( 'mimi-settings' ); ?>"><?php esc_html_e( "Let's do it!", 'madmimi-email-marketing' ); ?></a>
				</p>
			</div>

			<?php
		}
	}
}

/**
 * Mad Mimi Sign Up Forms instance.
 *
 * @since 1.0
 */
function mimi() {
	return Mad_Mimi_Official::instance();
}
add_action( 'plugins_loaded', 'mimi' );
