<html lang="fr">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Matcha</title>
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Seaweed+Script' rel='stylesheet' type='text/css'>
</head>
<body>
<header>
    <div class="head">
        <div class="button-nav float-right">
            <?php if (!isset($_SESSION['user'])){?>
                <a href="login.php"><button class="button">Log-in</button></a>
                <a href="register.php"><button class="button">Register</button></a>
            <?php }else {?>
                <a href="#"><button class="button"><?php echo $_SESSION['user']['login']?> want logout</button></a>
                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1){?>
                    <a href="#"><button class="button">Backoffice Admin</button></a>
                <?php }else {?>
                    <a href="../controllers/user_profil.php"><button class="button"><?php echo $_SESSION['user']['login']?>'s profil</button></a>
                <?php }}?>
        </div>
        <div class="clear"></div>
        <a href="home.php?page=0" class="banniere"><img class="logo" src="../assets/img/logo.png"></a>

    </div>
</header>
<div class="main">