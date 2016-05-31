<?php
include 'header.php';
?>
<div class="row">
<?php
foreach ($profils AS $profil){ ?>
    <div class="col-sm-offset-0 col-sm-4 col-md-3 col-xs-offset-2 col-xs-6">
        <div class="thumbnail">
            <img class="img-responsive tuile" src="<?=$this->base_url().$profil['images']?>" alt="Photo de profil d'un utilisateur">
            <div class="caption">
                <h3 class="text-center"><?=$profil['prenom'].' '.$profil['nom']?></h3>
                <p class="text-center">
                    <?php if($profil['like'] == TRUE){ ?>
                        <a href="<?=$this->base_url()?>match/unlike?t=<?=$profil['id']?>" class="btn btn-warning" role="button">
                            <i class="glyphicon glyphicon-heart"></i>
                        </a>
                    <?php }
                    else {?>
                    <a href="<?=$this->base_url()?>match/like?t=<?=$profil['id']?>" class="btn btn-primary" role="button">
                        Like
                    </a>
                    <?php } ?>
                    <a href="<?=$this->base_url()?>user/profil?t=<?=$profil['id']?>" class="btn btn-default" role="button">
                        Profil
                    </a>
                </p>
            </div>
        </div>
    </div>
<?php } ?>
</div>
<?php
include 'footer.php';
?>
