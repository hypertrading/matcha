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
