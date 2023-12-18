<?php
// Creating the widget
class quotations_widget extends WP_Widget {

	// The construct part
	function __construct() {
		// Parent constructor has 3 parameters
		// 1. Widget ID
		// 2. Widget name
		// 3. Widget description
		parent::__construct(
			'quotations_widget', 
			__('Quotations Widget', 'quotations'), 
			array( 'description' => __( 'Widget to display a random quotation', 'quotations' ), )
		);
	}

	// Creating widget's front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		// Args before widget
		echo $args['before_widget'];
		// Widget title
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		// Widget Content : displays random quotation
		global $wpdb;
		$tableName = $wpdb->prefix . 'quotations';
		$row = $wpdb->get_row("SELECT id, content, author FROM $tableName ORDER BY RAND() LIMIT 1");
		if (is_null($row)) {
			echo __( 'Hello, World!', 'quotations' );
		}
		else {
			echo '<q>' . $row->content . '</q>&nbsp;<cite>' . $row->author . '</cite>';
		}
		// Args after widget
		echo $args['after_widget'];
	}

	// Creating widget Backend
	public function form( $instance ) {
		$title = __( 'Default title', 'quotations' );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:' ); ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		return $instance;
	}

// Class ends here
}