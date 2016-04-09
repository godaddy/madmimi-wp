<?php
/**
 * Widget class
 *
 * @package Mad_Mimi
 */

/**
 * Mad Mimi Sign Up Forms widget.
 *
 * @since 1.0
 */
class Mad_Mimi_Form_Widget extends WP_Widget {

	/**
	 * Sets up a new Mad Mimi Sign Up Forms widget instance.
	 */
	function __construct() {
		parent::__construct(
			'mimi-form',
			__( 'Mad Mimi Sign Up Forms Form', 'madmimi-email-marketing' ),
			array(
				'classname'   => 'mimi-form',
				'description' => _x( 'Embed any Mad Mimi Sign Up Forms webform in your sidebar.', 'widget description', 'madmimi-email-marketing' ),
			)
		);

		foreach ( array( 'wpautop', 'wptexturize', 'convert_chars' ) as $filter ) {
			add_filter( 'mimi_widget_text', $filter );
		}
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Custom Menu widget instance.
	 */
	function widget( $args, $instance ) {
		$title   = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Mad Mimi Sign Up Forms Form', 'madmimi-email-marketing' ) : $instance['title'], $instance, $this->id_base );
		$text    = empty( $instance['text'] ) ? '' : $instance['text'];
		$form_id = empty( $instance['form'] ) ? false : $instance['form'];

		echo $args['before_widget']; // xss ok

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // xss ok
		}

		if ( $text ) {
			echo wp_kses_post( apply_filters( 'mimi_widget_text', $text ) );
		}

		$renderer = new Mad_Mimi_Form_Renderer();
		$renderer->process( $form_id, true );

		echo $args['after_widget']; // xss ok
	}

	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text']  = $new_instance['text'];
		$instance['form']  = absint( $new_instance['form'] );

		return $instance;
	}

	/**
	 * Outputs the widget settings form.
	 *
	 * @param array $instance Current settings.
	 */
	function form( $instance ) {

		// Set defaults.
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'text'  => '',
			'form'  => 0,
		) );

		$forms = Mad_Mimi_Dispatcher::get_forms();
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'madmimi-email-marketing' ); ?></label>
			<br/>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Additional Text:', 'madmimi-email-marketing' ); ?></label>
			<br/>
			<textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
		</p>

		<p>

			<?php if ( ! empty( $forms->signups ) ) : ?>

				<label for="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>"><?php esc_html_e( 'Form:', 'madmimi-email-marketing' ); ?></label>
				<br/>
				<select name="<?php echo esc_attr( $this->get_field_name( 'form' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>" class="widefat">

					<?php foreach ( $forms->signups as $f ) : ?>
						<option value="<?php echo esc_attr( $f->id ); ?>" <?php selected( $instance['form'], $f->id ); ?>><?php echo esc_html( $f->name ); ?></option>
					<?php endforeach; ?>

				</select>

			<?php else : ?>

			<span><?php printf(
				_x( 'Please set up your Mad Mimi Sign Up Forms account in the %s.', 'link to settings page', 'madmimi-email-marketing' ),
				sprintf(
					'<a href="%s">%s</a>',
					esc_url( admin_url( 'options-general.php?page=mimi-settings' ) ),
					esc_html__( 'settings page', 'madmimi-email-marketing' )
				)
			); ?></span>

			<?php endif; ?>

		</p>
		<?php
	}
}
