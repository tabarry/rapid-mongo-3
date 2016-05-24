<?php
            

if ($doUpdate == TRUE) {
    $updateValue = " , 'value'=>suUnstrip(\$row['" . $_POST['frmField'][$i] . "'])";
}
$multipart = TRUE;
if ($doUpdate == TRUE) {
$addCode .="
<div class=\"form-group\">
<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">            
                                <?php
                                //Label
                                \$showLabel = TRUE;
                                \$lblClass = suShowLabels(\$showLabel);
                                \$label = array('class' => \$lblClass);
                                echo suInput('label', \$label, \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_req'] . '" . $_POST['frmLabel'][$i] . ":', TRUE);
                                //Input
                                \$arg = array('type' => \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_html5_type'], 'name' => '" . $_POST['frmField'][$i] . "', 'id' => '" . $_POST['frmField'][$i] . "');
                                //Placeholder
                                if (\$showLabel == FALSE) {
                                    \$placeholder = array('placeholder' => '" . $_POST['frmLabel'][$i] . "');
                                    \$arg = array_merge(\$placeholder, \$arg);
                                }    
                                echo suInput('input', \$arg);
                                \$lblClass = suShowLabels();
                                ?>
</div> 
</div> 
                                ";
}else{
$addCode .="
<div class=\"form-group\">
<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">
                                <?php
                                //Label
                                \$showLabel = TRUE;
                                \$lblClass = suShowLabels(\$showLabel);
                                \$label = array('class' => \$lblClass);
                                echo suInput('label', \$label, \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_req'] . '" . $_POST['frmLabel'][$i] . ":', TRUE);
                                //Input
                                \$arg = array('type' => \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_html5_type'], 'name' => '" . $_POST['frmField'][$i] . "', 'id' => '" . $_POST['frmField'][$i] . "',\$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_html5_req'] => \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_html5_req']);
                                //Placeholder
                                if (\$showLabel == FALSE) {
                                    \$placeholder = array('placeholder' => '" . $_POST['frmLabel'][$i] . "');
                                    \$arg = array_merge(\$placeholder, \$arg);
                                }
                                echo suInput('input', \$arg);
                                \$lblClass = suShowLabels();
                                ?>
</div>
</div>
                                ";    
}
if ($doUpdate == TRUE) {
    $addCode .="
     <?php 
    if (!isset(\$row['" . $_POST['frmField'][$i] . "'])) {
        \$row['" . $_POST['frmField'][$i] . "'] = '';
    }
    if ((isset(\$row['" . $_POST['frmField'][$i] . "']) && \$row['" . $_POST['frmField'][$i] . "'] != '') && (file_exists(ADMIN_UPLOAD_PATH . \$row['" . $_POST['frmField'][$i] . "']))) { 
    ?>
    <a href=\"<?php echo BASE_URL.'files/'.\$row['" . $_POST['frmField'][$i] . "'] ;?>\" target=\"_blank\" class=\"underline\"><?php echo VIEW_FILE;?></a>
    <?php } ?>
    ";
}
$addCode .="
<div><?php echo \$getSettings['allowed_file_formats']; ?></div>
<p>&nbsp;</p>
    
";


if ($doUpdate == TRUE) {
    $addCode .="
    <?php    
    \$arg = array('type' => 'hidden', 'name' => 'previous_" . $_POST['frmField'][$i] . "', 'id' => 'previous_" . $_POST['frmField'][$i] . "', 'value' => \$row['" . $_POST['frmField'][$i] . "']);
    echo suInput('input', \$arg);     
    ?>
";
}
$extraSqlx2 = "
    //for file
    if (\$_FILES['" . $_POST['frmField'][$i] . "']['name'] != '') {
        \$uid = uniqid();
        \$uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        \$" . $_POST['frmField'][$i] . " = suSlugify(\$_FILES['" . $_POST['frmField'][$i] . "']['name'], \$uid);
        \$extraSql['" . $_POST['frmField'][$i] . "'] = \$uploadPath . \$" . $_POST['frmField'][$i] . ";

    }else{
            if (isset(\$_POST['" . $_POST['frmField'][$i] . "']) && (\$_POST['" . $_POST['frmField'][$i] . "'] != '')) {
                \$extraSql['" . $_POST['frmField'][$i] . "'] = \$_POST['previous_" . $_POST['frmField'][$i] . "'];
            }
    }    
";
$uploadCheck.="
        // file
        if (\$_FILES['" . $_POST['frmField'][$i] . "']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . \$uploadPath. \$" . $_POST['frmField'][$i] . ");
            @unlink(ADMIN_UPLOAD_PATH . \$_POST['previous_" . $_POST['frmField'][$i] . "']);
            copy(\$_FILES['" . $_POST['frmField'][$i] . "']['tmp_name'], ADMIN_UPLOAD_PATH . \$uploadPath. \$" . $_POST['frmField'][$i] . ");
        }    
";
$resetUploadValidation.="
    \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_req']='';
    
";
?>

