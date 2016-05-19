<?php if ($_GET['overlay'] != 'yes') { ?>
    <div class="breads">
        <?php if (($pageName == 'Home') || ($pageTitle == 'Dashboard')) { ?>
            <a href="<?php echo ADMIN_URL; ?>"><i class="fa fa-home lblue"></i> Home</a>
        <?php } else { ?>
            <a href="<?php echo ADMIN_URL; ?>"><i class="fa fa-home lblue"></i> Home</a> / <a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $pageName; ?></a>
        <?php } ?>
    </div>
<?php } ?>