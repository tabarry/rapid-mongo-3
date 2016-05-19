<?php include('includes/include.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php include('inc-head.php'); ?>
    </head>
    <body>
        <div id="wrapper">
            <!--HEADER-->
            <?php include('inc-header.php'); ?>
            <!--CONTENT-->
            <div id="content">
                <h2>Step 2/3: Select Tables </h2>
                <form id="form1" name="form1" method="post" action="step3.php" onsubmit="return validate2();">
                    <input type="hidden" name="db" value="<?php echo $_POST["db"]; ?>"/>
                    <p><a href="javascript:;" onclick="doCheck(1)">Check all</a> <a href="javascript:;" onclick="doCheck(0)">Uncheck all</a> </p>
                    <?php
                    $sql = "SHOW TABLES FROM " . $_POST["db"];
                    $rs = suQuery($sql) or die(suError());
                    $exclude = array(
                        'sulata_settings',
                        'sulata_faqs',
                        'sulata_headers',
                        'sulata_media_categories',
                        'sulata_media_files',
                        'sulata_pages',
                        'sulata_setting_types',
                        'sulata_statuses',
                        'sulata_user_types',
                        'sulata_users',
                        'sulata_testimonials'
                    );
                    while ($row = suFetch($rs)) {
                        if(!in_array($row[0], $exclude)){
                            $chk = 'checked="checked"';
                            $color = '';
                        } else {
                            $chk = '"';
                            $color = 'style="color:#FF0000"';
                        }
                        ?>
                        <?php if (($row[0] != 'sulata_blank')&&($row[0] != 'sulata_setting_types')&&($row[0] != 'sulata_statuses')&&($row[0] != 'sulata_user_types')) { ?>
                            <label <?php echo $color; ?>>
                                <input type="checkbox" <?php echo $chk; ?> name="table[]" value="<?= $row[0]; ?>"/>
                                <?= $row[0]; ?>
                            </label>
                        <?php } ?>
                        <?php
                    }
                    suFree($rs);
                    suClose($cn);
                    ?>
                    <input type="hidden" name="db" id="db" value="<?php echo $_POST['db']; ?>"/>
                    <p>
                        <input type="submit" name="Submit" value="Next &raquo;&raquo;" />
                    </p>
                </form>
            </div>
            <!--FOOTER-->
            <?php include('inc-footer.php'); ?>
        </div>
    </body>
</html>
