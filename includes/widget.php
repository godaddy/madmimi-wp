<?php

/**
 * Mad Mimi widget
 *
 * @since 2.8.0
 */
class Mad_Mimi_Form_Widget extends WP_Widget {

	function __construct() {

		parent::__construct( 'mimi-form', __( 'Mad Mimi Form', 'mad-mimi-sign-up-forms' ), array(
			'classname'   => 'mimi-form',
			'description' => __( 'Embed any Mad Mimi webform in your sidebar.', 'mad-mimi-sign-up-forms' ),
		) );

		foreach ( array( 'wpautop', 'wptexturize', 'convert_chars' ) as $filter ) {
			add_filter( 'mimi_widget_text', $filter );
		}

	}

	function widget( $args, $instance ) {

		// Set the initial form ID value if one exists.
		if ( empty( $instance['form'] ) ) {
			$forms = Mad_Mimi_Dispatcher::get_forms();

			if ( ! empty( $forms->signups ) ) {
				$instance['form'] = $forms->signups[0]->id;
			}
		}

		$title   = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Mad Mimi Form', 'mad-mimi-sign-up-forms' ) : $instance['title'], $instance, $this->id_base );
		$text    = empty( $instance['text'] ) ? '' : $instance['text'];
		$form_id = empty( $instance['form'] ) ? false : $instance['form'];

		echo $args['before_widget']; // xss ok.

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // xss ok.
		}

		if ( $text ) {
			echo wp_kses_post( apply_filters( 'mimi_widget_text', $text ) );
		}

		Mad_Mimi_Form_Renderer::process( $form_id, true );

		echo $args['after_widget']; // xss ok.

	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text']  = $new_instance['text'];
		$instance['form']  = absint( $new_instance['form'] );

		return $instance;

	}

	function form( $instance ) {

		// set defaults
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'text'  => '',
			'form'  => 0,
		) );

		$forms = Mad_Mimi_Dispatcher::get_forms(); ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'mad-mimi-sign-up-forms' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Additional Text:', 'mad-mimi-sign-up-forms' ); ?></label>
			<textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
		</p>

		<p>

			<?php if ( ! empty( $forms->signups ) ) : ?>

				<label for="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>"><?php esc_html_e( 'Form:', 'mad-mimi-sign-up-forms' ); ?></label>

				<select name="<?php echo esc_attr( $this->get_field_name( 'form' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>" class="widefat">

					<?php foreach ( $forms->signups as $f ) : ?>
						<option value="<?php echo esc_attr( $f->id ); ?>" <?php selected( $instance['form'], $f->id ); ?>><?php echo esc_html( $f->name ); ?></option>
					<?php endforeach; ?>

				</select>

			<?php else : ?>

			<span>
				<?php echo wp_kses(
					sprintf(
						/* translators: Link to the settings page. */
						esc_html__( 'Please set up your Mad Mimi account in the %s.', 'mad-mimi-sign-up-forms' ),
						sprintf(
							'<a href="%1$s">%2$s</a>',
							esc_url_raw( admin_url( 'options-general.php?page=mad-mimi-settings' ) ),
							esc_html__( 'settings page', 'mad-mimi-sign-up-forms' )
						)
					)
				); ?>
			</span>

			<?php endif; ?>

		</p>

	<?php }

}
