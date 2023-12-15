<?php
/*
Plugin Name: Quotations
Description: Displays quotations randomly in WordPress admin notice
Version: 1.0
Author: Christiane Dumont
Author URI: https://www.consultantweb.eu
License: GPL2
Text Domain: quotations
Domain Path: /languages
*/

// Si ce fichier est appelé directement, on sort
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $quotations_db_version;
$quotations_db_version = '1.0';

// Fonction appelée lors de l'activation du plugin
// Création de la table
function quotations_activate() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$tableName = $wpdb->prefix . 'quotations';
	$sql = "CREATE TABLE IF NOT EXISTS $tableName (
		id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		content VARCHAR(255) NOT NULL, 
		author VARCHAR(50) NOT NULL,
		creationDate datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY_KEY (id)
		) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	// Sauvegarder la version courante de la DB
    add_option('quotations_db_version', $quotations_db_version);
}

// Fonction appelée lors de l'activation du plugin
// Insertion de données
function quotations_install_data() {
	global $wpdb;
	$tableName = $wpdb->prefix . 'quotations';
	$wpdb->insert($tableName, array(
        'content' => 'La nature ne fait rien sans objet',
        'author' => 'Aristote'
    ));
	$wpdb->insert($tableName, array(
        'content' => "J'ai décidé d'être heureux parce que c'est bon pour la santé.",
        'author' => 'Voltaire'
    ));
	$wpdb->insert($tableName, array(
        'content' => "La raison nous trompe plus souvent que la nature",
        'author' => 'Vauvenargues'
    ));
}

register_activation_hook( __FILE__, 'quotations_activate' );
register_activation_hook( __FILE__, 'quotations_install_data' );

// Fonction appelée lors de la désactivation du plugin
function quotations_deactivate() {
	global $wpdb;
	$tableName = $wpdb->prefix . 'quotations';
	$sql = "TRUNCATE TABLE $tableName";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

register_deactivation_hook( __FILE__, 'zeronews_deactivate' );
// Fonction appelée lors de la désinstallation du plugin
function quotations_uninstall() {
	global $wpdb;
	$tableName = $wpdb->prefix . 'quotations';
	$sql = "DROP TABLE IF EXISTS $table_name";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_uninstall_hook(__FILE__, 'quotations_uninstall');

// Récupérer une citation aléatoire
function quotations_get_random() {
	global $wpdb;
	$tableName = $wpdb->prefix . 'quotations';
	$row = $wpdb->get_row("SELECT id, content, author FROM $tableName ORDER BY RAND() LIMIT 1");
	//var_dump($row);
	if (is_null($row)) {
		return '';
	}
	return '<q>' . $row->content . '</q>&nbsp;<cite>' . $row->author . '</cite>';
}

// Appel de la fonction qui va sélectionner une citation aléatoire
function quotations() {
	$quotation = quotations_get_random();
	echo '<div class="quotation">'.$quotation.'</div>';
}

// Gestion des traductions
function quotations_languages()
{
    load_plugin_textdomain('quotations', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'quotations_languages');

// Affichage de la citation dans le back-office
add_action( 'admin_notices', 'quotations' );

// Chargement de la feuille de style
function quotations_stylesheet()
{
    wp_enqueue_style( 'quotation_css', plugins_url( 'style.css', __FILE__ ) );
}
add_action('admin_print_styles', 'quotations_stylesheet');

require_once "includes/quotations-admin-menu.php";