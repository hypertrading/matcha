<?php
include 'header.php';
?>
<h2><small>Messagerie</small></h2>
<div class="row">
    <div class="col-md-2 col-sm-3 col-lg-2">
        <div class="btn-group-vertical" role="group" aria-label="...">
            <?php foreach($connected as $profil){?>
            <a href="<?= $this->base_url()?>messagerie/index?t=<?=$profil['id']?>" type="button" class="btn btn-default"><?= $profil['prenom'].' '.$profil['nom']?></a>
            <?php }?>
        </div>
    </div>
    <?php if(isset($chat)){ ?>
    <div class="col-md-8 col-sm-9 col-lg-6">
        <div id="chat">
            <?php foreach ($chat AS $msg){
                if($msg['from_id'] == $_SESSION['user']['id']){
                    echo '<p class="text-left">'.$msg['message'].'</p>';
                }
                else
                    echo '<p class="text-right">'.$msg['message'].'</p>';
                }
            ?>
        </div>
        <form method="post" action="<?= $this->base_url()?>messagerie/send_msg">
            <input type="text" name="msg" class="form-control" autocomplete="off">
            <button type="submit" name="to" value="<?= $_GET['t']?>" class="btn btn-default">Envoyer</button>
        </form>
    </div>
    <?php } ?>
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
                    para.className = 'text-right';
                    var node = document.createTextNode(message['message']);
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
