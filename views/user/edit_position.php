<div class="modal fade edit-position" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Description</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= $this->base_url()?>user/edit_position">
                    <div id="locationField">
                        <input class="form-control input-lg" id="autocomplete" placeholder="Enter your address" type="text">
                    </div>
                    <input id="place_id" name="place_id" value="FALSE" hidden>
            </div>
            <div class="modal-footer">
                <input type="submit" value="Envoyer">
                </form>
            </div>
        </div>
    </div>
</div>