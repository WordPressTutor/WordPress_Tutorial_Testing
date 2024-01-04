<?php
/*
Plugin Name: My custom Registration Plugin
Description: This is a custom registration plugin
Version: 1.0
Author: WordPress Tutorial
*/

function my_register_plugin_menu()
{
    add_menu_page(
        'My Register Menu',
        'Registration',
        'manage_options',
        'my-register-plugin',
        'my_register_plugin_page',
        'dashicons-admin-plugins'
    );
    add_submenu_page(
        'my-register-plugin',
        'Edit User',
        'Edit User',
        'manage_options',
        'edit-user',
        'edit_user_page'
    );

    add_menu_page(
        'My Register List',
        'Registration List',
        'manage_options',
        'my-register-list-plugin',
        'my_register_list_page',
        'dashicons-admin-list-plugin'
    );
}
add_action('admin_menu', 'my_register_plugin_menu');

function my_register_plugin_page()
{
    if (isset($_POST['submit'])) {
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $number = sanitize_text_field($_POST['number']);
        $dob = sanitize_text_field($_POST['dob']);
        $address = sanitize_text_field($_POST['address']);
        $gender = sanitize_text_field($_POST['gender']);

        $user_id = wp_insert_user(array(
            'user_login' => $username,
            'user_pass' => wp_hash_password($password),
            'user_email' => $email,
        ));

        if (!is_wp_error($user_id)) {
            update_user_meta($user_id, 'number', $number);
            update_user_meta($user_id, 'dob', $dob);
            update_user_meta($user_id, 'address', $address);
            update_user_meta($user_id, 'gender', $gender);
            echo '<div class="updated"><p>Data has been successfully saved!</p></div>';
        } else {
            echo '<div class="error"><p>Failed to save data!</p></div>';
        }
    }
    
    ?>
    <form id="registration-form" action="" method="post">
        <h1>Registration Form</h1>
        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br>

        <label for="number">Number:</label><br>
        <input type="text" name="number"><br>

        <label for="dob">Date of Birth:</label><br>
        <input type="date" name="dob"><br>

        <label for="address">Address:</label><br>
        <textarea name="address" cols="30" rows="5"></textarea><br>

        <label for="gender">Gender:</label><br>
        <select name="gender">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br><br>

        <input type="submit" name="submit" value="Register">
    </form>
    <?php
    
}
function edit_user_page()
{
    if (isset($_GET['edit_user'])) {
        $user_id = $_GET['edit_user'];
        $user = get_userdata($user_id);

        if ($user) {
            if (isset($_POST['update_user'])) {
                // Handle user data update here
                $username = sanitize_user($_POST['username']);
                $email = sanitize_email($_POST['email']);
                $number = sanitize_text_field($_POST['number']);
                $dob = sanitize_text_field($_POST['dob']);
                $address = sanitize_text_field($_POST['address']);
                $gender = sanitize_text_field($_POST['gender']);

                $user_data = array(
                    'ID' => $user_id,
                    'user_login' => $username,
                    'user_email' => $email,
                );

                if (wp_update_user($user_data)) {
                    update_user_meta($user_id, 'number', $number);
                    update_user_meta($user_id, 'dob', $dob);
                    update_user_meta($user_id, 'address', $address);
                    update_user_meta($user_id, 'gender', $gender);
                    echo '<div class="updated"><p>User data has been successfully updated!</p></div>';
                } else {
                    echo '<div class="error"><p>Failed to update user data.</p></div>';
                }
            }

            ?>
            <form id="edit-user-form" action="" method="post">
                <h1>Edit User</h1>
                <label for="username">Username:</label><br>
                <input type="text" name="username" value="<?php echo esc_attr($user->user_login); ?>" required><br>

                <label for="email">Email:</label><br>
                <input type="email" name="email" value="<?php echo esc_attr($user->user_email); ?>" required><br>

                <label for="number">Number:</label><br>
                <input type="text" name="number" value="<?php echo esc_attr(get_user_meta($user_id, 'number', true)); ?>"><br>

                <label for="dob">Date of Birth:</label><br>
                <input type="date" name="dob" value="<?php echo esc_attr(get_user_meta($user_id, 'dob', true)); ?>"><br>

                <label for="address">Address:</label><br>
                <textarea name="address" cols="30" rows="5"><?php echo esc_textarea(get_user_meta($user_id, 'address', true)); ?></textarea><br>

                <label for="gender">Gender:</label><br>
                <select name="gender">
                    <option value="male" <?php selected('male', get_user_meta($user_id, 'gender', true)); ?>>Male</option>
                    <option value="female" <?php selected('female', get_user_meta($user_id, 'gender', true)); ?>>Female</option>
                    <option value="other" <?php selected('other', get_user_meta($user_id, 'gender', true)); ?>>Other</option>
                </select><br><br>

                <input type="submit" name="update_user" value="Update User">
            </form>
            <?php
        } else {
            echo '<div class="error"><p>User not found.</p></div>';
        }
    }
}

function my_register_list_page()
{
    $users = get_users(array('meta_key' => 'number', 'meta_query' =>
    array(array('key' => 'number', 'compare' => 'EXISTS'))));

    ?>
    <div class="wrap">
        <h2>Registration List</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Date of Birth</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo esc_html($user->user_login); ?></td>
                        <td><?php echo esc_html($user->user_email); ?></td>
                        <td><?php echo esc_html(get_user_meta($user->ID, 'number', true)); ?></td>
                        <td><?php echo esc_html(get_user_meta($user->ID, 'dob', true)); ?></td>
                        <td><?php echo esc_html(get_user_meta($user->ID, 'address', true)); ?></td>
                        <td><?php echo esc_html(get_user_meta($user->ID, 'gender', true)); ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=edit-user&edit_user=' . $user->ID); ?>">Edit</a> |
                            <a href="<?php echo admin_url('admin.php?page=my-register-list-plugin&delete_user=' . $user->ID); ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function my_delete_user()
{
    if (isset($_GET['delete_user'])) {
        $user_id = $_GET['delete_user'];
        if (wp_delete_user($user_id)) {
            echo '<div class="updated"><p>User has been deleted successfully!</p></div>';
        } else {
            echo '<div class="error"><p>Failed to delete user.</p></div>';
        }
    }
}
add_action('admin_init', 'my_delete_user');

