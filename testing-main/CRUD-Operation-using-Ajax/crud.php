<?php
/*
Plugin Name: Crud using ajax
Description: Crud plugin
Version: 1.0
Author: WordPress Tutorial
*/

function enque_script_and_style()
{
    wp_enqueue_script('custom-script', plugins_url('crud.js', __FILE__), array('jquery'), '1.0', true);

    wp_localize_script('custom-script', 'custom_script_params', array(
        'ajax_url'   => admin_url('admin-ajax.php'),

    ));
}

add_action('wp_enqueue_scripts', 'enque_script_and_style');
// Start output buffering
function my_crud_shortcode()
{
    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Page Title</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    </head>


    <body>
        <div class="container mt-5">
            <div class="">
                <form action="" class="" id="myform" method="post">
                    
                    <h3 class="alert-warning text-center p-2">Add/Update Student</h3>
                    <input type="hidden" name="user-id" id="user-id" value="">
                    <div>
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="nameid">
                    </div>
                    <div>
                        <label for="age" class="form-label">Age</label>
                        <input type="text" class="form-control" name="age" id="ageid">
                    </div><br>
                    <div>
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="genderid" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailid" name="email">
                    </div>
                    <div>
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phoneid" name="phone">
                    </div>
                    <div>
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="addressid" name="address"></textarea>
                    </div>
                    <div>
                        <label for="grade" class="form-label">Grade</label>
                        <input type="text" class="form-control" id="gradeid" name="grade">
                    </div>
                    <div>
                        <label for="performane" class="form-label">Performance</label>
                        <input type="text" class="form-control" id="performanceid" name="performance">
                    </div><br>
                    <div class="mt-5">
                        <button type="submit" id="add">Add</button>
                        <button type="button" id="update" style="display: none;">Update</button>
                    </div><br><br>
                </form>
                <div class="text-center">
                    <h3 class="alert-warning p-2">Show Student Information</h3>
                    <table class="table" id="user-table">
                        <thead>
                            <tr>

                                <th scope="col">Name</th>
                                <th scope="col">Age</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Address</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Performance</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>

                </div>
            </div>
        </div>

    </body>

    </html>
<?php
    $output = ob_get_clean(); 
    return $output; 
}

add_shortcode('my_crud_shortcode', 'my_crud_shortcode');

//insert
function add_user_ajax()
{
    if (isset($_POST['user_data'])) {
        $user_data = $_POST['user_data'];

        $name = sanitize_text_field($user_data['name']);
        $age = sanitize_text_field($user_data['age']);
        $gender = sanitize_text_field($user_data['gender']);
        $email = sanitize_text_field($user_data['email']);
        $phone = sanitize_text_field($user_data['phone']);
        $address = sanitize_text_field($user_data['address']);
        $grade = sanitize_text_field($user_data['grade']);
        $performance = sanitize_text_field($user_data['performance']);


        global $wpdb;
        $table_name = $wpdb->prefix . 'student_info';

        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'age' => $age,
                'gender' => $gender,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'grade' => $grade,
                'performance' => $performance
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        if ($result !== false) {
            wp_send_json_success('user added successfully');
        } else {
            wp_send_json_error('user not added');
        }
    } else {
        wp_send_json_error('Invalid data');
    }
}


add_action('wp_ajax_add_user_ajax', 'add_user_ajax');

//update
function update_user_ajax()
{
    if (isset($_POST['user_data'])) {
        $user_data = $_POST['user_data'];


        $user_id = sanitize_text_field($user_data['user_id']);
        $name = sanitize_text_field($user_data['name']);
        $age = sanitize_text_field($user_data['age']);
        $gender = sanitize_text_field($user_data['gender']);
        $email = sanitize_text_field($user_data['email']);
        $phone = sanitize_text_field($user_data['phone']);
        $address = sanitize_text_field($user_data['address']);
        $grade = sanitize_text_field($user_data['grade']);
        $performance = sanitize_text_field($user_data['performance']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'student_info';

        $result = $wpdb->update(
            $table_name,
            array(
                'name' => $name,
                'age' => $age,
                'gender' => $gender,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'grade' => $grade,
                'performance' => $performance

            ),
            array(
                'id' => $user_id
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ),
        );

        if ($result) {
            wp_send_json_success('user updated successfully');
        } else {
            wp_send_json_error('user not updated');
        }
    } else {
        wp_send_json_error('Invalid data');
    }
    exit();
}

add_action('wp_ajax_update_user_ajax', 'update_user_ajax');


//delete

function delete_user_ajax()
{
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        global $wpdb;
        $table_name = $wpdb->prefix . 'student_info';

        $result = $wpdb->delete(
            $table_name,
            array(
                'id' => $user_id
            ),
            array(
                '%d'
            )
        );

        if ($result) {
            wp_send_json_success('user deleted successfully');
        } else {
            wp_send_json_error('user not deleted');
        }
    } else {
        wp_send_json_error('Invalid data');
    }
    exit();
}

add_action('wp_ajax_delete_user_ajax', 'delete_user_ajax');


//Retrieve data

function get_user_data_ajax()
{
    global $wpdb;


    $user = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'student_info');

    if ($user !== false) {
        wp_send_json($user);
    } else {
        wp_send_json_error('Error retrieving user data');
    }
}

add_action('wp_ajax_get_user_data_ajax', 'get_user_data_ajax');
