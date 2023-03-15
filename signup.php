<?php
// Include config file
require_once "conn.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["uname"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["uname"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userid FROM usertb WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["uname"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["uname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate password
    if(empty(trim($_POST["pword"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["pword"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["pword"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["cpword"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["cpword"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        $userinfo = "INSERT INTO userinfo set username = '$username', profilepic='profiles/undraw_pic_profile_re_1865.svg', coverphoto='profiles/undraw_photo_album_re_31c2.svg'";
        $mysqli->query($userinfo);
        
        // Prepare an insert statement
        $sql = "INSERT INTO usertb (username, password) VALUES (?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: home.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Sign up</title>
</head>
<body>
    <!-- sign up -->
    <div class="form">
        <form class="form-child" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Create an account</h2>
            <!-- username -->
            <input name="uname" class="controls" <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" type="text" placeholder="Username">
            <span><?php echo $username_err; ?></span>
            <input name="pword" class="controls" id="pword" type="password" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Password">
            <span><?php echo $password_err; ?></span>
            <input name="cpword" class="controls" id="cpword" type="password" class="form-control  <?php echo (!empty($conpassword_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" placeholder="Confirm  Password">
            <span><?php echo $confirm_password_err; ?></span>
            <div class="button">
                <button type="submit" class="controls" value="submit">Sign up</button>
                <a href="index.php">Already have an account?</a>
            </div>
        </form>
    </div>
</body>
</html>