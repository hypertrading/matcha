<?php include 'views/header.php'; ?>

    <div id="loginModal" class="show">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="text-center">Nouveau mot de passe</h2>
                </div>
                <div class="modal-body">
                    <form class="form col-md-12 center-block" method="post" action="<?= $this->base_url()?>security/update_password">
                        <div class="form-group">
                            <input name="password" type="password" class="form-control input-lg" placeholder="Nouveau mot de passe">
                        </div>
                        <div class="form-group">
                            <input name="val_password" type="password" class="form-control input-lg" placeholder="Repeter le mot de passe">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Valider</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

<?php include 'views/footer.php'; ?>