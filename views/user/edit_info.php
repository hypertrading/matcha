<div class="modal fade edit-profil" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Modifier mes informations</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= $this->base_url()?>user/edit_profil">
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