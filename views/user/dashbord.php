<?php include 'views/header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <h3>Dernières visites sur mon profil</h3>
        <ul>
        <?php foreach($visits as $visit){?>
            <li><?= $visit['date']?>
                <a href="<?=$this->base_url()?>user/profil?t=<?= $visit['user_visit']?>">
                    <?= $visit['visitor']['prenom'].' '.$visit['visitor']['nom']?>
                </a>
            </li>
        <?php } ?>
        </ul>
    </div>
    <div class="col-md-6">
        <h3>Ils me likes</h3>
        <ul>
            <?php foreach($likes as $like){?>
                <li><?= $like['date']?>
                    <a href="<?=$this->base_url()?>user/profil?t=<?= $like['id']?>">
                        <?= $like['prenom'].' '.$like['nom']?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="col-md-6">
        <h3>On se like !</h3>
        <ul>
            <?php foreach($connect as $like){?>
                <li><?= $like['date']?>
                    <a href="<?=$this->base_url()?>user/profil?t=<?= $like['id']?>">
                        <?= $like['prenom'].' '.$like['nom']?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<?php include 'views/footer.php'; ?>