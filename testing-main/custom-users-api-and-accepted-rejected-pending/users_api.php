<?php
/*
Plugin Name: custom users api
Descriptions: This plugin is used to perform CRUD operation using API
version: 1.0
Author: WordPress Tutorial
*/

include plugin_dir_path(__FILE__) . 'user_list.php';

function all_users_api_endpoint() {
    register_rest_route('custom/v1', '/get_all_users/', array(
        'methods'             => 'POST',
        'callback'            => 'get_users_callback',
        'permission_callback' => '__return_true',
    ));
}

add_action('rest_api_init', 'all_users_api_endpoint');

function insert_user_data_into_custom_table($user_id, $name, $email, $role) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_users';

    $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'name'    => $name,
            'email'   => $email,
            'role'    => $role,
        ),
        array('%d', '%s', '%s', '%s')
    );
}

function get_users_callback($data) {
    $name = isset($data['name']) ? sanitize_text_field($data['name']) : '';

    $args = array(
        'search'         => '*' . $name . '*',
        'search_columns' => array('user_login', 'user_nicename', 'user_email', 'user_url'),
    );

    $users = get_users($args);
    $response_data = array();

    foreach ($users as $user) {
        $user_id = $user->ID;
        $name    = $user->display_name;
        $email   = $user->user_email;
        $role    = $user->roles[0];

        insert_user_data_into_custom_table($user_id, $name, $email, $role);

        $user_data = array(
            'id'    => $user_id,
            'name'  => $name,
            'email' => $email,
            'role'  => $role,
        );

        $response_data[] = $user_data;
    }

    return rest_ensure_response($response_data);
}

?>
