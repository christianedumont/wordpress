<?php

global $wpdb;
$table_name = $wpdb->prefix . 'quotations';

$current_user = wp_get_current_user();
$validToken = $current_user->ID . '-' . $current_user->user_login;
$content = '';
$author = '';
$message = '';
$error = '';
// Structure par défaut dans laquelle nous allons enregistrer un élément
$default = array(
	'id' => 0,
	'content' => '',
	'author' => ''
);

// Vérifier si le jeton a été transmis et est valide
if (wp_verify_nonce($_REQUEST['tokenonce'], $validToken)) {
	// Récupérer les données
	$item = shortcode_atts($default, $_REQUEST);
	//var_dump($item);
	// Valider les données
	if ( empty($item['content']) || empty($item['author']) ) {
		$error = __('Please fill in all the required fields', 'quotations');
	}
	else {
		// Insertion d'une nouvelle citation
		if ($item['id'] == 0) {
			// Insertion dans la base de données
			$result = $wpdb->insert($table_name, $item);
			if ($result) {
				$item['id'] = $wpdb->insert_id;
				$message = sprintf(__('Quotation %d was successfully saved', 'quotations'), $item['id']);
				$item = $default;
			}
			else {
				$error = __('Error while inserting quotation', 'quotations');
			}
		}
		else {
			// Modification dans la base de données
			$result = $wpdb->update($table_name, $item, array('id' => $item['id']));
			if ($result) {
				$message = sprintf(__('Quotation %d has been successfully updated', 'quotations'), $item['id']);
			} else {
				$error = __('Error while updating quotation', 'quotations');
			}
			//$message = 'Modification id '.$item['id'];
		}
	}
}
else {
	// Récupérer les données de la table et les afficher
	$item = $default;
	if (isset($_REQUEST['id'])) {
        $item = $wpdb->get_row($wpdb->prepare("SELECT id, content, author FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
		if (!$item) {
			$item = $default;
			$error = __('Quotation not found', 'quotations');
		}
	}
}

require_once 'views/quotations-form-view.php';