<?php

class Mad_Mimi_Form_Renderer {

	private static $loops = 0;

	public static function process( $form_id, $echo = false ) {

		$form = Mad_Mimi_Dispatcher::get_fields( (int) $form_id );

		if ( ! empty( $form->fields ) ) :

			self::$loops++;

			ob_start();

			?>

			<div class="mimi-form-wrapper" id="form-<?php echo esc_attr( absint( $form_id ) ); ?>">
				<form action="<?php echo esc_url( $form->submit ); ?>" method="post" class="mimi-form">

					<?php do_action( 'mimi_before_fields', $form_id, $form->fields ); ?>

					<?php foreach ( $form->fields as $count => $field ) : ?>

						<p><?php Mad_Mimi_Form_Fields::dispatch_field( $field, self::$loops ); ?></p>

					<?php endforeach; ?>

					<?php do_action( 'mimi_after_fields', $form_id, $form->fields ); ?>

					<?php $show_powered_by = Mad_Mimi_Settings_Controls::get_option( 'display_powered_by' ) ? true : false;

					if ( $show_powered_by ) : ?>

						<p>
							<a href="https://madmimi.com" target="_blank"><?php esc_html_e( 'Powered by Mad Mimi', 'mad-mimi-sign-up-forms' ); ?></a>
						</p>

					<?php endif; ?>

					<input type="hidden" name="form_id" value="<?php echo esc_attr( absint( $form->id ) ); ?>" />
					<input type="submit" value="<?php echo esc_attr( $form->button_text ); ?>" class="button mimi-submit" />

					<span class="mimi-spinner"></span>

				</form>
			</div>

			<?php $output = ob_get_clean();

			if ( $echo ) {

				echo $output; // xss ok.

			}

			return $output;

		endif;

	}

}

class Mad_Mimi_Form_Fields {

	private static $cycle = 0;

	public static function dispatch_field( $field, $cycle = 1 ) {

		if ( ! is_object( $field ) || ! method_exists( __CLASS__, $field->type ) ) {

			return;

		}

		self::$cycle = absint( $cycle );

		if ( ! is_null( $field->field_type ) ) {

			call_user_func( array( __CLASS__, $field->field_type ), $field );

		} else {

			call_user_func( array( __CLASS__, $field->type ), $field );

		}

	}

	public static function get_form_id( $field_name ) {

		// since HTML ID's can't exist in the same exact spelling more than once... make it special.
		return sprintf( 'form_%s_%s', self::$cycle, $field_name );

	}

	public static function string( $args ) {

		$field_classes = array( 'mimi-field' );

		// is this field required?
		if ( $args->required ) {

			$field_classes[] = 'mimi-required';

		}

		$field_classes = (array) apply_filters( 'mimi_required_field_class', $field_classes, $args ); ?>

		<label for="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>">

			<?php echo esc_html( $args->display ); ?>

			<?php if ( $args->required && apply_filters( 'mimi_required_field_indicator', true, $args ) ) : ?>

				<span class="required">*</span>

			<?php endif; ?>

		</label>

		<input type="text" name="<?php echo esc_attr( $args->name ); ?>" id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" class="<?php echo esc_attr( join( ' ', $field_classes ) ); ?>" />

		<?php

	}

	//this was the old checkbox function
	public static function checkbox( $args ) {

		$field_classes = array( 'mimi-checkbox' );

		// is this field required?
		if ( $args->required ) {

			$field_classes[] = 'mimi-required';

		}

		$field_classes = (array) apply_filters( 'mimi_required_field_class', $field_classes, $args ); ?>

		<label for="<?php echo esc_attr( self::get_form_id( $args->name ) . $args->value ); ?>">

			<input type="checkbox" value="<?php echo esc_attr( $args->value ); ?>" name="<?php echo esc_attr( $args->name ); ?>" id="<?php echo esc_attr( self::get_form_id( $args->name ) . $args->value ); ?>" class="<?php echo esc_attr( join( ' ', $field_classes ) ); ?>" />

			<?php echo esc_html( $args->display ); ?>

			<?php if ( $args->required && apply_filters( 'mimi_required_field_indicator', true, $args ) ) : ?>

				<span class="required">*</span>

			<?php endif; ?>

		</label>

	<?php }

	public static function checkboxes( $args ) {

		$field_classes = array( 'mimi-checkbox' );

		if ( $args->required ) {

			$field_classes[] = 'mimi-required';

		}

		$field_classes = (array) apply_filters( 'mimi_required_field_class', $field_classes, $args ); ?>

		<label for="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>">

			<?php echo esc_html( $args->display ); ?>

			<?php if ( $args->required && apply_filters( 'mimi_required_field_indicator', true, $args ) ) : ?>

				<span class="required">*</span>

			<?php endif; ?>

		</label>

		</br>

		<?php

		$trim_values = array( '[', ']' );
		$options     = $args->options;

		foreach ( $trim_values as $trim ) {

			$options = trim( $options, $trim );

		}

		$trimmed_options = array();
		$options         = str_replace( '"', '', $options );
		$trimmed_options = explode( ',', $options );

		foreach ( $trimmed_options as $key => $value ) { ?>

			<input type="checkbox" id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" name="<?php echo esc_attr( $args->name ); ?>" value="<?php echo esc_attr( $value ); ?>"> <?php echo esc_html( $value ); ?><br>

		<?php } ?>

	<?php }


	public static function dropdown( $args) {

		$field_classes = array( 'mimi-checkbox' );

		if ( $args->required ) {

			$field_classes[] = 'mimi-required';

		}

		$field_classes = (array) apply_filters( 'mimi_required_field_class', $field_classes, $args ); ?>

		<label for="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>">

			<?php echo esc_html( $args->display ); ?>

			<?php if ( $args->required && apply_filters( 'mimi_required_field_indicator', true, $args ) ) : ?>

				<span class="required">*</span>

			<?php endif; ?>

		</label>

		</br>

		<select id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" name="<?php echo esc_attr( $args->name ); ?>">

		<?php

		$trim_values = array( '[', ']' );

		$options = $args->options;

		foreach ( $trim_values as $trim ) {

			$options = trim( $options, $trim );

		}

		$trimmed_options = array();
		$options         = str_replace( '"', '', $options );
		$trimmed_options = explode( ',', $options );

		foreach ( $trimmed_options as $dropdown_options ) { ?>

			<option value="<?php echo esc_attr( $dropdown_options ); ?>"> <?php echo $dropdown_options; // xss ok. ?><br>

		<?php

		}

		?>

		</select>

		<?php

	}

	public static function radio_buttons( $args ) {

		$field_classes = array( 'mimi-checkbox' );

		if ( $args->required ) {

			$field_classes[] = 'mimi-required';

		}

		$field_classes = (array) apply_filters( 'mimi_required_field_class', $field_classes, $args ); ?>

		<label for="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>">

			<?php echo esc_html( $args->display ); ?>

			<?php if ( $args->required && apply_filters( 'mimi_required_field_indicator', true, $args ) ) : ?>

				<span class="required">*</span>

			<?php endif; ?>

		</label>

		</br>

		<?php

		$trim_values = array( '[', ']' );
		$options     = $args->options;

		foreach ( $trim_values as $trim ) {

			$options = trim( $options, $trim );

		}

		$trimmed_options = array();
		$options         = str_replace( '"', '', $options );
		$trimmed_options = explode( ',', $options );

		foreach ( $trimmed_options as $radio_options ) { ?>

				<input type="radio" id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" name="<?php echo esc_attr( $args->name ); ?>" value="<?php echo esc_attr( $radio_options ); ?>"> <?php echo $radio_options; // xss ok. ?><br>

		<?php } ?>

	<?php }

	public static function date( $args ) {

		$field_classes = array( 'mimi-checkbox' );

		if ( $args->required ) {

			$field_classes[] = 'mimi-required';

		}

		$field_classes = (array) apply_filters( 'mimi_required_field_class', $field_classes, $args ); ?>

		<label for="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>">

			<?php echo esc_html( $args->display ); ?>

			<?php if ( $args->required && apply_filters( 'mimi_required_field_indicator', true, $args ) ) : ?>

				<span class="required">*</span>

			<?php endif; ?>

		</label>

		</br>

		<?php $current_year = date( 'Y' ); ?>

			<span class="third">
				<select fingerprint="date" id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" name="<?php echo esc_attr( $args->name );?>">
					<option value="00"><?php esc_html_e( 'Month', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="January"><?php esc_html_e( 'January', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="February"><?php esc_html_e( 'Febuary', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="March"><?php esc_html_e( 'March', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="April"><?php esc_html_e( 'April', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="May"><?php esc_html_e( 'May', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="June"><?php esc_html_e( 'June', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="July"><?php esc_html_e( 'July', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="August"><?php esc_html_e( 'August', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="September"><?php esc_html_e( 'September', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="October"><?php esc_html_e( 'October', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="November"><?php esc_html_e( 'November', 'mad-mimi-sign-up-forms' ); ?></option>
					<option value="December"><?php esc_html_e( 'December', 'mad-mimi-sign-up-forms' ); ?></option>
				</select>
			</span>
			<span class="third">
				<select fingerprint="date" id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" name="<?php echo esc_attr( $args->name ); ?>">
					<option value="00"><?php esc_html_e( 'Day', 'mad-mimi-sign-up-forms' ); ?></option>
					<?php for ( $i = 1; $i < 32; $i++ ) { ?>
						<option value="<?php echo esc_attr( strlen( $i ) < 2 ? '0' . $i : $i ); ?>">
							<?php echo esc_html( $i ); ?>
						</option>
					<?php } ?>
				</select>
			</span>
			<span class="third">
			<select fingerprint="date" id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" name="<?php echo esc_attr( $args->name ); ?>">
				<option value="00"><?php esc_html_e( 'Year', 'mad-mimi-sign-up-forms' ); ?></option>
				<?php for ( $x = $current_year + 5; $x > $current_year - 81; $x-- ) { ?>
					<option value="<?php echo esc_html( $x ); ?>">
						<?php echo esc_html( $x ); ?>
					</option>
					<?php } ?>
				</select>
			</span>

		<input type="hidden" id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" name="<?php echo esc_attr( $args->name ); ?>" value="">

	<?php

	}

	public static function text_field( $args ) {

		$field_classes = array( 'mimi-field' );

		// is this field required?
		if ( $args->required ) {

			$field_classes[] = 'mimi-required';

		}

		$field_classes = (array) apply_filters( 'mimi_required_field_class', $field_classes, $args ); ?>

		<label for="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>">

			<?php echo esc_html( $args->display ); ?>

			<?php if ( $args->required && apply_filters( 'mimi_required_field_indicator', true, $args ) ) : ?>

				<span class="required">*</span>

			<?php endif; ?>

		</label>

		<input type="text" name="<?php echo esc_attr( $args->name ); ?>" id="<?php echo esc_attr( self::get_form_id( $args->name ) ); ?>" class="<?php echo esc_attr( join( ' ', $field_classes ) ); ?>" />

		<?php

	}

}
