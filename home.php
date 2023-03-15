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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>Home</title>
</head>
<body>
    <?php include 'loader.php';?>
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
                echo  "<script>alert('Posting Failed $uploaderr')</script>";
            }
       }
    ?>
    <!-- end  of posting function -->
    <div class="home-body">
        <div class="content-home">
            <!-- post -->
            <form class="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <textarea name="post" id="post" cols="" rows="3" placeholder="What's on your mind?"></textarea>
                <!-- file upload -->
                <div class="fileupload">
                    Select file to upload:
                    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" multiple>
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
                            where posts.privacy='public' or posts.username='$username' ORDER BY postid DESC;";
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
                                            elseif ($filetype == "mp3" || $filetype == "wav") {
                                    ?>
                                                <audio class="music" src="./uploads/<?php echo $row['img']; ?>" controls></audio>
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
    <?php include 'footer.php';?>
    <!-- js script -->
    <script src="index.js"></script>
</body>
</html>