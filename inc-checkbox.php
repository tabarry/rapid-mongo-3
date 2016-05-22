<?php

//Build details table checkbox section                    
if ($_POST['frmDetailsSourceText'] != 'Checkbox Text..') {
    $f2 = explode('.', $_POST['frmDetailsSourceText']);
    $f2 = end($f2);

    $f1 = explode('.', $_POST['frmDetailsSourceValue']);
    $f1 = end($f1);

    $t1 = explode('.', $_POST['frmDetailsSourceValue']);
    $t1 = current($t1);

    //id
    $f1a = explode('.', $_POST['frmDetailsDestValue']);
    $f1a = end($f1a);
    $t1a = explode('.', $_POST['frmDetailsDestValue']);
    $t1a = current($t1a);

    //text
    $f2a = explode('.', $_POST['frmDetailsDestText']);
    $f2a = end($f2a);
    $prefix = explode('__', $f2a);
    $prefix = $prefix[0];

    $newPage = explodeExtract($t1, "_", 0);
    $newPage = str_replace('_', '-', $newPage);


    //Add remote
    $frmDetailsDestText = $_POST['frmDetailsDestText'];
    $justFieldName1 = explode(".", $frmDetailsDestText);
    $justFieldName = $justFieldName1[1];
    $justTableName = $justFieldName1[0];
    $fieldPrefix1 = explode('__', $f1);
    $fieldPrefix1 = $fieldPrefix1[0];

    //Add sections        
    $addCheckBox = " 
 <div class=\"clearfix\">&nbsp;</div>    
<hr/>   
<h4><i class=\"fa fa-check-square-o\"></i> Select " . strtoupper(str_replace('-', ' ', explodeExtract($t1, "_", 0))) . "</h4>   

<?php if (\$addAccess == 'true') { ?>
<div>
    <a title=\"Add new record..\" rel=\"prettyPhoto[iframes]\" href=\"<?php echo ADMIN_URL; ?>" . $newPage . "-add.php?overlay=yes&iframe=true&width=100%&height=100%\"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/add-icon.png'/></a>

    <a onclick=\"suReload2('checkboxLinkArea','<?php echo ADMIN_URL; ?>','<?php echo suCrypt('" . $t1 . "'); ?>','<?php echo suCrypt('" . $f1 . "'); ?>','<?php echo suCrypt('" . $f2 . "'); ?>','<?php echo suCrypt('" . $t1a . "'); ?>','<?php echo suCrypt('" . $f1a . "'); ?>','<?php echo suCrypt('" . $f2a . "'); ?>','<?php echo suCrypt(\$id); ?>');\" href=\"javascript:;\"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/reload-icon.png'/></a>    
</div> 
<?php } ?>  
<div id=\"checkboxArea\">

</div>                                
<p class=\"clearfix\">&nbsp;</p>                                
    <div id=\"checkboxLinkArea\">
<?php
//Build checkboxes

\$chkCol = new MongoCollection(\$db, '" . $t1 . "');
\$chkCriteria = array('" . $fieldPrefix1 . "__dbState' => 'Live');
\$chkSort = array('" . $f1 . "' => 1);
\$chkFields = array('" . $f1 . "' => 1);
\$chkRows = \$chkCol->find(\$chkCriteria,\$chkFields)->sort(\$chkSort);

?>
<div class=\"widget tasks-widget col-xs-12 col-sm-12 col-md-6 col-lg-6\" style=\"padding:0px;margin:0px;\">


<?php
foreach (\$chkRows as \$chkDoc) {
\$chkUid = suSlugifyName(\$chkDoc['" . $f1 . "']);
    ?>
<a id=\"chk<?php echo \$chkUid; ?>\" href=\"javascript:;\" class=\"underline\" onclick=\"loadCheckbox('<?php echo \$chkUid; ?>', '<?php echo addslashes(suUnstrip(\$chkDoc['" . $f1 . "'])); ?>', '".$f2a."')\" onmouseover=\"toggleCheckboxClass('over', '<?php echo \$chkUid; ?>');\" onmouseout=\"toggleCheckboxClass('out', '<?php echo \$chkUid; ?>');\"><i id=\"fa<?php echo \$chkUid; ?>\" class=\"fa fa-square-o\"></i> <?php echo suUnstrip(\$chkDoc['" . $f1 . "']); ?>.</a>
        

    <?php
}
?>

</div>
</div>";
//Update code
    $updateCheckBox = "
<hr/>   
<h4><i class=\"fa fa-check-square-o\"></i> Select " . strtoupper(str_replace('-', ' ', explodeExtract($t1, "_", 0))) . "</h4>   
<?php if (\$addAccess == 'true') { ?>    
<div>
    <a title=\"Add new record..\" rel=\"prettyPhoto[iframes]\" href=\"<?php echo ADMIN_URL; ?>" . $newPage . "-add.php?overlay=yes&iframe=true&width=100%&height=100%\"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/add-icon.png'/></a> 
    


    <a onclick=\"suReload2('checkboxLinkArea','<?php echo ADMIN_URL; ?>','<?php echo suCrypt('" . $t1 . "'); ?>','<?php echo suCrypt('" . $f1 . "'); ?>','<?php echo suCrypt('" . $f2 . "'); ?>','<?php echo suCrypt('" . $t1a . "'); ?>','<?php echo suCrypt('" . $f1a . "'); ?>','<?php echo suCrypt('" . $f2a . "'); ?>','<?php echo suCrypt(\$id); ?>');\" href=\"javascript:;\"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/reload-icon.png'/></a>    
</div> 
<?php } ?>  
<div id=\"checkboxArea\">
 <?php
\$chkArr = array();
sort(\$row['" . $f2a . "']);   

//Sort entered data
sort(\$chkArr);


 foreach (\$row['" . $f2a . "'] as \$fieldValue) {
    \$chkUid = suSlugifyName(\$fieldValue);
    array_push(\$chkArr, addslashes(suUnstrip(\$fieldValue)));
    ?>
    <table id=\"chkTbl<?php echo \$chkUid; ?>\" class=\"checkTable\"><tbody><tr><td class=\"checkTd\"><?php echo suUnstrip(\$fieldValue); ?></td><td onclick=\"removeCheckbox('<?php echo \$chkUid; ?>')\" class=\"checkTdCancel\"><a onclick=\"removeCheckbox('<?php echo \$chkUid; ?>')\" href=\"javascript:;\">x</a></td></tr><input type=\"hidden\" name=\"".$f2a."[]\" value=\"<?php echo suUnstrip(\$fieldValue); ?>\"></tbody></table>
<?php } ?>
</div>                                
<p class=\"clearfix\">&nbsp;</p>   
  

<div id=\"checkboxLinkArea\">
<?php
//Get entered data

\$chkArr = \$row['" . $f2a . "'];

//Build checkboxes
\$chkCol = new MongoCollection(\$db, '" . $t1 . "');
\$chkCriteria = array('" . $fieldPrefix1 . "__dbState' => 'Live');
\$chkSort = array('" . $f1 . "' => 1);
\$chkFields = array('" . $f1 . "' => 1);
\$chkRows = \$chkCol->find(\$chkCriteria,\$chkFields)->sort(\$chkSort);
?>
<div class=\"widget tasks-widget col-xs-12 col-sm-12 col-md-6 col-lg-6\" style=\"padding:0px;margin:0px;\">

<?php
foreach (\$chkRows as \$chkDoc) {
\$chkUid = suSlugifyName(\$chkDoc['" . $f1 . "']);
if (in_array(\$chkDoc['" . $f1 . "'], \$chkArr)) {
        \$display = \"style='display:none'\";
    } else {
        \$display = '';
    }    
    ?>
<a <?php echo \$display; ?> id=\"chk<?php echo \$chkUid; ?>\" href=\"javascript:;\" class=\"underline\" onclick=\"loadCheckbox('<?php echo \$chkUid; ?>', '<?php echo addslashes(suUnstrip(\$chkDoc['" . $f1 . "'])); ?>', '".$f2a."')\" onmouseover=\"toggleCheckboxClass('over', '<?php echo \$chkUid; ?>');\" onmouseout=\"toggleCheckboxClass('out', '<?php echo \$chkUid; ?>');\"><i id=\"fa<?php echo \$chkUid; ?>\" class=\"fa fa-square-o\"></i> <?php echo suUnstrip(\$chkDoc['" . $f1 . "']); ?>.</a>
        

    <?php
}
?>

</div>
</div>

";
//Validate remote
    $validateAddRemote = "
//Check if at least one checkbox is selected
if (isset(\$_POST['" . $f2a . "']) && sizeof(\$_POST['" . $f2a . "'])==0) {
    \$vError[]=VALIDATE_EMPTY_CHECKBOX;
}  
";

//Delete remote
    $deleteCheckBoxRemote = "
//Delete from child checkboxes table
\$sql = \"UPDATE " . $t1a . " SET " . $prefix . "__Last_Action_On='\".date('Y-m-d H:i:s').\"', " . $prefix . "__Last_Action_By='\".\$_SESSION[SESSION_PREFIX . 'user__Name'] .\" WHERE " . $f2a . "='\".\$_POST[\"" . $_POST['primary'] . "\"].\"'\";
suQuery(\$sql);
";




    $addCheckBoxRemote = "
//Add details data
        \$chkArray = array();
        for (\$i = 0; \$i <= sizeof(\$_POST['" . $f2a . "'])-1; \$i++) {
            if (isset(\$_POST['" . $f2a . "'][\$i])) {
                array_push(\$chkArray,\$_POST['" . $f2a . "'][\$i]);
            }
        }
        \$data = array(
        '\$set' => array('" . $justFieldName . "' => \$chkArray)
    );
    \$criteria = array('_id' => \$max_id);
    \$col->update(\$criteria, \$data);

    
";
    //update remote
    $updateCheckBoxRemote = "
//update details data
    \$chkArray = array();
    for (\$i = 0; \$i <= sizeof(\$_POST['" . $f2a . "'])-1; \$i++) {
        array_push(\$chkArray,\$_POST['" . $f2a . "'][\$i]);
    }
    \$data = array(
    '\$set' => array('" . $justFieldName . "' => \$chkArray)
    );
    \$criteria = array('_id' => \$max_id);
    \$col->update(\$criteria, \$data);

        
";
}
?>