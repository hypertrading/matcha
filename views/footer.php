</div>
</div>
<footer class="main-footer">
    <div class="container">
        <p class="pull-right">vklepper - 2016</p>
    </div>
</footer>
</div>
<script src="<?= $this->base_url();?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
    window.onload = function(){
        setInterval(function(){
            $.post("<?= $this->base_url()?>welcome/notification", {}, function(data){
                if (data == 1) {
                    $('#notif').removeClass('hidden');
                    $('#notif_bord').removeClass('hidden');
                }
                else if(data == 2) {
                    $('#notif').removeClass('hidden');
                    $('#notif_msg').removeClass('hidden');
                }
                else {
                    $('#notif').addClass('hidden');
                    $('#notif_msg').addClass('hidden');
                    $('#notif_bord').addClass('hidden');
                }
            });
        },1000);
        setInterval(function(){
            $.post("<?= $this->base_url()?>welcome/ping", {}, function(data){});
        },5000);
    }
</script>
</body>
</html>