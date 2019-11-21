<!DOCTYPE html>
<html>
<head>
    <title><?=$appName?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <div id="main">
        <h1 class="logo"><?=$appName?></h1>
        
        <div id="cont">
            <div id="errorMessage" class="login-alert">&nbsp;</div>
            <form method="POST" action="/login" class="form-signin" id="login_form">
                <div><input type="text" name="username" placeholder="<?=$language['username']?>" required autofocus /></div>
                <div><input type="password" name="password" value="" placeholder="<?=$language['password']?>" required /></div>
                <div><input type="submit" name="login" value="<?=$language['login_submit']?>" /></div>
            </form>
        </div>
    </div>
    <div id="circle"><img src="css/images/logo.png" /></div>

    <script src="js/libs/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="js/login.js" type="text/javascript"></script>
</body>
</html>