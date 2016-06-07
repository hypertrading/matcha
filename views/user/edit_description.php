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