<?php
/**
 * Plugin Name: Custom Post List Table
 * Description: A custom table to display user data.
 * Author: WordPress Tutorial
 */


if (!function_exists('add_action')) {
    echo 'Not allowed';
    exit;
}

// Include necessary WordPress files
require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class Custom_User_List_Table extends WP_List_Table {
    
    public function __construct() {
        parent::__construct(array(
            'singular' => 'user',
            'plural'   => 'users',
            'ajax'     => false,
        ));
    }

    public function column_default($item, $column_name) {
        return $item[$column_name];
    }

    public function column_usermeta($item) {
        $user_id = $item['ID'];
        $metadata_html = '';
        $number = get_user_meta($user_id, 'number', true);
        $date_of_birth = get_user_meta($user_id, 'dob', true);
        $address = get_user_meta($user_id, 'address', true);
        $gender = get_user_meta($user_id, 'gender', true);
        
        if (!empty($number)) {
            $metadata_html .= '<strong>Number:</strong> ' . esc_html($number) . '<br>';
        }
        
        if (!empty($date_of_birth)) {
            $metadata_html .= '<strong>Date of Birth:</strong> ' . esc_html($date_of_birth) . '<br>';
        }
        
        if (!empty($address)) {
            $metadata_html .= '<strong>Address:</strong> ' . esc_html($address) . '<br>';
        }
        
        if (!empty($gender)) {
            $metadata_html .= '<strong>Gender:</strong> ' . esc_html($gender) . '<br>';
        }
        
        return $metadata_html;
    }

    public function get_columns() {
        $columns = array(
            'username'   => 'Name',
            'user_email' => 'Email',
            'usermeta'   => 'Other Details',
        );
        return $columns;
    }
    
    public function prepare_items() {
        global $wpdb;

        $query = "SELECT ID, user_login AS username, user_email FROM $wpdb->users";
        $data = $wpdb->get_results($query, ARRAY_A);

        $per_page = 10;         
        $current_page = $this->get_pagenum();
        $total_items = count($data);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ));

        $data = array_slice($data, ($current_page - 1) * $per_page, $per_page);

        $this->_column_headers = array($this->get_columns(), array(), array('usermeta'));
        $this->items = $data;
    }
}

function my_shortcode() {
    $message = "<h1>Custom User List</h1>";
    return $message;
}

add_shortcode('greeting', 'my_shortcode');

function custom_users_list_page() {
    $user_list = new Custom_User_List_Table();
    $user_list->prepare_items();
    
    echo do_shortcode('[greeting]');
    $user_list->display();
}

function custom_users_list_menu() {
    add_menu_page(
        'Custom User List', 
        'User List', 
        'manage_options', 
        'custom-users-list', 
        'custom_users_list_page'
    );
}

add_action('admin_menu', 'custom_users_list_menu');
