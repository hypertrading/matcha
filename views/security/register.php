<?php include 'views/header.php';?>

<div class="modal-content">

    <div class="modal-header">
        <h1 class="text-center">Inscription</h1>
    </div>
    <div class="modal-body">
        <form method="post" action="<?= $this->base_url()?>security/new_user">
            <div class="row inscr">

                <div class="col-md-6">
                    <label>Pseudo* :</label>
                    <input name="pseudo" type="text" value="<?= isset($_POST['pseudo'])? $_POST['pseudo'] : ''?>" class="form-control input-lg" placeholder="Pseudo">
                </div>

                <div class="col-md-6">
                    <label>Nom* :</label>
                    <input name="nom" type="text" value="<?= isset($_POST['nom'])? $_POST['nom'] : ''?>" class="form-control input-lg" placeholder="Nom">
                </div>

                <div class="col-md-6">
                    <label>Prénom* :</label>
                    <input name="prenom" type="text" value="<?= isset($_POST['prenom'])? $_POST['prenom'] : ''?>" class="form-control input-lg" placeholder="Prénom">
                </div>

                <div class="col-md-6">
                    <label>Email* :</label>
                    <input name="email" type="email" value="<?= isset($_POST['email'])? $_POST['email'] : ''?>" class="form-control input-lg" placeholder="Adresse Mail">
                </div>

                <div class="col-md-6">
                    <label>Date de naissance* :</label>
                    <input name="date_naissance" value="<?= isset($_POST['date_naissance'])? $_POST['date_naissance'] : ''?>" class="form-control input-lg" type="date" placeholder="Date de naissance">
                </div>

                <div class="col-md-6">
                    <label>Mot de passe* :</label>
                    <input name="password" type="password" class="form-control input-lg" placeholder="Mot de passe">
                </div>

                <div class="col-md-6">
                    <label>Confirmation mot de passe* :</label>
                    <input name="val_password" type="password" class="form-control input-lg" placeholder="Répéter le mot de passe">
                </div>

                <div class="col-md-6 text-center">
                    <label class="radio-inline hidden"><input name="sexe" type="radio" value="0" checked></label>
                    <label class="radio-inline"><input name="sexe" type="radio" value="1" >Homme</label>
                    <label class="radio-inline"><input name="sexe" type="radio" value="2">Femme</label>
                </div>

                <div class="col-md-6">
                    <p>*champs obligatoires</p>
                </div>

                <div class="col-md-12 valider">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Valider</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <p class="text-center text-info">
            Conformément à la loi n° 78-17 du 6 janvier 1978, relative à l'Informatique, aux Fichiers et aux Libertés,
            vous disposez d'un droit d'accès et de rectification des données à caractère personnel vous concernant
            et faisant l’objet de traitements sous la responsabilité du RSI.
        </p>
    </div>
</div>

<?php include 'views/footer.php'; ?>

