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
    $fnameerr=$lnameerr="";
    
    if(isset($_POST["save"])) {
        // initialization

        $fname = ($_POST["fname"]);
        $lname = ($_POST["lname"]);
        $bday = ($_POST["bday"]);
        $cnumber = ($_POST["cnumber"]);
        //AGE
        $today = date("Y-m-d");
        $diff = date_diff(date_create($bday), date_create($today));
        $age = $diff->format('%y');
        // address
        $address=($_POST["address"]);

        // validating firstname
        if (!preg_match("/^[a-zA-Z-' ]*$/",$fname)) {
            $fnameerr = "Invalid First name format";
        }

        // validating lastname
        if (!preg_match("/^[a-zA-Z-' ]*$/",$lname)) {
            $lnameerr = "Invalid Last name format";
        }

        if(empty($fnameerr && $lnameerr)){
            $sql = "UPDATE userinfo set fname='$fname', lname='$lname', birthday='$bday', age='$age', address='$address', cnumber='$cnumber' WHERE username='$username'";

            if ($mysqli -> query($sql) == true){
                echo "<script>alert('Profile Updated')</script>";
            }
            else{
                echo "<script>alert('Failed')</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Edit Profile</title>
</head>
<body>
     <!-- nav bar -->
     <?php include 'header.php';?>
    <form class="home-body" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="profile">
            <h1>Profile Information</h1>
            <div class="profile-info">
                <div class="details">
                <?php
                $userinfo = "SELECT fname, lname, birthday, age, address, cnumber FROM userinfo where username='$username'";
                $result = $mysqli->query($userinfo);
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                ?>
                        First name <input type="text" name="fname" placeholder="<?php echo $row['fname']?>">
                        <span class="error"><?php echo $fnameerr ?></span>
                        Last name<input type="text" name="lname" placeholder="<?php echo $row['lname']?>">
                        <span class="error"><?php echo $lnameerr ?></span>
                        Birthday: <input type="date" name="bday" value="<?php echo $row["birthday"]?>">
                        Address <input type="text" placeholder="<?php echo $row["address"]?>" name="address">
                        Mobile number <input type="text" name="cnumber" placeholder="<?php echo $row['cnumber']?>">
                <?php
                    }
                }
                ?>
                </div>
            </div>
            <div class="profile-controls">
                <button name="save">Save Profile</button>
            </div>
        </div>
    </form>
    <!-- footer -->
    <?php include 'footer.php';?>
</body>
</html>