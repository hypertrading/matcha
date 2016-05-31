<?php
include 'header.php';
?>
<div class="row">
<?php
foreach ($profils AS $profil){ ?>
    <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
            <img class="img-responsive tuile" src="<?=$this->base_url()?>assets/img/user_photo/<?= $profil['id']?>.jpg" alt="Photo de profil d'un utilisateur">
            <div class="caption">
                <h3><?= $profil['prenom'].' '.$profil['nom']?></h3>
                <p>
                    <a href="#" class="btn btn-primary" role="button">Like</a>
                    <a href="<?=$this->base_url()?>user/profil?t=<?= $profil['id']?>" class="btn btn-default" role="button">Profil</a>
                </p>
            </div>
        </div>
    </div>
<?php } ?>
</div>
<?php
include 'footer.php';
?>
