<div class="nav">
    <div class="left">
        <h1><?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
    </div>
    <div class="right">
        <button class= "webbutton" onclick="window.location.href='home.php'"><img class="icon" src="assets/home.png" alt="User" srcset=""></button>
        <button class= "webbutton" onclick="window.location.href='profile.php'"><img class="icon" src="assets/user.png" alt="" srcset=""></button>
        <button class= "webbutton" onclick="window.location.href='logout.php'"><img class="icon" src="assets/switch.png" alt="" srcset=""></button>
    </div>
</div>