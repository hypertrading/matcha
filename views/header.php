<!DOCTYPE HTML>
<html lang="fr">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Matcha</title>
    <link href="<?= $this->base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= $this->base_url();?>assets/css/style.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $this->base_url();?>assets/img/favicon.png">
    <link href='https://fonts.googleapis.com/css?family=Seaweed+Script' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="page">
    <div class="bloc-principal">
        <div class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a  href="<?= $this->base_url();?>"><img src="<?= $this->base_url();?>assets/img/logo.png" alt="Icone du menu" id="logo"></a>
                </div>
                <div class="collapse navbar-collapse" id="menu">
                    <ul class="nav navbar-nav">

                        <?php if(!isset($_SESSION['user']['id'])){ ?>
                            <li>
                                <a href="<?= $this->base_url();?>Security/connexion">Connexion</a>
                            </li>
                        <?php }
                        else { ?>
                            <li>
                                <a href="<?= $this->base_url();?>match/decouverte">Decouverte</a>
                            </li>
                            <li>
                                <a href="<?= $this->base_url();?>match/search_page">Recherche</a>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="glyphicon glyphicon-user"></i>
                                    <?php echo $_SESSION['user']['prenom']." ".$_SESSION['user']['nom']; ?>
                                    <b class="caret"></b>
                                    <i id="notif" class="glyphicon glyphicon-flag red <?php echo $_SESSION['notif'] == TRUE ? '' : 'hidden'; ?>"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= $this->base_url();?>user/my_profil">- Mon profil</a></li>
                                    <li><a href="<?= $this->base_url();?>messagerie/index">- Messagerie<i id="notif_msg" class="glyphicon glyphicon-flag red hidden"></i></a></li>
                                    <li><a href="<?= $this->base_url();?>user/dashbord">- Tableau de bord <i id="notif_bord" class="glyphicon glyphicon-flag red hidden"></i></a></li>
                                    <li><a href="<?= $this->base_url();?>security/logout">- Me déconnecter</a></li>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
        <?php if(isset($info)){ ?>
            <div class="alert" role="alert">
                <p><?= $info ?></p>
            </div>
        <?php } ?>
