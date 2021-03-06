<?php include 'views/header.php'; ?>

<div id="loginModal" class="show">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="text-center">Mot de passe oublié</h1>
            </div>
            <div class="modal-body">
                <form class="form col-md-12 center-block" method="post" action="<?= $this->base_url()?>security/send_password">
                    <div class="form-group">
                        <input name="email" type="email" class="form-control input-lg" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input name="nom" type="text" class="form-control input-lg" placeholder="Votre nom">
                    </div>
                    <div class="form-group">
                        <input name="prenom" type="text" class="form-control input-lg" placeholder="Votre Prénom">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Envoyer le mot de passe</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p class="text-center text-info">Vous recevrez un email contenant votre mot de passe.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>