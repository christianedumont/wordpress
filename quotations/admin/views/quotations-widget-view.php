<div class="form-row">
	<label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:' ); ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" value="<?php echo esc_attr( $title ); ?>" />
</div>