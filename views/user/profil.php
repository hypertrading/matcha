<?php
include 'views/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <h2>
            <?= ucfirst($profil['nom']).' '.ucfirst($profil['prenom']) ?>

        <?php if($like == TRUE){ ?>
            <a href="<?=$this->base_url()?>match/unlike?t=<?=$profil['id']?>" class="btn btn-warning" role="button">
                <i class="glyphicon glyphicon-heart">
                </i>
            </a>
        <?php }
        else {?>
            <a href="<?=$this->base_url()?>match/like?t=<?=$profil['id']?>" class="btn btn-primary" role="button">Like</a>
        <?php } ?>
        </h2>
        <p>Dernière connexion : <?=$profil['date_last_login']?></p>
    </div>
    <div class="col-md-3">
        <?php if(!isset($images[0])){ ?>
            <img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-thumbnail">
        <?php }
        else {?>
            <img src="<?= $this->base_url().$images[0]?>" class="img-thumbnail photo_profil">
        <?php }?>
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
            <?= $profil['description']?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Tags</h4>
        <p>
            <?php foreach ($profil['tag'] as $tnom)
                echo "<span class='label label-default'>
                    ".$tnom['nom']."</span>  ";
            ?>
        </p>
    </div>
</div>

<?php
include 'views/footer.php';
?>
