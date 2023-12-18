<?php

/** À des fins d'internationalisation, utiliser __('english string', 'nom_du_plugin') pour récupérer les chaînes de caractères à traduire et _e('english string', 'nom_du_plugin') pour afficher les informations.
*/

/**
 * Register a custom admin menu
 */
function quotations_admin_menu() {
	// add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $callback = '', string $icon_url = '', int|float $position = null ): string
	// Lien de menu principal
	add_menu_page(
		__('Quotations administration', 'quotations'),
		__('Quotations', 'quotations'),
		'manage_options', 
		'quotations/admin/quotations-list.php', 
		''
	);
	// Sous-menu
	add_submenu_page(
		'quotations/admin/quotations-list.php', 
		__('Quotations administration', 'quotations'), 
		__('List', 'quotations'), 
		'manage_options', 
		'quotations/admin/quotations-list.php', 
		'');
	// Sous-menu
	add_submenu_page(
		'quotations/admin/quotations-list.php', 
		__('Add quotation', 'quotations'),
		__('Add', 'quotations'), 
		'manage_options', 
		'quotations/admin/quotations-form.php', 
		''
	);
}
add_action( 'admin_menu', 'quotations_admin_menu' );