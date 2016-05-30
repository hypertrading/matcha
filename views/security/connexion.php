<?php include 'views/header.php'; ?>

<div id="loginModal" class="show">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="text-center">Connexion</h1>
            </div>
            <div class="modal-body">
                <form class="form col-md-12 center-block" method="post" action="<?= $this->base_url()?>security/authentification">
                <div class="form-group">
                    <input name="pseudo" type="text" class="form-control input-lg" placeholder="Pseudo">
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control input-lg" placeholder="Mot de passe">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Entrer</button>
                    <span class=""><a href="<?= $this->base_url()?>security/lost_password">Mot de passe oublié ?</a></span>
                    <span class="pull-right"><a href="<?= $this->base_url()?>security/register">S'inscrire</a></span>
                </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>