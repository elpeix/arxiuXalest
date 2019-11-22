<!DOCTYPE html>
<html>
<head>
    <title><?=$appName?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/alerts.css">
    <link rel="stylesheet" type="text/css" href="css/form.css">
    <link rel="stylesheet" type="text/css" href="css/up.element.tags.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.16.custom.css">
</head>
<body>
    <div id="header">
        <div id="logo">
            <a href=""><?=$appName?></a>
        </div>
        <?php if($user->level == 1) { ?>
        <div id="menuAdminLauncher">
            <div id="menuAdminBtn"><?=$language['add']?></div>
            <ul id="menuAdmin"></ul>
        </div>
        <?php } ?>
        <div id="logout"><a href="#" title="<?=$language['logout']?>"><?=$language['logout']?></a></div>
        <div id="user">
            <div id="username"><?=$user->firstName?> <?=$user->lastName?></div>
        </div>
        <ul id="menu"></ul>
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