<?php
class Test_Mad_Mimi_Official extends WP_UnitTestCase {

	/**
	 * @var Mad_Mimi_Official
	 */
	private $instance;

	/**
	 * Holds the plugin file path
	 *
	 * @var Plugin
	 */
	protected $plugin_file_path;

	/**
	 * PHP unit setup function
	 *
	 * @return void
	 */
	function setUp() {
		parent::setUp();
		$this->plugin_file_path = $GLOBALS['_plugin_file'];
		$this->instance = Mad_Mimi_Official::instance();
	}

	public function test_basics() {
		$this->assertTrue( class_exists( 'Mad_Mimi_Official', false ) );
		$this->assertTrue( function_exists( 'mimi' ) );
	}

	public function test_instance() {
		$this->assertInstanceOf( 'Mad_Mimi_Official', $this->instance );
		$instance_second = Mad_Mimi_Official::instance();
		$this->assertSame( $this->instance, $instance_second );
	}

	public function test_setup_actions() {
		global $wp_filter;

		$this->assertArrayHasKey( 'init', $wp_filter );
		$this->assertArrayHasKey( 'widgets_init', $wp_filter );
		$this->assertArrayHasKey( 'admin_notices', $wp_filter );
		$this->assertArrayHasKey( 'plugins_loaded', $wp_filter );
		$this->assertArrayHasKey( 'plugin_action_links_' . plugin_basename( $this->plugin_file_path ), $wp_filter );
		$this->assertArrayHasKey( 'activate_' . plugin_basename( $this->plugin_file_path ), $wp_filter );
		$this->assertArrayHasKey( 'deactivate_' . plugin_basename( $this->plugin_file_path ), $wp_filter );
	}

	public function test_setup_constants() {
		$this->assertTrue( defined( 'Mad_Mimi_PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'Mad_Mimi_PLUGIN_URL' ) );
		$this->assertTrue( defined( 'Mad_Mimi_PLUGIN_BASE' ) );
		$this->assertTrue( defined( 'Mad_Mimi_VERSION' ) );

		$this->assertEquals( Mad_Mimi_PLUGIN_DIR, plugin_dir_path( $this->plugin_file_path ) );
		$this->assertEquals( Mad_Mimi_PLUGIN_URL, plugin_dir_url( $this->plugin_file_path ) );
		$this->assertEquals( Mad_Mimi_PLUGIN_BASE, plugin_basename( $this->plugin_file_path ) );
		$plugin_data = get_plugin_data( $this->plugin_file_path );
		$this->assertEquals( Mad_Mimi_VERSION, $plugin_data['Version'] );
	}

	public function test_requirements() {
		$this->assertTrue( class_exists( 'Mad_Mimi_Dispatcher', false ) );
		$this->assertTrue( class_exists( 'Mad_Mimi_Shortcode', false ) );
		$this->assertTrue( class_exists( 'Mad_Mimi_Form_Renderer', false ) );
		$this->assertTrue( class_exists( 'Mad_Mimi_Form_Fields', false ) );
		$this->assertTrue( class_exists( 'Mad_Mimi_Settings', false ) );
		$this->assertTrue( class_exists( 'Mad_Mimi_Settings_Controls', false ) );
		$this->assertTrue( class_exists( 'Mad_Mimi_Form_Widget', false ) );
	}

	public function test_init() {
		global $wp_filter;

		$this->assertFalse( $this->instance->debug );
		$this->assertNull( $this->instance->settings );
		$this->assertArrayHasKey( 'wp_enqueue_scripts', $wp_filter );

		// test in admin case:
		define( 'WP_ADMIN', true );
		$second_instance = new Mad_Mimi_Official();
		$second_instance->init();
		$this->assertInstanceOf( 'Mad_Mimi_Settings', $second_instance->settings );
	}

	public function test_register_shortcode() {
		global $shortcode_tags;

		$this->instance->register_shortcode();
		$this->assertArrayHasKey( 'mimi', $shortcode_tags );
		$this->assertArrayHasKey( 'Mad_Mimi', $shortcode_tags );

		$this->assertEquals( $shortcode_tags['mimi'], array( $this->instance->shortcode, 'render' ) );
		$this->assertEquals( $shortcode_tags['Mad_Mimi'], array( $this->instance->shortcode, 'render' ) );
		$this->assertTrue( has_shortcode( 'This is a blob with [mimi id=123] in it', 'mimi' ) );
		$this->assertTrue( has_shortcode( 'This is a blob with [Mad_Mimi] in it', 'Mad_Mimi' ) );
	}

	public function test_register_widget() {
		global $wp_widget_factory;

		$this->instance->register_widget();
		$this->assertArrayHasKey( 'Mad_Mimi_Form_Widget', $wp_widget_factory->widgets );
		$this->assertInstanceOf( 'Mad_Mimi_Form_Widget', $wp_widget_factory->widgets['Mad_Mimi_Form_Widget'] );
	}

	public function test_enqueue() {
		$this->instance->enqueue();
		$this->assertTrue( wp_script_is( 'mimi-main','queue' ) );
		$this->assertTrue( wp_script_is( 'function', 'queue' ) );
		$this->assertTrue( wp_script_is( 'jquery-ui', 'queue' ) );
		$this->assertTrue( wp_style_is( 'mimi-base', 'registered' ) );
		$this->assertTrue( wp_style_is( 'jquery-ui', 'registered' ) );
	}

	public function test_action_links() {
		global $_parent_pages;

		$_parent_pages['mimi-settings'] = 'settings_slug';

		$sample_array = array( 'the_key' => 'the_value' );
		$actual_result = $this->instance->action_links( $sample_array );

		$this->assertArrayHasKey( 'the_key', $actual_result );
		$this->assertEquals( 'the_value', $actual_result['the_key'] );
		$this->assertArrayHasKey( 'settings', $actual_result );
		$this->assertEquals( '<a href="http://example.org/wp-admin/settings_slug?page=mimi-settings">Settings</a>', $actual_result['settings'] );
	}

	public function test_activate() {
		// nothing to test
	}

	public function test_deactivate() {
		update_option( 'mimi-version', 'test_version' );

		$this->instance->deactivate();
		$this->assertNull( get_option( 'mimi-version', null ) );
	}

	public function test_action_admin_notices() {
		global $current_screen;

		$current_screen = new stdClass();
		$current_screen->id = 'test';

		ob_start();
		$this->instance->action_admin_notices();
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertEmpty( $actual_output );

		ob_start();
		delete_option( 'mimi-version' );
		$current_screen->id = 'plugins';
		$this->instance->action_admin_notices();
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertContains( 'Mad Mimi Sign Up Forms is almost ready.', $actual_output );

		ob_start();
		update_option( 'mimi-version', 'test_version' );
		$current_screen->id = 'plugins';
		$this->instance->action_admin_notices();
		$actual_output = ob_get_contents();
		ob_end_clean();

		$this->assertEmpty( $actual_output );
	}
}
