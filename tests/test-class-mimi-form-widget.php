<?php
class Test_Mad_Mimi_Form_Widget extends WP_UnitTestCase {

	/**
	 * Load WP_Http_Mock_Transport
	 */
	public static function setUpBeforeClass() {
		require_once( 'mock-transport.php' );
	}

	/**
	 * PHP unit setup function
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		WP_Http_Mock_Transport::$test_class = $this;
		WP_Http_Mock_Transport::$expected_url = null;
		add_action( 'http_api_transports', array( $this, 'get_transports' ) );
	}

	public function tearDown() {
		parent::tearDown();

		remove_action( 'http_api_transports', array( $this, 'get_transports' ) );
		WP_Http_Mock_Transport::$test_class = null;
	}

	public function get_transports() {
		return array( 'Mock_Transport' );
	}

	public function test_basics() {
		$this->assertTrue( class_exists( 'Mad_Mimi_Form_Widget', false ) );
	}

	public function test_construct() {
		$instance = new Mad_Mimi_Form_Widget();
		$this->assertEquals( 10, has_action( 'mimi_widget_text', 'wpautop' ) );
		$this->assertEquals( 10, has_action( 'mimi_widget_text', 'wptexturize' ) );
		$this->assertEquals( 10, has_action( 'mimi_widget_text', 'convert_chars' ) );

		$this->assertEquals( 'mimi-form', $instance->id_base );
		$this->assertEquals( 'Mad Mimi Sign Up Forms Form', $instance->name );
		$this->assertEquals( 'widget_mimi-form', $instance->option_name );
	}

	public function test_widget() {
		$sample_data = array(
			'fields' => array(
				'field_a' => array(
					'type' => 'string',
					'field_type' => 'string',
					'name' => 'the_name_a',
					'required' => false,
					'display' => 'text_a',
				),
				'field_b' => array(
					'type' => 'checkbox',
					'field_type' => 'checkbox',
					'required' => true,
					'name' => 'the_name_b',
					'value' => 'the_value',
					'display' => 'text_b',
				),
			),
			'submit' => 'the_url',
			'id' => 'the_id',
			'button_text' => 'button_text',
		);
		WP_Http_Mock_Transport::$response = array(
			'response' => array(
				'code' => 200,
			),
			'body' => json_encode( $sample_data ),
		);

		$widget = new Mad_Mimi_Form_Widget();
		$args = array(
			'before_widget' => 'before_text',
			'after_widget' => 'after_text',
			'before_title' => 'before_title',
			'after_title' => 'after_title',
		);
		$instance = array(
			'title' => 'the_title',
			'text' => 'the_text',
			'form' => 'form_id',
		);

		ob_start();
		$widget->widget( $args, $instance );
		$actual_output = ob_get_contents();
		ob_end_clean();

		$this->assertContains( 'before_textbefore_titlethe_titleafter_title<p>the_text</p>', $actual_output );
		$this->assertContains( '<form action="http://the_url" method="post" class="mimi-form">', $actual_output );
		$this->assertContains( '<label for="form_3_the_name_a">', $actual_output );
		$this->assertContains( 'text_a', $actual_output );
		$this->assertContains( '<input type="text" name="the_name_a" id="form_3_the_name_a" class="mimi-field" />', $actual_output );
		$this->assertContains( '<label for="form_3_the_name_bthe_value">', $actual_output );
		$this->assertContains( '<input type="checkbox" value="the_value" name="the_name_b" id="form_3_the_name_bthe_value" class="mimi-checkbox mimi-required" />', $actual_output );
		$this->assertContains( 'text_b', $actual_output );
		$this->assertContains( '<input type="hidden" name="form_id" value="0" />', $actual_output );
		$this->assertContains( '<input type="submit" value="button_text" class="button mimi-submit" />', $actual_output );
		$this->assertContains( 'after_text', $actual_output );
	}

	public function test_update() {
		$widget = new Mad_Mimi_Form_Widget();

		$new_instance = array(
			'title' => '<b>the_title</b>',
			'text' => 'new_text',
			'form' => 123,
		);
		$old_instance = array(
			'title' => 'the_old',
			'text' => 'old_text',
			'form' => 456,
			'other' => 'new_value',
		);
		$output = $widget->update( $new_instance, $old_instance );
		$this->assertEquals( 'the_title', $output['title'] );
		$this->assertEquals( 'new_text', $output['text'] );
		$this->assertEquals( 123, $output['form'] );
		$this->assertEquals( 'new_value', $output['other'] );

		$new_instance['form'] = -123;
		$output = $widget->update( $new_instance, $old_instance );
		$this->assertEquals( 123, $output['form'] );
	}

	public function test_form() {
		$widget = new Mad_Mimi_Form_Widget();
		$user_name = 'the_user';
		$api_key = 'the_api_key';
		$sample_data = new stdClass();
		$sample_field = new stdClass();
		$sample_field->id = 'the_field_id';
		$sample_field->name = 'the_field_name';
		$sample_data->signups = array( $sample_field );

		update_option( 'mimi-settings', array( 'api-key' => $api_key, 'username' => $user_name ) );
		set_transient( 'mimi-' . $user_name . '-lists', $sample_data );

		$instance = array(
			'title' => 'the_title',
			'text' => 'the_text',
			'form' => 123,
		);
		ob_start();
		$widget->form( $instance );
		$actual_output = ob_get_contents();
		ob_end_clean();
		delete_transient( 'mimi-' . $user_name . '-lists' );

		$this->assertContains( '<input class="widefat" id="widget-mimi-form--title" name="widget-mimi-form[][title]" type="text" value="the_title" />', $actual_output );
		$this->assertContains( '<textarea class="widefat" rows="3" id="widget-mimi-form--text" name="widget-mimi-form[][text]">the_text</textarea>', $actual_output );
		$this->assertContains( '<label for="widget-mimi-form--form">Form:</label>', $actual_output );
		$this->assertContains( '<select name="widget-mimi-form[][form]" id="widget-mimi-form--form" class="widefat">', $actual_output );
		$this->assertContains( '<option value="the_field_id" >the_field_name</option>', $actual_output );
	}
}
