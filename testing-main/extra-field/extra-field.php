<?php
/*
Template Name: Extra Fields
*/
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class a="site-main">
        <div id="user-profile">
            <h1>User Profile</h1>
            <?php
            if (isset($_POST['Add_profile'])) {
                $user_name = $_POST['user_username'];
                $user_email = $_POST['user_email'];
                $user_firstname = $_POST['user_firstname'];
                $user_lastname = $_POST['user_lastname'];
                $user_address = $_POST['user_address'];
                $user_mobile = $_POST['user_mobile'];
                $user_other_details = $_POST['add_field'];
                $user_other_details = implode(",", $user_other_details);

                global $wpdb;
                $table_name = $wpdb->prefix . 'user_profile';

                $wpdb->insert(
                    $table_name,
                    array(
                        'user_username' => $user_name,
                        'user_email' => $user_email,
                        'user_firstname' => $user_firstname,
                        'user_lastname' => $user_lastname,
                        'user_address' => $user_address,
                        'user_mobile' => $user_mobile,
                        'user_other_details' => $user_other_details,
                    )
                );

                echo '<div class="updated"><p>Your profile has been updated.</p></div>';
            } else {
                echo '<div class="updated"><p>Your profile has not been updated.</p></div>';
            }
            ?>
            <form method="post" action="" enctype="multipart/form-data"><br><br>
                <label for="user_username">Username:</label>
                <input type="text" name="user_username" value="" required><br><br>

                <label for="user_email">Email:</label>
                <input type="email" name="user_email" value="" required><br><br>

                <label for="user_firstname">First Name:</label>
                <input type="text" name="user_firstname" value="" required><br><br>

                <label for="user_lastname">Last Name:</label>
                <input type="text" name="user_lastname" value="" required><br><br>

                <label for="user_address">Address:</label>
                <input type="text" name="user_address" value=""><br><br>

                <label for="user_mobile">Mobile Number:</label>
                <input type="tel" name="user_mobile" value=""><br><br>
                <label for="other_details">Add Your Skills </label>
                <fieldset class="inputs-set" id="add-field">
                    <input class="input-field" type="text" name="add_field[]" required />
                </fieldset><br><br>
                <button class="btn-add-input" onclick="addfield()" type="button">
                    +
                </button><br><br>

                <input type="submit" name="Add_profile" value="Add Profile"><br><br>
            </form>
        </div>
    </main><br><br>
    <div>
    <h1>View Profile</h1>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>Mobile Number</th>
            <th>Skills</th>
        </tr>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_profile';
        $results = $wpdb->get_results("SELECT * FROM $table_name");
        foreach ($results as $row) {
            $user_other_details = $row->user_other_details;
            $user_other_details = explode(",", $user_other_details);
            $user_other_details = implode("<br>", $user_other_details);
        ?>
            <tr>
                <td><?php echo $row->user_username; ?></td>
                <td><?php echo $row->user_email; ?></td>
                <td><?php echo $row->user_firstname; ?></td>
                <td><?php echo $row->user_lastname; ?></td>
                <td><?php echo $row->user_address; ?></td>
                <td><?php echo $row->user_mobile; ?></td>
                <td><?php echo $user_other_details; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>
</div>

<script>
    const myForm = document.getElementById("add-field");

    function addfield() {
        // Create a new input field
        const newTextField = document.createElement("input");
        newTextField.type = "text";
        newTextField.name = "add_field[]";
        newTextField.setAttribute("required", "");
        newTextField.classList.add("input-field");

        // Create a button to remove the field
        const removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.textContent = "-";
        removeButton.classList.add("btn-remove-input");

        // Add an event listener to the remove button
        removeButton.addEventListener("click", () => {
            myForm.removeChild(newTextField);
            myForm.removeChild(removeButton);
        });

        // Insert the input field and remove button
        myForm.appendChild(newTextField);
        myForm.appendChild(removeButton);
    }
</script>

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
