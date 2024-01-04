<?php
/**
 * Plugin Name: Custom Drop Down
 * Description: Custom Drop Down Menu Plugin
 * Author: WordPress Tutorial
 */

// Enqueue scripts
function enqueue_my_script() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-drop', plugin_dir_url(__FILE__) . 'drop.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-drop', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_my_script');

// Shortcode for custom drop-down
function custom_drop_down_shortcode() {
    ob_start(); ?>
    
    <form>
        <label for="country">Country:</label>
        <select id="country">
            <option value="">Select country</option>
            <?php 
            global $wpdb;
            $table_name = $wpdb->prefix . 'countries';
            $countries = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
       
            
            if ($countries) {
                foreach ($countries as $row) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    
                <?php }
            } ?>
        </select>

        <label for="state">State:</label>
        <select id="state">
            <option value="">Select state</option>
        </select>
        
        <label for="city">City:</label>
        <select id="city">
            <option value="">Select city</option>
        </select>
    </form>

    <?php return ob_get_clean();
}
add_shortcode('custom_drop_down', 'custom_drop_down_shortcode');

// AJAX callback to get states
function get_states_callback() {
    if (isset($_POST['ID'])) {
        $id = $_POST['ID'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'states';
        $states = $wpdb->get_results("SELECT * FROM $table_name WHERE country_id='$id'", ARRAY_A);
        
        if ($states) {
            echo "<option value=''>select state</option>";
            foreach ($states as $row) {
              
                $id = $row['id'];
                $state = $row['name'];
            
                echo "<option value='$id'>$state</option>";
            }
        }
    }
    wp_die();
}
add_action('wp_ajax_get_states', 'get_states_callback');
add_action('wp_ajax_nopriv_get_states', 'get_states_callback');

// AJAX callback to get cities
function get_cities_callback() {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'cities';
        $cities = $wpdb->get_results("SELECT * FROM $table_name WHERE state_id='$id'", ARRAY_A);

        if ($cities) {
            echo "<option value=''>select city</option>";
            foreach ($cities as $row) {
                $id = $row['id'];
                $cityName = $row['name'];
                echo "<option value='$id'>$cityName</option>";
            }
        }
    }
    wp_die();
}
add_action('wp_ajax_get_cities', 'get_cities_callback');
add_action('wp_ajax_nopriv_get_cities', 'get_cities_callback');
?>
