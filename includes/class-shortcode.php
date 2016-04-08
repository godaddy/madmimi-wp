<?php
/**
 * Shortcode class & template tag
 *
 * @package GEM
 */

/**
 * GoDaddy Email Marketing shortcode.
 *
 * @since 1.0
 */
class GEM_Shortcode {

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

		return gem_form( $id, false );
	}
}

/**
 * The main template tag. Pass on the ID and watch the magic happen.
 *
 * @since 1.0
 * @see GEM_Form_Renderer
 *
 * @param int  $id The ID of the form you wish to output.
 * @param bool $echo Wether to echo the form field. Default true.
 */
function gem_form( $id, $echo = true ) {
	if ( class_exists( 'GEM_Form_Renderer', false ) ) {
		$renderer = new GEM_Form_Renderer();
		$renderer->process( $id, $echo );
	}
}
