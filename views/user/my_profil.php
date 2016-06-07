<?php
if(!isset($_SESSION['user'])) {
    $this->set(array('info' => 'Vous n\'avez pas accés à cet page. Connecter vous.'));
    $this->views('home');
}
include 'views/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h2>
            <?= ucfirst($pseudo) ?>
            <small><a href="#" data-toggle="modal" data-target=".edit-profil"><i class="glyphicon glyphicon-edit"></i></a></small>
        </h2>
        <p>
            Localisation : <?=$localisation?>
            <small><a href="#" data-toggle="modal" data-target=".edit-position"><i class="glyphicon glyphicon-edit"></i></a></small>
        </p>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-8">
        <?php if(!isset($images[0])){ ?>
            <img src="<?= $this->base_url()?>assets/img/user_photo/defaultprofil.gif" class="img-thumbnail">
        <?php } else {?>
            <div class="img-thumbnail img-block">
                <img src="<?= $this->base_url().$images[0]['path']?>" class="photo_profil">
                <a href="<?= $this->base_url().'user/rm_picture?t='.$images[0]['id']?>" class="img-rm"><i class='glyphicon glyphicon-trash'></i></a>
            </div>
        <?php }?>
        <button data-toggle="modal" data-target=".edit_avatar" class="btn">Ajouter une photo</button>
    </div>
    <div class="col-md-9 col-sm-9 col-xs-8">
        <?php for($i = 1; isset($images[$i]); $i++){?>
            <div class="img-thumbnail img-block">
                <img src="<?= $this->base_url().$images[$i]['path']?>" class=" other-pics">
                <a href="<?= $this->base_url().'user/rm_picture?t='.$images[$i]['id']?>" class="img-rm"><i class='glyphicon glyphicon-trash'></i></a>
                <a href="<?= $this->base_url().'user/set_avatar?t='.$images[$i]['id']?>" class="img-set-profil"><i class='glyphicon glyphicon-star'></i></a>
            </div>
        <?php } ?>
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
            <button type="submit" class="btn btn-default">Ajouter</button>
        </form>
    </div>
</div>

<?php include 'views/user/edit_position.php'?>
<?php include 'views/user/edit_photo.php'?>
<?php include 'views/user/edit_description.php'?>
<?php include 'views/user/edit_info.php'?>

<script>
    var placeSearch, autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        $("#place_id").val(place.place_id);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvYkrx4RvWzS38k7SlCSpiYTbcvrcLk5k&signed_in=true&libraries=places&callback=initAutocomplete" async defer>
</script>

<?php include 'views/footer.php'; ?>