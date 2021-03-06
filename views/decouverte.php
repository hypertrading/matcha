<?php
include 'header.php';
?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-xs-12">
            <div class="form-group-sm form-inline">
                <label>
                    Trier par :
                    <select class="form-control input-sm" id="order">
                        <option value="0" disabled selected>-</option>
                        <option value="1">Age</option>
                        <option value="2">Distance</option>
                        <option value="3">Popularité</option>
                    </select>
                </label>
            </div>
    </div>
    <div id="box_profil">
<?php
foreach ($profils AS $profil){ ?>
    <div class="col-sm-offset-0 col-sm-4 col-md-3 col-xs-offset-2 col-xs-6 profil">
        <div class="thumbnail">
            <a href="<?=$this->base_url()?>user/profil?t=<?=$profil['id']?>">
                <img class="img-responsive tuile" src="<?=$this->base_url().$profil['images']?>" alt="Photo de profil d'un utilisateur">
            </a>
            <div class="caption">
                <a href="<?=$this->base_url()?>user/profil?t=<?=$profil['id']?>" class="no-link">
                    <h3 class="text-center"><?=$profil['prenom'].' '.$profil['nom']?></h3>
                </a>
                <p class="text-center">
                    <span class="age"><?= $profil['age']?></span> ans |
                    <span class="dist"><?= $profil['distance']?></span> kms |
                    <i class="glyphicon glyphicon-fire"></i> <span class="pop"><?= $profil['pop']?></span>
                </p>
                <p class="text-center">
                    <?php if($profil['like'] == TRUE){ ?>
                        <a href="<?=$this->base_url()?>match/unlike?t=<?=$profil['id']?>" class="btn btn-warning" role="button">
                            <i class="glyphicon glyphicon-heart"></i>
                        </a>
                    <?php }
                    else {?>
                    <a href="<?=$this->base_url()?>match/like?t=<?=$profil['id']?>" class="btn btn-primary" role="button">
                        Like
                    </a>
                    <?php } ?>
                    <a href="<?=$this->base_url()?>user/profil?t=<?=$profil['id']?>" class="btn btn-default" role="button">
                        Profil
                    </a>
                </p>
            </div>
        </div>
    </div>
<?php } ?>
    </div>
</div>

<script type="text/javascript">
    var $divs = $("div.profil");
    console.log($divs);
    $("#order").change(function(){
        if($(this).val() == '1'){
            var orderbyage = $divs.sort(function (a, b) {
                a = parseInt($(a).find("span.age").text());
                b = parseInt($(b).find("span.age").text());
                if(a > b) {
                    return 1;
                } else if(a < b) {
                    return -1;
                } else {
                    return 0;
                }
            });
            $("#box_profil").html(orderbyage);
        }
        if($(this).val() == '2'){
            var orderbydistance = $divs.sort(function (a, b) {
                a = parseInt($(a).find("span.dist").text());
                b = parseInt($(b).find("span.dist").text());
                if(a > b) {
                    return 1;
                } else if(a < b) {
                    return -1;
                } else {
                    return 0;
                }
            });
            $("#box_profil").html(orderbydistance);
        }
        if($(this).val() == '3'){
            var orderbypop = $divs.sort(function (a, b) {
                a = parseInt($(a).find("span.pop").text());
                b = parseInt($(b).find("span.pop").text());
                if(a < b) {
                    return 1;
                } else if(a > b) {
                    return -1;
                } else {
                    return 0;
                }
            });
            $("#box_profil").html(orderbypop);
        }
    });
</script>
<?php
include 'footer.php';
?>