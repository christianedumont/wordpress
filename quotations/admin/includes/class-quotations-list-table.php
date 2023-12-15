<?php
/**
* quotations_List_Table class that will display our custom table
* records in nice table
*/

/** À des fins d'internationalisation, utiliser __('english string', 'nom_du_plugin') pour récupérer les chaînes de caractères à traduire et _e('english string', 'nom_du_plugin') pour afficher les informations.
*/

class Quotations_List_Table extends WP_List_Table
{
    /**
    * [REQUIRED] You must declare constructor and give some basic params
    */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => __( 'Quotation', 'quotations' ),
            'plural' => __( 'Quotations', 'quotations' ),
        ));
    }

    /**
	* [REQUIRED] this is a default column renderer
	*
	* @param $item - row (key, value array)
	* @param $column_name - string (key)
	* @return HTML
	*/
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }


    /**
	* This is an example, how to render column with actions,
	* when you hover row "Edit | Delete" links showed
	*
	* @param $item - row (key, value array)
	* @return HTML
	*/
    function column_content($item)
    {
        // Liens destinés à pouvoir éditer ou supprimer un élément de la liste
        $actions = array(
            'edit' => sprintf('<a href="?page=quotations/admin/quotations-form.php&id=%s">%s</a>', $item['id'], __('Edit', 'quotations')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'quotations')),
        );

        return sprintf('%s %s',
            $item['content'],
            $this->row_actions($actions)
        );
    }

    /**
	* [REQUIRED] this is how checkbox column renders
	*
	* @param $item - row (key, value array)
	* @return HTML
	*/
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
	* [REQUIRED] This method return columns to display in table
	* you can skip columns that you do not want to show
	* like content, or description
	*
	* @return array
	*/
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox">',
            'author' => __('Author', 'quotations'),
            'content' => __('Content', 'quotations'),
        );
        return $columns;
    }

    /**
	* [OPTIONAL] This method return columns that may be used to sort table
	* all strings in array - is column names
	* notice that true on name column means that its default sort
	*
	* @return array
	*/
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'content' => array('content', true),
            'author' => array('author', false),
        );
        return $sortable_columns;
    }

    /**
	* [OPTIONAL] Return array of bult actions if has any
	*
	* @return array
	*/
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
	* [OPTIONAL] This method processes bulk actions
	* it can be outside of class
	* it can not use wp_redirect coz there is output already
	* in this example we are processing delete action
	* message about successful deletion will be shown on page in next part
	*/
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'quotations'; // do not forget about tables prefix

        if ($this->current_action() === 'delete') {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    /**
	* [REQUIRED] This is the most important method
	*
	* It will get rows from database and prepare them to be showed in table
	*/
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'quotations';
        $per_page = 5; // constant, how much records will be shown per page
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // Here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // Will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // Prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // Notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT id, content, author, creationDate FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}