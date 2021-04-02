<?php
// ********* THIS IS THE ACCOUNT SETTINGS PAGE *********

require_once '../load.php';
confirm_logged_in();


$id = $_SESSION['user_id'];
$current_user = getSingleUser($id);

if(empty($current_user)){
    $message = 'Failed to get user info';
}

// Double check here that user level editor cannot UPgrade their own user level
// They should only be able to downgrade

if(isset($_POST['submit'])){
    $data = array(
        'fname'=>trim($_POST['fname']),
        'username'=>trim($_POST['username']),
        'password'=>trim($_POST['password']),
        'email'=>trim($_POST['email']),
        'user_level'=>isCurrentUserAdminAbove()?trim($_POST['user_level']):'0',
        'id'=>$id,
    );

    $message = editUser($data);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/main.css">

    <title>Account Settings</title>
</head>
<body>
    <main>
        <?php include "../templates/header.php"; ?>
        <h2 class="admin-content-title">Account Settings</h2>

        <?php echo !empty($message)?$message:'';?>
        <?php if (!empty($current_user)): ?> 

            <form class="account-info-form" action="admin_edituser.php" method="POST">
            <!-- Use POST - do not show sensitive information in URL -->
                <?php while($user_info = $current_user->fetch(PDO::FETCH_ASSOC)):?>
                    <label for="first_name">First Name</label> 
                    <input id="first_name" type="text" name="fname" value="<?php echo $user_info['user_fname']; ?>">
                    

                    <label for="username">Username</label> 
                    <input id="username" type="text" name="username" value="<?php echo $user_info['user_name']; ?>">
                    

                    <label for="password">Password</label> 
                    <input id="password" type="text" name="password" value="<?php echo $user_info['user_pass']; ?>">
                    <!-- change type="text" to type="password" for production to hide password when typed - better UX -->
                    

                    <label for="email">Email</label> 
                    <input id="email" type="email" name="email" value="<?php echo $user_info['user_email']; ?>">
                    

                    <!-- This dropdown should only be available for user level admin -->
                    <?php if(isCurrentUserAdminAbove()):?>
                        <label for="user_level">Membership</label>
                        <select name="user_level" id="user_level"> 

                            <?php $user_level_map = getUserLevelMap();
                                foreach($user_level_map as $val => $label):?>
                                <option value="<?php echo $val; ?>" <?php echo $val===(int)$user_info['user_level']?'selected':'';?> ><?php echo $label;?>
                                </option>
                            <?php endforeach;?>

                        </select>
                        
                    <?php endif;?>

                    <button type="submit" name="submit">Update Account</button>
                    <a class="dashboard-link" href="index.php">Back to Dashboard</a>
                <?php endwhile;?>
                
            </form>
        <?php endif;?>
        <?php include "../templates/footer.php"; ?>

    </main>
    <?php include "../templates/scripts.php"; ?>
</body>
</html>