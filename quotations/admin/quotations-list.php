<?php

require_once "includes/class-quotations-list-table.php";

global $wpdb;
$table = new Quotations_List_Table();
$table->prepare_items();
$message = '';

require_once 'views/quotations-list-view.php';