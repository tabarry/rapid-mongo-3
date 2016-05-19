<?php


$thisVal = "''";
if ($doUpdate == TRUE) {
    $thisVal = " suUnstrip(\$row['" . $_POST['frmField'][$i] . "'])";
}
//Get dropdown text
$tableFieldText = explode(".", $_POST['frmForeignkeytext'][$i]);
$table = $tableFieldText[0];
$fieldText = $tableFieldText[1];
//Get dropdown value
$tableFieldId = explode(".", $_POST['frmForeignkeyvalue'][$i]);
$table = $tableFieldId[0];
$fieldId = $tableFieldId[1];
//add page name
$addpage = explode('_', $table);
$addpage = $addpage[1];
//Get felf prefix
$fieldPrefix1 = explode('__', $tableFieldText[1]);
$fieldPrefix1 = $fieldPrefix1[0];


$addCode .="
<div class=\"form-group\">
<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">        
<label><?php echo \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_req']; ?>" . $_POST['frmLabel'][$i] . ":
<?php if (\$addAccess == 'true') { ?>    
<a title=\"Add new record..\" rel=\"prettyPhoto[iframes]\" href=\"<?php echo ADMIN_URL; ?>" . $addpage . "-add.php?overlay=yes&iframe=true&width=80%&height=100%\"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/add-icon.png'/></a>

<a onclick=\"suReload('" . $_POST['frmField'][$i] . "','<?php echo ADMIN_URL; ?>','<?php echo suCrypt('" . $table . "');?>','<?php echo suCrypt('" . $fieldId . "');?>','<?php echo suCrypt('" . $fieldText . "');?>');\" href=\"javascript:;\"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/reload-icon.png'/></a>    
<?php } ?>    
</label>
                                <?php
                                
                                \$ddCol = new MongoCollection(\$db, '".$table."');
                                \$ddCriteria = array('".$fieldPrefix1."__dbState' => 'Live');
                                \$ddSort = array('".$fieldText."' => 1);
                                \$ddFields = array('".$fieldText."' => 1);
                                \$ddRows = \$ddCol->find(\$ddCriteria,\$ddFields)->sort(\$ddSort);
                                \$options = array('^' => 'Select..');
                                foreach (\$ddRows as \$ddDoc) {
                                    \$options[suUnstrip(\$ddDoc['".$fieldId."'])] = suUnstrip(\$ddDoc['".$fieldText."']);
                                }
                                                
                                
                               \$js = \"class='form-control'\";
                                echo suDropdown('" . $_POST['frmField'][$i] . "', \$options, " . $thisVal . ",\$js)
                                ?>
</div>
</div>
";
?>