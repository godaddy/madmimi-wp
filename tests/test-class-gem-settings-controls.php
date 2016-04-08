<?php
class Test_GEM_Settings_Controls extends WP_UnitTestCase {

	/**
	 * PHP unit setup function
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	public function test_basics() {
		$this->assertTrue( class_exists( 'GEM_Settings_Controls', false ) );
	}

	public function test_description() {
		ob_start();
		GEM_Settings_Controls::description();
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertContains( '<p>Please enter your GoDaddy Email Marketing username and API Key in order to be able to create forms.</p>', $actual_output );
	}

	public function test_select() {
		ob_start();
		GEM_Settings_Controls::select( array(
			'options' => array( 'key' => 'the_value' ),
			'id' => 'the_id',
			'page' => 'the_page',
		) );
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertContains( '<select id="the_id" name="the_page[the_id]">', $actual_output );
		$this->assertContains( '<option value="key" >', $actual_output );
		$this->assertContains( 'the_value', $actual_output );
	}

	public function test_select_is_empty() {
		ob_start();
		GEM_Settings_Controls::select( array(
			'id' => null,
		) );
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertEmpty( $actual_output );
	}

	public function test_text() {
		ob_start();
		GEM_Settings_Controls::text(array(
			'id' => 'the_id',
			'page' => 'the_page',
		) );
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertContains( '<input type="text" name="the_page[the_id]"', $actual_output );
		$this->assertContains( 'id="the_page-the_id"', $actual_output );
		$this->assertContains( 'value="" class="regular-text code" />', $actual_output );
	}

	public function test_text_is_empty() {
		ob_start();
		GEM_Settings_Controls::text( array(
			'id' => null,
		) );
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertEmpty( $actual_output );
	}

	public function test_checkbox() {
		ob_start();
		GEM_Settings_Controls::checkbox( array(
			'id' => 'the_id',
			'page' => 'the_page',
		) );
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertContains( '<label for="the_page[the_id]">', $actual_output );
		$this->assertContains( '<input type="checkbox" name="the_page[the_id]" id="the_page[the_id]" value="1"  />', $actual_output );
		$this->assertContains( '</label>', $actual_output );
	}

	public function test_checkbox_is_empty() {
		ob_start();
		GEM_Settings_Controls::checkbox( array(
			'id' => null,
		) );
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertEmpty( $actual_output );
	}

	public function test_show_description() {
		ob_start();
		GEM_Settings_Controls::show_description( array(
			'description' => 'the_description',
		) );
		$actual_output = ob_get_contents();
		ob_end_clean();
		$this->assertContains( '<p class="description">the_description</p>', $actual_output );
	}

	public function test_get_option() {
		update_option( 'gem-settings', array( 'username' => 'user_name', 'api-key' => '1234' ) );
		$this->assertFalse( GEM_Settings_Controls::get_option( 'error' ) );
		$this->assertEquals( 'user_name', GEM_Settings_Controls::get_option( 'username' ) );
		$this->assertEquals( '1234', GEM_Settings_Controls::get_option( 'api-key' ) );
	}
}
