<!DOCTYPE html>
<html>
<head>
    <title><?=$appName?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="css/mf.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div id="header" class="header">
        <div id="logo"><a href=""><?=$appName?></a></div>
        <?php if($user->level == 1) { ?>
        <div id="menuAdminLauncher">
            <div id="menuAdminBtn"><?=$language['add']?></div>
            <ul id="menuAdmin"></ul>
        </div>
        <?php } ?>
        <input class="menu-btn" type="checkbox" id="menu-btn" />
        <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
        <ul id="menu" class="menu">
            
            <li id="logout" class="menu-static logout"><a href="#" title="<?=$language['logout']?>"><?=$language['logout']?></a></li>
            <li id="user" class="menu-static user"><a href="#user" id="username"><?=$user->firstName?> <?=$user->lastName?></a></li>
        </ul>
    </div>

    <div id="container">
        <div id="load" class="show"><?=$language['loading']?></div>
        <div id="containerHeader"></div>
        <div id="content"></div>
    </div>

    <script type="text/javascript" src="js/libs/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/libs/routie.min.js"></script>

    <script type="text/javascript" src="js/app.vars.js"></script>

    <script type="text/javascript" src="js/libs/mf/mf.js"></script>
    <script type="text/javascript" src="js/libs/mf/mf.alerts.js"></script>
    <script type="text/javascript" src="js/libs/mf/mf.connection.js"></script>
    <script type="text/javascript" src="js/libs/mf/mf.form.js"></script>
    <script type="text/javascript" src="js/libs/mf/mf.page.js"></script>
    <script type="text/javascript" src="js/libs/mf/mf.viewlist.js"></script>
    <script type="text/javascript" src="js/libs/mf/mf.controller.js"></script>

    <!-- Helpers -->
    <script type="text/javascript" src="js/helpers/app.resources.js"></script>
    <script type="text/javascript" src="js/helpers/app.menu.js"></script>
    <script type="text/javascript" src="js/helpers/app.filter.js"></script>
    <script type="text/javascript" src="js/helpers/app.autocomplete.js"></script>
    <!-- Models -->
    <script type="text/javascript" src="js/app.model.js"></script>
    <script type="text/javascript" src="js/app.collections.js"></script>
    <!-- Views -->
    <script type="text/javascript" src="js/views/list.scores.js"></script>
    <script type="text/javascript" src="js/views/list.all.js"></script>
    <script type="text/javascript" src="js/views/score.js"></script>
    <script type="text/javascript" src="js/views/search.js"></script>

    <?php if($user->level == 1) { ?>
        <script type="text/javascript" src="js/app.admin.js"></script>
    <?php } ?>
    
    <!-- INIT -->
    <script type="text/javascript" src="js/app.init.js"></script>
    <script type="text/javascript" src="js/app.controllers.js"></script>

</body>
</html>