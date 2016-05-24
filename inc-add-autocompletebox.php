<?php

$autoCompleteCount = $autoCompleteCount + 1;
$thisVal = "''";

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


if ($doUpdate == TRUE) {
    $updateValue = " , 'value'=>suUnstrip(\$row['" . $_POST['frmField'][$i] . "'])";

    $updateFieldValue = "
        
        
       ";
} else {
    $autoCompleteFrameBuster .= ' && ($_GET["do"] != "autocomplete' . $autoCompleteCount . '") ';
    $updateFieldValue = "";
}
if ($_POST['frmField'][$i] == $uniqueField) {
    $doMaxLength = "\$dbs_" . $table . "['" . $fieldText . "_max']";
} else {
    $doMaxLength = "\$dbs_" . $table . "['" . $fieldText . "_max']";
}
$addCode .="
<div class=\"form-group\">
<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">                
                                <?php
                                
                                " . $updateFieldValue . "
                                //Label
                                \$label = array('class' => \$lblClass);
                                echo suInput('label', \$label, \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_req'] . '" . $_POST['frmLabel'][$i] . ":', TRUE);
                                //Input                    
                                \$arg = array('type' => \$dbs_" . $table . "['" . $fieldText . "_html5_type'] " . $updateValue . ", 'name' => '" . $_POST['frmField'][$i] . "', 'id' => '" . $_POST['frmField'][$i] . "', 'autocomplete' => 'off', 'maxlength' =>  $doMaxLength " . ",\$dbs_" . $table . "['" . $fieldText . "_html5_req'] => \$dbs_" . $table . "['" . $fieldText . "_html5_req'],'class'=>'form-control');
                                //Placeholder
                                if (\$showLabel == FALSE) {
                                    \$placeholder = array('placeholder' => '" . $_POST['frmLabel'][$i] . "');
                                    \$arg = array_merge(\$placeholder, \$arg);
                                }
                                echo suInput('input', \$arg);
                                ?>
</div>
</div>

<script type=\"text/javascript\">
    //Autocomplete code
    jQuery(document).ready(function() {
        $('#" . $_POST['frmField'][$i] . "').autocomplete(
                {source: '<?php echo ADMIN_URL; ?>" . $_POST['frmFormsetvalue'] . "-remote.php?do=autocomplete" . $autoCompleteCount . "', minLength: 2}
        );
    });
</script>
                                                
";
$remoteCodeAutoInsert .= ""
;
$remoteCodeAutoComplete .= "
//if autocomplete
if (isset(\$_GET['do']) && (\$_GET['do'] == 'autocomplete" . $autoCompleteCount . "')) {


    \$col = new MongoCollection(\$db, '" . $table . "');
    \$sort = array('" . $fieldText . "' => 1); 
    \$fields = array('" . $fieldText . "' => 1); 
    \$criteria = array('" . $fieldPrefix1 . "__dbState' => 'Live', '" . $fieldText . "' => new MongoRegex(\"/\" . suUnstrip(\$_REQUEST['term']) . \"/i\"));
    \$numDocs = \$col->count(\$criteria);
    \$row = \$col->find(\$criteria)->sort(\$sort);
        

    \$data = array();
    if (\$numDocs > 0) {
        foreach (\$row as \$doc) {
            \$data[] = array(
                'label' => suUnstrip(\$doc['" . $fieldText . "']),
                'value' => suUnstrip(\$doc['" . $fieldText . "'])
            );
        }
    }

    echo json_encode(\$data);
    flush();
}
";
$remoteValueExistsCheckAdd .="
    //Check if autocomplete value exists in the collection
    \$col = new MongoCollection(\$db, '" . $table . "');
    \$numDocs = 0;
    try {
        \$criteria = array('" . $fieldText . "' => \$_POST['" . $_POST['frmField'][$i] . "'], '" . $fieldPrefix1 . "__dbState' => 'Live');
        \$row = \$col->findOne(\$criteria);
    } catch (MongoException \$e) {
        if (\$e->getCode() > 0) {
            \$vError[] = MONGODB_ERROR;
        }
    }
//Number of documents
    \$numDocs = \$col->count(\$criteria);
    if (\$numDocs < 1) {
        \$vError[] = sprintf(INCORRECT_AUTO_COMPLETE_VALUE,'" . $_POST['frmLabel'][$i] . "');
    }
    ///==
";

$remoteValueExistsCheckUpdate .="
    //Check if autocomplete value exists in the collection
    \$col = new MongoCollection(\$db, '" . $table . "');
    \$numDocs = 0;
    try {
        \$criteria = array('" . $fieldText . "' => \$_POST['" . $_POST['frmField'][$i] . "'], '" . $fieldPrefix1 . "__dbState' => 'Live');
        \$row = \$col->findOne(\$criteria);
    } catch (MongoException \$e) {
        if (\$e->getCode() > 0) {
            \$vError[] = MONGODB_ERROR;
        }
    }
//Number of documents
    \$numDocs = \$col->count(\$criteria);
    if (\$numDocs < 1) {
        \$vError[] = sprintf(INCORRECT_AUTO_COMPLETE_VALUE,'" . $_POST['frmLabel'][$i] . "');
    }
    ///==
";
?>
