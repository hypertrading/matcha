<?php include 'header.php'; ?>

<div class="row">
    <div class="col-sm-12 col-md-12 col-xs-12">
        <form id="search_form">
        <div class="form-group-sm form-inline">
            <label>
                Age entre :
                <input class="input-sm" name="age_min" type="number" min="16" max="99" value="16">
                et
                <input class="input-sm" name="age_max" type="number" min="16" max="99" value="99">
            </label>
        </div>
        <div class="form-group-sm form-inline">
            <label>
                Distance entre :
                <input class="input-sm" name="dist_min" type="number" min="0" max="1000" value="0">
                et
                <input class="input-sm" name="dist_max" type="number" min="1" max="1000" value="1000">
            </label>
        </div>
        <div class="form-group-sm form-inline">
            <label>
                Popularité entre :
                <input class="input-sm" name="pop_min" type="number" min="0" max="1000" value="0">
                et
                <input class="input-sm" name="pop_max" type="number" min="1" max="1000" value="1000">
            </label>
        </div>
        <div class="form-group-sm form-inline">
            <label>
                Tag(s) :
                <input class="input-sm" name="tag" type="text" placeholder="ex: poulet, vin">
            </label>
        </div>
        <div class="form-group-sm form-inline">
            <button class="btn btn-default" id="btn_search" type="submit"> Rechercher</button>
        </div>
        </form>
    </div>
    <div class="form-group-sm form-inline">
        <label>
            Trier par :
            <select class="form-control input-sm" id="order">
                <option value="0">-</option>
                <option value="1">Age</option>
                <option value="2">Distance</option>
                <option value="3">Popularité</option>
            </select>
        </label>
    </div>
</div>
    <div id="result_search">
    </div>


<script type="text/javascript">
    $('#search_form').on("submit", function(e){
        e.preventDefault();
        $('#result_search').html('');
        $.post("<?= $this->base_url()?>match/search", $("#search_form").serialize(), function (data){
                var profils = JSON.parse(data);
                var nbr = profils.length;
                if(nbr == 0){
                    var no_result = document.createElement("p3");
                    no_result.className = 'text-center';
                    var msg = document.createTextNode('Aucun resultat');
                    no_result.appendChild(msg);
                    var place = document.getElementById("result_search");
                    place.appendChild(no_result);
                    return;
                }
                else if(profils == false){
                    var no_result = document.createElement("p3");
                    no_result.className = 'text-center';
                    var msg = document.createTextNode('Erreur de saisie');
                    no_result.appendChild(msg);
                    var place = document.getElementById("result_search");
                    place.appendChild(no_result);
                    return;
                }
                for (const profil of profils){
                    var div = document.createElement('div');
                    div.className = 'col-sm-offset-0 col-sm-4 col-md-3 col-xs-offset-2 col-xs-6 profil';
                    var thumbnail = document.createElement('div');
                    thumbnail.className = 'thumbnail';
                    var caption = document.createElement('div');
                    caption.className = 'caption';

                    var a_profil = document.createElement('a');
                    a_profil.className = 'caption';
                    var img = document.createElement('img');
                    img.className = 'img-responsive tuile';
                    img.setAttribute('src', '<?= $this->base_url()?>'+profil['images']);
                    a_profil.appendChild(img);
                    a_profil.setAttribute('href', '<?=$this->base_url()?>user/profil?t='+profil['id']);

                    var p_prenom = document.createElement("h3");
                    p_prenom.className = 'text-center';
                    var prenom = document.createTextNode(profil['prenom']+' '+profil['nom']);
                    p_prenom.appendChild(prenom);

                    var info = document.createElement('p');
                    info.className = 'text-center';

                    var span_age = document.createElement('span');
                    span_age.className = 'age';
                    var age =  document.createTextNode(profil['age']);
                    span_age.appendChild(age);
                    var span_dist = document.createElement('span');
                    span_dist.className = 'dist';
                    var dist =  document.createTextNode(profil['distance']+' kms | ');
                    span_dist.appendChild(dist);
                    var span_pop = document.createElement('span');
                    span_pop.className = 'pop';
                    var pop =  document.createTextNode(profil['pop']);
                    span_pop.appendChild(pop);

                    info.appendChild(span_age);
                    info.appendChild(span_dist);
                    info.appendChild(span_pop);

                    thumbnail.appendChild(a_profil);
                    thumbnail.appendChild(p_prenom);
                    thumbnail.appendChild(info);
                    div.appendChild(thumbnail);
                    var place = document.getElementById("result_search");
                    place.appendChild(div);
                }
        });
    });
</script>

<script type="text/javascript">

    $("#order").change(function(){
        var $divs = $("div.profil");
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
            $("#result_search").html(orderbyage);
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
            $("#result_search").html(orderbydistance);
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
            $("#result_search").html(orderbypop);
        }
    });
</script>
<?php include 'footer.php'; ?>
