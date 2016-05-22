<?php if (!isset($_GET['overlay']) || ($_GET['overlay'] != 'yes')) { ?>
    <div class="siteFooter pull-right">
        <a target="_blank" href="<?php echo $getSettings['site_footer_link']; ?>"><?php echo $getSettings['site_footer']; ?></a>
    </div>
<?php } ?>