<?php


function my_custom_menu()
{
    add_menu_page(
        'Custom User List',
        'User List',
        'manage_options',
        'custom-users-list',
        'custom_users_list_page'
    );
}
add_action('admin_menu', 'my_custom_menu');

function custom_users_list_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_users';
    $users = $wpdb->get_results("SELECT * FROM $table_name");
    if(isset($_REQUEST['submit'])){
        $id = $_REQUEST['id'];
        $user_action = $_REQUEST['action'.$id];
        $wpdb->update(
            $table_name,
            array(
                'Status' => $user_action
            ),
            array(
                'id' => $id
            )
        );
        echo "<script>window.location.href='admin.php?page=custom-users-list'</script>";
    }

?>
    <div class="wrap">
        <h1>Custom User List</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column">ID</th>
                    <th scope="col" class="manage-column">Name</th>
                    <th scope="col" class="manage-column">Email</th>
                    <th scope="col" class="manage-column">Role</th>
                    <th scope="col" class="manage-column">Status</th>
                    <th scope="col" class="manage-column">Select Option</th>
                    <th scope="col" class="manage-column">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo $user->id; ?></td>
                        <td><?php echo $user->name; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td><?php echo $user->role; ?></td>
                        <td><?php echo $user->Status == 0 ? 'Pending' : ($user->Status == 1 ? 'Accepeted' : 'Rejected')?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                                <select name="action<?php echo $user->id; ?>">
                                    <option value="0" <?php echo $user->Status == 0 ? 'selected' : ''; ?>>Pending</option>
                                    <option value="1" <?php echo $user->Status == 1 ? 'selected' : ''; ?>>Accepted</option>
                                    <option value="2" <?php echo $user->Status == 2 ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                <td><button type="submit" name="submit">Submit</button></td>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php
}

?>