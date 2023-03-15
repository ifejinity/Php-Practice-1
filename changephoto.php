<?php
    require_once "conn.php";
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: index.php");
        exit;
    }
    $username=$_SESSION["username"];
    error_reporting(0);
    $profileerr=$covererr="";
    $profilesuc=$coversuc="";

    if(isset($_POST["save"])) {
        // profile start
        if ($_FILES["profilepic"]["size"] <= 0) {
          $profileerr= "No image selected or the selected image is unable to upload";
        }
        else{
            $target_dir = "profiles/";
            $target_file = $target_dir . basename($_FILES["profilepic"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $profileerr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $profileerr = $profileerr;
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE userinfo SET profilepic='$target_file' WHERE username='$username'";
                    if ($mysqli->query($sql) === TRUE) {
                        $postval=$username . " " . "changed his profile photo.";
                        date_default_timezone_set('Asia/Manila');
                        $time = date("h:i:s");
                        $date = date("Y-m-d");

                        $updateprofile = "INSERT INTO posts (username, post, date, time, privacy)
                        VALUES ('$username','$postval', '$date', '$time', 'public')";
                        if ($mysqli->query($updateprofile) === true){
                            $profilesuc = "The file has been uploaded";
                        }
                    }
                    else {
                        $profileerr = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $profileerr = "Sorry, there was an error uploading your file.";
                }
            }
        }
        // profile end

        // cover start
        if ($_FILES["coverpic"]["size"] <= 0) {
            $covererr= "No image selected or the selected image is unable to upload";
        }
        else{
            $target_dir2 = "profiles/";
            $target_file2 = $target_dir2 . basename($_FILES["coverpic"]["name"]);
            $uploadOk2 = 1;
            $imageFileType2 = strtolower(pathinfo($target_file2,PATHINFO_EXTENSION));

            // Allow certain file formats
            if($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg"
            && $imageFileType2 != "gif" ) {
                $covererr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk2 = 0;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk2 == 0) {
                $covererr = $covererr;
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["coverpic"]["tmp_name"], $target_file2)) {
                    $sql2 = "UPDATE userinfo SET coverphoto='$target_file2' WHERE username='$username'";
                    if ($mysqli->query($sql2) === TRUE) {
                        date_default_timezone_set('Asia/Manila');
                        $time = date("h:i:s");
                        $date = date("Y-m-d");
                        $postval=$username . " " . "changed his cover photo.";
                        $updatecover = "INSERT INTO posts (username, post, date, time, privacy)
                        VALUES ('$username','$postval', '$date', '$time', 'public')";
                        if ($mysqli->query($updatecover) === true){
                            $coversuc = "The file has been uploaded";
                        }
                    } else {
                        $covererr = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $covererr = "Sorry, there was an error uploading your file.";
                }
            }
        }
        // cover end
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Change photo</title>
</head>
<body>
     <!-- nav bar -->
    <?php include 'header.php';?>
    <form class="home-body" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="profile">
            <h1>Change photo</h1>
            <div class="profile-info">
                <div class="details">
                    <h3>Profile picture</h3>
                    <input type="file" name="profilepic" accept="image/*">
                    <span><p class="error"><?php echo $profileerr?></p><p class="success"><?php echo $profilesuc?></p></span>
                    <h3>Cover picture</h3>
                    <input type="file" name="coverpic" accept="image/*">
                    <span><p class="error"><?php echo $covererr?></p><p class="success"><?php echo $coversuc?></span>
                </div>
            </div>
            <div class="profile-controls">
                <button name="save">Save photo</button>
            </div>
        </div>
    </form>
    <?php include 'footer.php';?>
</body>
</html>