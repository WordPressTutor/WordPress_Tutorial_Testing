
//update 
jQuery(document).ready(function(){

    loadUserData();
   
    jQuery('#add').click(function(e){
        e.preventDefault();
        var userData = {
            name: jQuery('#nameid').val(),
            age: jQuery('#ageid').val(),
            gender: jQuery('#genderid').val(),
            email: jQuery('#emailid').val(),
            phone: jQuery('#phoneid').val(),
            address: jQuery('#addressid').val(),
            grade: jQuery('#gradeid').val(),
            performance: jQuery('#performanceid').val()
            

        };
        
    
        jQuery.ajax({
            url: custom_script_params.ajax_url,
            type: 'post',
            data: {
                action: 'add_user_ajax',
                user_data: userData,
            },
            success: function(response){
                if(response.success){
                    alert('User added successfully');
                    loadUserData();
                    jQuery('#myform')[0].reset();
                }else{
                    alert('Error adding user: ' + response.error);
                }
            },
        });
    });
//edit user
    jQuery(document).on('click', '.edit-user', function(){
        var userId = jQuery(this).data('user-id');
        var userRow = jQuery(this).closest('tr');
        var name = userRow.find('td:eq(0)').text();
        var age = userRow.find('td:eq(1)').text();
        var gender = userRow.find('td:eq(2)').text();
        var email = userRow.find('td:eq(3)').text();
        var phone = userRow.find('td:eq(4)').text();
        var address = userRow.find('td:eq(5)').text();
        var grade = userRow.find('td:eq(6)').text();
        var performance = userRow.find('td:eq(7)').text();


        jQuery('#user-id').val(userId);
        jQuery('#nameid').val(name);
        jQuery('#ageid').val(age);
        jQuery('#genderid').val(gender);
        jQuery('#emailid').val(email);
        jQuery('#phoneid').val(phone);
        jQuery('#addressid').val(address);
        jQuery('#gradeid').val(grade);
        jQuery('#performanceid').val(performance);


        jQuery('#add').hide();
        jQuery('#update').show();
    });

    jQuery('#update').click(function(e){
        e.preventDefault();
        var userData= {
            user_id: jQuery('#user-id').val(),
            name: jQuery('#nameid').val(),
            age: jQuery('#ageid').val(),
            gender: jQuery('#genderid').val(),
            email: jQuery('#emailid').val(),
            phone: jQuery('#phoneid').val(),
            address: jQuery('#addressid').val(),
            grade: jQuery('#gradeid').val(),
            performance: jQuery('#performanceid').val()

        };


        jQuery.ajax({
            url: custom_script_params.ajax_url,
            type: 'post',
            data: {
                action: 'update_user_ajax',
                user_data: userData,
            },
            success: function(response){
                if(response.success){
                    alert('User updated successfully');
                    loadUserData();
                    jQuery('#myform')[0].reset();
                    jQuery('#add').show();
                    jQuery('#update').hide();
                }else{
                    alert('Error updating user: ' + response.error);
                }
            },
        });
    });

    //delete user

    jQuery(document).on('click', '.delete-user', function(){
        var confirmDelete = confirm('Are you sure you want to delete this user?');
        if(confirmDelete){
            var userId = jQuery(this).data('user-id');
            console.log(userId);
            jQuery.ajax({
                url: custom_script_params.ajax_url,
                type: 'post',
                data: {
                    action: 'delete_user_ajax',
                    user_id: userId,
                },
                success: function(response){
                    if(response.success){
                        alert('User deleted successfully');
                        loadUserData();
                    }else{
                        alert('Error deleting user: ' + response.error);
                    }
                },
            });
        }
    });
//loadUserData();

    function loadUserData(){
        jQuery.ajax({
            url: custom_script_params.ajax_url,
            type: 'post',
            data: {
                action: 'get_user_data_ajax',
            },
            success: function(response){
                var userData = response;
                jQuery('#user-table tbody').empty();
                
                for(var i = 0; i < userData.length; i++){
                    var row = '<tr>' +
                    '<td>' + userData[i].name + '</td>' +
                    '<td>' + userData[i].age + '</td>' +
                    '<td>' + userData[i].gender + '</td>' +
                    '<td>' + userData[i].email + '</td>' +
                    '<td>' + userData[i].phone + '</td>' +
                    '<td>' + userData[i].address + '</td>' +
                    '<td>' + userData[i].grade + '</td>' +
                    '<td>' + userData[i].performance + '</td>' +
                    '<td>' +
                    '<button class="edit-user" data-user-id="' + userData[i].id + '">Edit</button><br><br>' +
                    '<button class="delete-user" data-user-id="' + userData[i].id + '">Delete</button>' +
                    '</td>' +
                    '</tr>';
                    
                    jQuery('#user-table tbody').append(row);
                    
                }
            },
            
        });
    }
});


