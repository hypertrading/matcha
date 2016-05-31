<?php
if(!isset($_SESSION['user'])) {
    $this->set(array('info' => 'Vous n\'avez pas accés à cet page. Connecter vous.'));
    $this->views('home');
}
include 'views/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>
            <?= ucfirst($pseudo) ?>
            <a href="#" data-toggle="modal" data-target=".edit-profil"><i class="glyphicon glyphicon-edit"></i></a>
        </h2>
    </div>
    <div class="col-md-3">
        <?php if(!isset($images[0])){ ?>
            <img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-thumbnail">
        <?php }
            else {?>
            <img src="<?= $this->base_url().$images[0]?>" class="img-thumbnail photo_profil">
        <?php }?>
        <button data-toggle="modal" data-target=".edit_avatar" class="btn">Ajouter une photo</button>
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
            <?= $description?>
            <a href="#" data-toggle="modal" data-target=".edit-description"><i class="glyphicon glyphicon-edit"></i></a>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Tags</h4>
        <p>
            <?php foreach ($tag as $tnom)
                echo "<span class='label label-default'>
                    ".$tnom['nom']." 
                    <a href='".$this->base_url()."user/remove_tag?t=".$tnom['nom']."'>
                    <i class='glyphicon glyphicon-remove'></i></a></span>  ";
            ?>
        </p>
        <form method="post" action="<?= $this->base_url()?>user/add_tag">
            <label>
                Ajouter un tag
                <input type="text" name="tag">
            </label>
        </form>
    </div>
</div>



<!-- Ajouter une photo-->
<div class="modal fade edit_avatar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Ajouter une photo</h4>
            </div>
            <div class="modal-body">
                <p>Maximum 2Mo, format JPEG obligatoire.</p>
                <form enctype="multipart/form-data" method="post" action="<?= $this->base_url()?>user/add_picture">
                <input type="file" name="picture" class="filestyle" accept="image/jpeg">
            </div>
            <div class="modal-footer">
                <input type="submit" value="Envoyer">
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Editer description -->
    <div class="modal fade edit-description" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Description</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= $this->base_url()?>user/edit_description">
                        <textarea class="form-control" name="description" rows="3"><?= $description?></textarea>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="Envoyer">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Modification des informations personnelles -->
    <div class="modal fade edit-profil" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Modifier mes informations</h4>
                </div>
                <div class="modal-body">
                    <form method="post">
                    <label>Pseudo :</label>
                    <input name="pseudo"  value="<?= $pseudo?>" type="text" class="form-control input-sm" placeholder="Pseudo">
                    <label>Nom :</label>
                    <input name="nom"  value="<?= $_SESSION['user']['nom'] ?>" type="text" class="form-control input-sm" placeholder="Nom">
                    <label>Prénom :</label>
                    <input name="prenom" value="<?= $_SESSION['user']['prenom'] ?>" type="text" class="form-control input-sm" placeholder="Prénom">
                    <label>Email :</label>
                    <input name="email"  value="<?= $_SESSION['user']['email'] ?>" type="email" class="form-control input-sm" placeholder="Adresse Mail">
                    <label>Date de naissance:</label>
                    <input name="date_naissance"  value="<?= $_SESSION['user']['date_naissance'] ?>" class="form-control input-sm" type="date">
                    <div class="radio no_line_height">
                        <label class="radio-inline">
                            <input name="sexe" type="radio" id= "inlineRadio1" value="1" <?php if($_SESSION['user']['sexe'] == 1){echo "checked";} ;?>>
                            Homme
                        </label>
                    </div>
                    <div class="radio">
                        <label class="radio-inline">
                            <input name="sexe" type="radio" id="inlineRadio2" value="2" <?php if($_SESSION['user']['sexe'] == 2){echo "checked";} ;?>>
                            Femme
                        </label>
                    </div>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-sm btn-default <?php if($_SESSION['user']['orientation'] == 0){echo 'active';}?>">
                                <input type="radio" name="orientation" id="orientation1" value="0" autocomplete="off" <?php if($_SESSION['user']['orientation'] == 0){echo 'checked';}?>> Bisexuel
                            </label>
                            <label class="btn btn-sm btn-default <?php if($_SESSION['user']['orientation'] == 1){echo 'active';}?>">
                                <input type="radio" name="orientation" id="orientation2" value="1" autocomplete="off" <?php if($_SESSION['user']['orientation'] == 1){echo 'checked';}?>> Hétérosexuel
                            </label>
                            <label class="btn btn-sm btn-default <?php if($_SESSION['user']['orientation'] == 2){echo 'active';}?>">
                                <input type="radio" name="orientation" id="orientation3" value="2" autocomplete="off" <?php if($_SESSION['user']['orientation'] == 2){echo 'checked';}?>> Homosexuel
                            </label>
                        </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 valider">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Valider</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'views/footer.php'; ?>