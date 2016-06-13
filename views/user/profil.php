<?php
if(!isset($_SESSION['user'])) {
    $this->set(array('info' => 'Vous n\'avez pas accés à cet page. Connecter vous.'));
    $this->views('home');
}
include 'views/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <h2>
            <?= ucfirst($profil['prenom']).' '.ucfirst($profil['nom']) ?>
        <?php if($like == TRUE){ ?>
            <a href="<?=$this->base_url()?>match/unlike?t=<?=$profil['id']?>" class="btn btn-warning" role="button">
                <i class="glyphicon glyphicon-heart"></i>
            </a>
        <?php }else {?>
            <a href="<?=$this->base_url().'match/like?t='.$profil['id']?>" class="btn btn-primary" role="button">Like</a>
        <?php } ?>
        <?php if($connected == TRUE){ ?>
            <a href="<?= $this->base_url().'messagerie/index?t='.$profil['id']?>" class="btn btn-primary" role="button">
                <i class="glyphicon glyphicon-envelope"></i>
            </a>
        <?php }?>

            <div class="btn-group">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-option-horizontal"></i></button>
                <ul class="dropdown-menu">
                    <li><a href="<?= $this->base_url().'user/report?t='.$profil['id'].'&v=1'?>">Bloquer cette utilisateur</a></li>
                    <li><a href="<?= $this->base_url().'user/report?t='.$profil['id'].'&v=2'?>">C'est un faux compte</a></li>
                </ul>
            </div>
        </h2>
        <p>Dernière connexion : <?=$profil['date_last_login']?></p>

    </div>
    <div class="col-md-3">
        <?php if(!isset($images[0]))
            echo '<img src="'.$this->base_url().'assets/img/user_photo/defaultprofil.gif" class="img-thumbnail">';
        else
            echo '<img src="'.$this->base_url().$images[0].'" class="img-thumbnail photo_profil">';
        ?>
    </div>
    <div class="col-md-9">
        <div class="col-md-7">
            <?php for($i = 1; isset($images[$i]); $i++){ ?>
                <img src="<?= $this->base_url().$images[$i]?>" class="img-thumbnail other-pics">
            <?php } ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <h4>Description</h4>
        <p>
            <?=$profil['description']?>
        </p>
    </div>
    <div class="col-md-6">
        <h4>Info</h4>
        <p><?=$profil['age']?> ans</p>
        <p>Popularité : <?=$profil['pop']?></p>
        <p>Localisation : <?=$profil['localisation'].' ('.$distance.' kms)'?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Tags</h4>
        <p>
        <?php foreach ($profil['tag'] as $tnom)
            echo '<span class="label label-default">'.$tnom['nom'].'</span>  ';?>
        </p>
    </div>
</div>

<?php include 'views/footer.php'; ?>
