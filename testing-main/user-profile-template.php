<?php
/*
Template Name: User Profile
*/

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div id="user-profile">
            <h1>User Profile</h1>
            <?php
            if (!is_user_logged_in()) {
                echo '<p>You must be logged in to view this page.</p>';
            } else {
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                $user_data = get_userdata($user_id);

                $first_name = $current_user->user_firstname;
                $last_name = $current_user->user_lastname;
                $email = $current_user->user_email;

                if (isset($_POST['update_profile'])) {
                    // Handle form submission and update user data
                    $user_email = sanitize_email($_POST['user_email']);
                    $user_firstname = sanitize_text_field($_POST['user_firstname']);
                    $user_lastname = sanitize_text_field($_POST['user_lastname']);
                    $user_address = sanitize_text_field($_POST['user_address']);
                    $user_mobile = sanitize_text_field($_POST['user_mobile']);
                    $user_password = $_POST['user_password'];

                    // Update user data
                    $user_args = array(
                        'ID' => $user_id,
                        'user_email' => $user_email,
                        'first_name' => $user_firstname,
                        'last_name' => $user_lastname,
                    );

                    wp_update_user($user_args);

                    // Update usermeta data for address and mobile number
                    update_user_meta($user_id, 'user_address', $user_address);
                    update_user_meta($user_id, 'user_mobile', $user_mobile);

                    // Update password (if it's changed)
                    if (!empty($user_password)) {
                        wp_set_password($user_password, $user_id);
                    }

                    echo '<div class="updated"><p>Your profile has been updated.</p></div>';
                }
                $user_id = get_current_user_id();

                if (isset($_POST['submit_profile_picture']) && isset($_FILES['profile_picture'])) {
                        $file = $_FILES['profile_picture'];
                        if ($file['error'] == 0) {
                            require_once(ABSPATH . 'wp-admin/includes/image.php');
                            require_once(ABSPATH . 'wp-admin/includes/file.php');
                            require_once(ABSPATH . 'wp-admin/includes/media.php');

                            $attachment_id = media_handle_upload('profile_picture', $user_id);
                            update_user_meta($user_id, 'profile_picture', $attachment_id);
                        }
                }

                $profile_picture_attachment_id = get_user_meta($user_id, 'profile_picture', true);

                $desired_height = 200;
                $desired_width = 200;

                if ($profile_picture_attachment_id) {
                    $profile_picture_url = wp_get_attachment_url($profile_picture_attachment_id);
                    echo '<img id="img" src="' . esc_url($profile_picture_url) . '" alt="Profile Picture" width="' . $desired_width . '" height="' . $desired_height . '" />';
                }
            }
            ?>
            <form method="post" action="" enctype="multipart/form-data"><br><br>
                <input type="file" name="profile_picture" /><br><br>
                <input type="submit" name="submit_profile_picture" value="Upload Profile Picture" /><br><br>
                <label for="user_username">Username:</label>
                <input type="text" name="user_username" value="<?php echo $user_data->user_login; ?>" readonly><br><br>

                <label for="user_email">Email:</label>
                <input type="email" name="user_email" value="<?php echo $email; ?>" required><br><br>

                <label for="user_firstname">First Name:</label>
                <input type="text" name="user_firstname" value="<?php echo $first_name; ?>" required><br><br>

                <label for="user_lastname">Last Name:</label>
                <input type="text" name="user_lastname" value="<?php echo $last_name; ?>" required><br><br>

                <label for="user_address">Address:</label>
                <input type="text" name="user_address" value="<?php echo esc_attr(get_user_meta($user_id, 'user_address', true)); ?>"><br><br>

                <label for="user_mobile">Mobile Number:</label>
                <input type="tel" name="user_mobile" value="<?php echo esc_attr(get_user_meta($user_id, 'user_mobile', true)); ?>"><br><br>

                <label for="user_password">New Password:</label>
                <input type="password" name="user_password" placeholder="Leave blank to keep the same password"><br><br>

                <input type="submit" name="update_profile" value="Update Profile"><br><br>
            </form>
        </div>
    </main>
</div>

<?php get_footer(); ?>

<style>
  /* Add your CSS styles here */
  #user-profile {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    #user-profile h1 {
        font-size: 32px;
        margin-bottom: 20px;
        color: #ec2d32;
    }

    input[type="file"] {
        margin-bottom: 10px;
    }

    label {
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    input[type="submit"] {
        background-color: #ec2d32;
        color: #fff;
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
    }

    #img {
        max-width: 200px;
        max-height: 200px;
        border: 2px solid #ec2d32;
        border-radius: 50%;
        margin-top: 20px;
    }
</style>
