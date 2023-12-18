<?php

require_once "includes/class-quotations-widget.php";

function quotations_load_widget() {
    register_widget( 'quotations_widget' );
}
add_action( 'widgets_init', 'quotations_load_widget' );