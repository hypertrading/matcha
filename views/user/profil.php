<?php
include 'views/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <h2>
            <?= ucfirst($profil['nom']) ?>
        </h2>
    </div>
    <div class="col-md-3">
        <?php if(!isset($images[0])){ ?>
            <img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-thumbnail">
        <?php }
        else {?>
            <img src="<?= $this->base_url().$images[0]?>" class="img-thumbnail photo_profil">
        <?php }?>
    </div>
    <div class="col-md-7">
        <img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-thumbnail other-pics">
        <img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-thumbnail other-pics">
        <img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-thumbnail other-pics">
    </div>
    <div class="col-md-2">
        <h4>Ces profils vous correspondent</h4>
        <div class="row">
            <div class="col-md-12">
                <a href="#" ><img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-circle mini-circle"></a>
                <a href="#" ><img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-circle mini-circle"></a>
            </div>
            <div class="col-md-12">
                <a href="#" ><img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-circle mini-circle"></a>
                <a href="#" ><img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-circle mini-circle"></a>
            </div>
            <div class="col-md-12">
                <a href="#" ><img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-circle mini-circle"></a>
                <a href="#" ><img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-circle mini-circle"></a>
            </div>

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
                    ".$tnom['nom']."
                    <a href='".$this->base_url()."user/remove_tag?t=".$tnom['nom']."'>
                    <i class='glyphicon glyphicon-remove'></i></a></span>  ";
            ?>
        </p>
    </div>
</div>



<?php
include 'views/footer.php';
?>
