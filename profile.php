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
?>
<!-- html start -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <link rel="stylesheet" href="home.css">
    <title>Profile</title>
</head>
<body>
    <!-- loader -->
    <?php include 'loader.php';?>
    <!-- nav bar -->
    <?php include 'header.php';?>
     <!-- posting function -->
     <?php
       if($_SERVER["REQUEST_METHOD"] == "POST"){
            $postval="";
            $posterr=$uploaderr="";

            $username = ($_SESSION["username"]);
            $postval=($_POST["post"]);
            $privacy =($_POST["privacy"]);
            // time zone
            date_default_timezone_set('Asia/Manila');
            $time = date("h:i:s");
            $date = date("Y-m-d");

            // image
            if(!empty($_FILES["fileToUpload"]));{
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                

                if(empty($uploaderr)){
                    // If upload button is clicked ...
                    $filename = $_FILES["fileToUpload"]["name"];
                    $tempname = $_FILES["fileToUpload"]["tmp_name"];
                    $folder = "./uploads/" . $filename;
                    
                    // Now let's move the uploaded image into the folder: image
                    move_uploaded_file($tempname, $folder);
                }
            }

            if (empty($_POST["post"])) {
                $posterr = "Field is empty";
            }

            if (empty($posterr && $uploaderr)){
                if(empty($uploaderr)){
                    $sql = "INSERT INTO posts (username, post, img, date, time, privacy)
                    VALUES ('$username','$postval','$filename', '$date', '$time', '$privacy')";
                }
                else{
                    $sql = "INSERT INTO posts (username, post, date, time, privacy)
                    VALUES ('$username','$postval', '$date', '$time', '$privacy')";
                }
                if ($mysqli->query($sql) === TRUE) {
                    echo  "<script>alert('Posted')</script>";
                } else {
                    echo "Error: " . $sql . "<br>" . $mysqli->error;
                }
            }
            else{
                echo  "<script>alert('Posting Failed')</script>";
            }
       }
    ?>
    <!-- end  of posting function -->
    <div class="home-body">
        <!-- profile photos -->
        <?php
        $userinfo = "SELECT profilepic, coverphoto FROM userinfo where username='$username'";
        $result = $mysqli->query($userinfo);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
        ?>
                <div class="profile" style="background-image: url(<?php echo $row["coverphoto"]?>); background-repeat: no-repeat; background-size: contain; background-size: cover; background-position: center;">
                    <div class="profile-image">
                        <img class="imageprofile" src="<?php echo $row["profilepic"]?>" alt="profile">
                    </div>
                    <div class="profile-controls">
                        <button onclick="window.location.href='changephoto.php'">Change photo</button>
                    </div>
                </div>
        <?php
            }
        }
        ?>
        <div class="section2">
            <div class="moreinfo-mobile">
                <button onclick="window.location.href=''">User info</button>
                <button onclick="window.location.href=''">Photos</button>
                <button onclick="window.location.href=''">Friends</button>
            </div>
            <div class="more-info">
                <div class="minfo-child">
                    <h2>User info</h2>
                    <?php
                    $userinfo = "SELECT fname, lname, birthday, age, address, cnumber FROM userinfo where username='$username'";
                    $result = $mysqli->query($userinfo);
                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                    ?>
                            <p>Name: <?php echo $row["fname"] . " " . $row["lname"]?></p>
                            <p>Birthday: <?php echo $row["birthday"]?></p>
                            <p>Age: <?php echo $row["age"]?></p>
                            <p>Address: <?php echo $row["address"]?></p>
                            <p>Mobile number: <?php echo $row["cnumber"]?></p>
                    <?php
                        }
                    }
                    ?>
                    <div class="profile-controls">
                        <button onclick="window.location.href='editprofile.php'">Edit Profile</button>
                    </div>
                </div>
                <div class="minfo-child">
                    <h2>Uploads</h2>
                    <div class="minfo-image">
                        <?php
                        $userpost = "SELECT img FROM posts where username='$username'";
                        $result = $mysqli->query($userpost);
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                                if(!empty($row["img"])){
                        ?>                              
                            <?php 
                                if(!empty($row['img'])){
                                    $filetype = strtolower(pathinfo($row['img'],PATHINFO_EXTENSION));

                                    if($filetype == "jpg" || $filetype == "png" || $filetype == "jpeg"
                                        || $filetype == "gif") {
                                    ?>
                                            <img class="img" src="./uploads/<?php echo $row['img'];?>"></img>
                                    <?php 
                                    }
                                    elseif ($filetype == "mp4" || $filetype == "mov" || $filetype == "wmv"
                                            || $filetype == "avi" || $filetype == "avchd" || $filetype == "mkv" || $filetype == "mpeg-2") {
                                    ?>
                                            <video class="img" src="./uploads/<?php echo $row['img']; ?>" controls></video>
                                    <?php
                                        }
                                    }
                                ?>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="profile-controls">
                        <button onclick="window.location.href=''">Show more uploads</button>
                    </div>
                </div>
                <div class="minfo-child">
                <h2>Music</h2>
                    <div class="music">
                        <?php
                        $userpost = "SELECT img FROM posts where username='$username'";
                        $result = $mysqli->query($userpost);
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                                if(!empty($row["img"])){
                                    if(!empty($row['img'])){
                                        $filetype = strtolower(pathinfo($row['img'],PATHINFO_EXTENSION));
                                            if ($filetype == "mp3" || $filetype == "wav" || $filetype == "m4a") {
                                            ?>
                                                <audio class ="music" src="./uploads/<?php echo $row['img']; ?>" controls></audio>
                                            <?php
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                    </div>
                    <div class="profile-controls">
                        <button onclick="window.location.href=''">Show more music</button>
                    </div>
                </div>
                <div class="minfo-child">
                    <h2>Friends</h2>
                </div>
            </div>
            <div class="content">
                <!-- post -->
                <form class="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <textarea name="post" id="post" cols="" rows="3" placeholder="What's on your mind?"></textarea>
                    <!-- file upload -->
                    <div class="fileupload">
                        Select file to upload: 
                        <input type="file" name="fileToUpload" id="fileToUpload" >
                    </div>
                    <div class="post-buttons">
                        <button type="submit" name="save">Post</button>
                        <select name="privacy" id="privacy">
                            <option value="public">Public</option>
                            <option value="friend">Friends</option>
                            <option value="privates">Private</option>
                        </select>
                        <label for="privacy">Privacy</label>
                    </div>
                </form>
                
                <!-- all post -->
                <div class="posts">
                    <?php
                        require_once "conn.php";
                        
                        if ($mysqli->connect_error) {
                        die("Connection failed: " . $mysqli->connect_error);
                        }

                        $sql = "SELECT userinfo.profilepic, posts.username, posts.post, posts.img, posts.privacy, posts.time, 
                                        posts.date FROM posts inner join userinfo on posts.username = userinfo.username 
                                        where posts.username='$username' ORDER BY postid DESC;";
                        $result = $mysqli->query($sql);

                        if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                    ?>
                            <div class="user-post">
                                <div class="post-info">
                                    <strong>
                                        <span class="username"><img class="profilepic" src="<?php echo $row['profilepic']?>" alt=""><?php echo $row['username']?></span>
                                    </strong>
                                    <p><?php echo $row['post'] ?></p>
                                    <?php 
                                        if(!empty($row['img'])){
                                            $filetype = strtolower(pathinfo($row['img'],PATHINFO_EXTENSION));

                                            if($filetype == "jpg" || $filetype == "png" || $filetype == "jpeg"
                                            || $filetype == "gif") {
                                    ?>
                                                <img src="./uploads/<?php echo $row['img'];?>"></img>
                                    <?php 
                                            }
                                            elseif ($filetype == "mp4" || $filetype == "mov" || $filetype == "wmv"
                                            || $filetype == "avi" || $filetype == "avchd" || $filetype == "mkv" || $filetype == "mpeg-2") {
                                    ?>
                                                <video src="./uploads/<?php echo $row['img']; ?>" controls></video>
                                    <?php
                                            }
                                            elseif ($filetype == "mp3" || $filetype == "wav" || $filetype == "m4a") {
                                    ?>
                                                <audio class ="music" src="./uploads/<?php echo $row['img']; ?>" controls></audio>
                                    <?php
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="post-time">
                                    <h5>Posted on:</h5>
                                    <p><?php echo $row['time'] ?></p>
                                    <p><?php echo $row['date'] ?></p>
                                </div>
                            </div>
                    <?php
                        }
                        } else {
                        echo "0 posts";
                        }
                        $mysqli->close();
                    ?>
                    <button class="load-more" id="loadMore">Load more</button>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php';?>
    <script src="index.js"></script>
</body>
</html>