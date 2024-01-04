<?php
/*
Plugin Name: Custom Search Option
Description: Custom search option for custom post type
Author: WordPress Tutorial
*/

include plugin_dir_path(__FILE__) . 'search.php';

function search_shortcode_options(){
    ob_start();
    search_option();
    return ob_get_clean();
}

add_shortcode('search','search_shortcode_options');
?>