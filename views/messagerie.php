<?php
include 'header.php';
?>

<h2><small>Messagerie</small></h2>
<div class="row">
    <div class="col-md-4">
        <div class="btn-group-vertical" role="group" aria-label="...">
            <?php foreach($connected as $profil){?>
            <a href="<?= $this->base_url()?>messagerie/index?t=<?=$profil['id']?>" type="button" class="btn btn-default"><?= $profil['prenom'].' '.$profil['nom']?></a>
            <?php }?>
        </div>
    </div>
    <div class="col-md-8">
        <div id="chat">
            <?php foreach ($chat AS $msg){ ?>
                <p><?= $msg['date'].' '.$msg['message']?></p>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function $_GET(param) {
        var vars = {};
        window.location.href.replace(location.hash, '').replace(/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
            function(m, key, value) { // callback
                vars[key] = value !== undefined ? value : '';
            }
        );
        if (param) {
            return vars[param] ? vars[param] : null;
        }
        return vars;
    }
    var pid = $_GET('t');
    if(pid != null){
        setInterval(function(){
            $.post("<?= $this->base_url()?>messagerie/load_new_message?t="+pid, {}, function(data){
                var chat = JSON.parse(data);
                for (const message of chat){
                    var para = document.createElement("p");
                    var node = document.createTextNode(message['date']+' '+message['message']);
                    para.appendChild(node);
                    var element = document.getElementById("chat");
                    element.appendChild(para);
                }
            });
        },2000);
    }
</script>
<?php
include 'footer.php';
?>
