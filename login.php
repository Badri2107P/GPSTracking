<?php
session_start();
error_reporting(0);
$_SESSION['username'] = "admin";
        header("Location: index.php");
        exit;
require 'DB_Connect.php';

session_start();
error_reporting(0);
if($_SERVER["REQUEST_METHOD"] == "POST")    // checks whether any value is posted
{
    $user=$_POST['username'];
    $pass=$_POST['password'];
$sql="select username,password from users where username='$user' and password='$pass';";
$result = $conn->query($sql);
    if ($result->num_rows > 0) {

    $_SESSION['username'] = $user;
        header("Location: index.php");
        exit;
}else{
        $error = "Invalid credentials!";
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Bus Management System</title>
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>

.mdl-layout {
    align-items: center;
    justify-content: center;
}
.mdl-layout__content {
    padding: 24px;
    flex: none;
}
    </style>
</head>



<body>
<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title">Bus Management System</span>
        </div></header>
    <div class="mdl-layout mdl-js-layout mdl-color--grey-100">
            <div class="mdl-card mdl-shadow--6dp">
                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                    <h2 class="mdl-card__title-text">Login</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <form onsubmit="return check();" method="post">
                        <div class="mdl-textfield mdl-js-textfield">
                            Username
                            <input class="mdl-textfield__input" type="text" id="username" name="username"/>

                        </div>
                        <div class="mdl-textfield mdl-js-textfield">
                            password
                            <input class="mdl-textfield__input" type="password" id="password" name="password"/>
                        </div>
                            <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit" value="submit">Log in</button>
                    </form>
                    <span class="mdl-textfield__error" id="error" style="visibility: visible"><?php echo $error; ?></span>
                    <br>
                    <script>
                        function check(){
                            if(username.value=="" ){
                                document.getElementById('username').style.borderColor = "red";
                                return false;
                            } if(password.value=="" ){
                                document.getElementById('password').style.borderColor = "red";
                                return false;
                            }
                        }
                    </script>
                </div>


            </div>
    </div>
    </div>
</body>