<?php
/**
 * Shortcode class & template tag
 *
 * @package Mad_Mimi
 */

/**
 * Mad Mimi Sign Up Forms shortcode.
 *
 * @since 1.0
 */
class Mad_Mimi_Shortcode {

	/**
	 * Renders the shortcode.
	 *
	 * @param array $atts An array of shortcode attributes.
	 */
	public function render( $atts ) {
		extract( shortcode_atts( array(
			'id' => false,
		), $atts ) );

		if ( ! $id ) {
			return;
		}

		return mimi_form( $id, false );
	}
}

/**
 * The main template tag. Pass on the ID and watch the magic happen.
 *
 * @since 1.0
 * @see Mad_Mimi_Form_Renderer
 *
 * @param int  $id The ID of the form you wish to output.
 * @param bool $echo Wether to echo the form field. Default true.
 */
function mimi_form( $id, $echo = true ) {
	if ( class_exists( 'Mad_Mimi_Form_Renderer', false ) ) {
		$renderer = new Mad_Mimi_Form_Renderer();
		return $renderer->process( $id, $echo );
	}
}
