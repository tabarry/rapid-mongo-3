<?php
$checkIfPost='';
//Build validation type array
for ($i = 1; $i <= sizeof($_POST['frmType']) - 1; $i++) {
    if ($_POST['frmType'][$i] == 'Textbox') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'required', ";
    }
    if ($_POST['frmType'][$i] == 'Email') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'email', ";
    }
    if ($_POST['frmType'][$i] == 'Password') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'password', ";
    }
    if ($_POST['frmType'][$i] == 'Textarea') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'required', ";
    }
    if ($_POST['frmType'][$i] == 'HTML Area') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'required', ";
    }
    if ($_POST['frmType'][$i] == 'Integer') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'int', ";
    }

    if ($_POST['frmType'][$i] == 'Double') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'double', ";
    }

    if ($_POST['frmType'][$i] == 'Float') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'float', ";
    }
    if ($_POST['frmType'][$i] == 'Date') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'required', ";
    }
    if ($_POST['frmType'][$i] == 'Enum') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'enum', ";
    }
    if ($_POST['frmType'][$i] == 'Dropdown from DB') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'required', ";
    }
    if ($_POST['frmType'][$i] == 'File field') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'file', ";
    }
    if ($_POST['frmType'][$i] == 'Picture field') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'image', ";
    }
    if ($_POST['frmType'][$i] == 'Attachment field') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'attachment', ";
    }
    if ($_POST['frmType'][$i] == 'URL') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'url', ";
    }
    if ($_POST['frmType'][$i] == 'IP') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'ip', ";
    }
    if ($_POST['frmType'][$i] == 'Credit Card') {
        $validateAs .= " '" . $_POST['frmField'][$i] . "_validateas'" . "=>'cc', ";
    }
}
$validateAs = "\$validateAsArray=array(" . $validateAs . ");";

//if auto complete///////
if ($_POST['frmType'][$i] == 'Autocomplete') {
    $setInsertAutocompleteSql = " \$sql = \"SELECT " . $fieldId . " AS f1, " . $fieldText . " AS f2 FROM " . $table . " WHERE " . $fieldPrefix1 . "__dbState='Live' AND  " . $fieldText . " LIKE '%\" . suUnstrip(\$_REQUEST['term']) . \"%' ORDER BY f2\";
     ";
}
//Remote code starts
for ($i = 0; $i <= sizeof($_POST['frmField']) - 1; $i++) {
    if ($_POST['frmField'][$i] != $_POST['primary']) {
        if (!strstr($_POST['frmType'][$i], 'field')) {
            
            if ($_POST['frmType'][$i] == 'Date') {
                $checkIfPost .= "if(!isset(\$_POST['" . $_POST['frmField'][$i] . "'])){\$_POST['" . $_POST['frmField'][$i] . "']='';}";
                $setInsertSql.="'" . $_POST['frmField'][$i] . "' => new MongoDate(strtotime(suDate2Db(\$_POST['" . $_POST['frmField'][$i] . "']) . ' ' . date('H:i:s'))),";
            } else {
                $checkIfPost .= "if(!isset(\$_POST['" . $_POST['frmField'][$i] . "'])){\$_POST['" . $_POST['frmField'][$i] . "']='';}";
                if ($_POST['frmField'][$i] == $_POST['uniqueField']) {
                    $setInsertSql.="'" . $_POST['frmField'][$i] . "' => suStrip(\$_POST['" . $_POST['frmField'][$i] . "']),";
                    $setInsertSql.="'" . $_POST['frmField'][$i] . "_slug' => suSlugifyString(\$_POST['" . $_POST['frmField'][$i] . "']),";
                } else {
                    $setInsertSql.="'" . $_POST['frmField'][$i] . "' => suStrip(\$_POST['" . $_POST['frmField'][$i] . "']),";
                }
            }
        }
    }
}

$fieldPrefix = explode('__', $_POST['frmField'][0]);
$fieldPrefix = $fieldPrefix[0];
$setInsertSql .= " '{$fieldPrefix}__Last_Action_On' =>new MongoDate(strtotime(date('Y-m-d H:i:s'))),'{$fieldPrefix}__Last_Action_By'=>\$_SESSION[SESSION_PREFIX . 'user__Name'], '{$fieldPrefix}__dbState'=>'Live'";


$remotePath = $appPath . $_POST['frmSubFolder'] . '/' . $_POST['frmFormsetvalue'] . '-remote.php';
$fieldsToShowRemote .="
, CONCAT('<button class=\'jtable-command-button jtable-edit-command-button\' onclick=\\\"window.location.href=\'" . $_POST['frmFormsetvalue'] . "-update.php/'," . $_POST['primary'] . ",'/\'\\\"','-',' title=\'Edit Record\'><span>Edit Record</span></button>') AS edit    
";
$remoteCode = "<?php    
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAs
//---------

//Check to stop page opening outside iframe
//Deliberately disabled for list and delete conditions
\$do = suSegment(1);
if (isset(\$_GET[\"do\"]) && (\$_GET[\"do\"] != \"check\") && (\$_GET[\"do\"] != \"autocomplete\") " . $autoCompleteFrameBuster . ") {
    suFrameBuster();
}
//Hand the unset POST variables
$checkIfPost;
?>
<?php


//Add record
if (\$do == \"add\") {
//Check referrer
    suCheckRef();
//Validate
    \$vError = array();

//
//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: \$dbs_" . $_POST['table'] . "['" . $_POST['primary'] . "_req']=''   
    suProcessForm(\$dbs_" . $_POST['table'] . ",\$validateAsArray);
" . $validateAddRemote . "
        
//Print validation errors on parent
    suValdationErrors(\$vError);

//add record
\$extraSql = array();
" . $extraSqlx1 . $extraSqlx2 . $extraSqlx3 . "
    \$col = \$db->" . $_POST['table'] . ";
    $date1    
    \$data = array(" . $setInsertSql . ");
    \$data = array_merge(\$data,\$extraSql);

    try {
        \$col->insert(\$data);
        \$max_id = \$data['_id'];
        //Upload files
        " . $uploadCheck . "
        /*POST INSERT PLACE*/
        " . $addCheckBoxRemote . "     

        suPrintJs('
            parent.suToggleButton(0);
            parent.\$(\"#error-area\").hide();
            parent.\$(\"#message-area\").show();
            parent.\$(\"#message-area\").html(\"' . SUCCESS_MESSAGE . '\");
            parent.\$(\"html, body\").animate({ scrollTop: parent.\$(\"html\").offset().top }, \"slow\");
            parent.suForm.reset();
        ');
    } catch (MongoWriteConcernException \$e) {
        if (\$e->getCode() > 0) {
            if (\$e->getCode() == 11000) {
            \$error = sprintf(DUPLICATION_ERROR, '" . $_POST['unique'] . "');
            } else {
                \$error = MONGODB_ERROR;
            }
            suPrintJs('
            parent.suToggleButton(0);
            parent.\$(\"#message-area\").hide();
            parent.\$(\"#error-area\").show();
            parent.\$(\"#error-area\").html(\"<ul><li>' . \$error . '</li></ul>\");
            parent.\$(\"html, body\").animate({ scrollTop: parent.\$(\"html\").offset().top }, \"slow\");
        ');
        }
    }


//Get autocomplete insert ids
" .
        $remoteCodeAutoInsert
        . "
    
}
//Update record
if (\$do == \"update\") {
//Check referrer
    suCheckRef();
//Validate
    \$vError = array();

//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: \$dbs_" . $_POST['table'] . "['" . $_POST['primary'] . "_req']=''   
    $resetUploadValidation
        
    suProcessForm(\$dbs_" . $_POST['table'] . ",\$validateAsArray);
" . $validateAddRemote . " 
    //Reset optional


//Print validation errors on parent
    suValdationErrors(\$vError);
//update record
\$extraSql = array();
" . $extraSqlx1 . $extraSqlx2 . $extraSqlx3 . "
    \$col = \$db->" . $_POST['table'] . ";
    \$data = array(" . $setInsertSql . ");
    \$data = array_merge(\$data,\$extraSql);

    \$mongoID = new MongoID(\$_POST['_id']);
    \$criteria = array('_id' => \$mongoID);
    try {
        \$col->update(\$criteria, \$data);
        \$max_id = \$mongoID;
        " . $uploadCheck . "
        /*POST UPDATE PLACE*/
        " . $updateCheckBoxRemote . "   

        suPrintJs(\"
            parent.window.location.href='\" . ADMIN_URL . \"" . $_POST['frmFormsetvalue'] . "{\$tableCardLink}.php';
        \");
    } catch (MongoWriteConcernException \$e) {
        if (\$e->getCode() > 0) {
            if (\$e->getCode() == 11000) {
            \$error = sprintf(DUPLICATION_ERROR, '" . $_POST['unique'] . "');
            } else {
                \$error = MONGODB_ERROR;
            }
            suPrintJs('
            parent.suToggleButton(0);
            parent.\$(\"#message-area\").hide();
            parent.\$(\"#error-area\").show();
            parent.\$(\"#error-area\").html(\"<ul><li>' . \$error . '</li></ul>\");
            parent.\$(\"html, body\").animate({ scrollTop: parent.$(\"html\").offset().top }, \"slow\");
        ');
        }
    }


}

//Delete record
if (\$do == \"delete\") {
//Check referrer
    suCheckRef();
    \$id = suSegment(2);
//Delete from database by updating just the state
//First find the previous unique field value
    \$mongoID = new MongoID(\$id);
    
\$col = new MongoCollection(\$db, '" . $_POST['table'] . "');
    try {
        \$criteria = array('_id' => \$mongoID);
    } catch (MongoException \$e) {
        \$numDocs = 0;
    }
    \$row = \$col->findOne(\$criteria);

//Number of documents
    \$numDocs = \$col->count(\$criteria);

    //make a unique id attach to previous unique field and do update
    \$uid = uniqid() . '-';
    \$data = array(
        '\$set' => array('" . $_POST['uniqueField'] . "' => \$uid . suStrip(\$row['" . $_POST['uniqueField'] . "']),
            '" . $fieldPrefix . "__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
            '" . $fieldPrefix . "__Last_Action_By' => \$_SESSION[SESSION_PREFIX . 'user__Name'],
            '" . $fieldPrefix . "__dbState' => 'Deleted')
    );
    \$criteria = array('_id' => \$mongoID);

    try {
        \$col->update(\$criteria, \$data);

    } catch (MongoWriteConcernException \$e) {
        if (\$e->getCode() > 0) {
            if (\$e->getCode() == 11000) {
            \$error = sprintf(DUPLICATION_ERROR, '" . $_POST['unique'] . "');
            } else {
                \$error = MONGODB_ERROR;
            }
        }
    }
    

}


";
$remoteCode = $remoteCode . $remoteCodeAutoComplete;
$remoteCode .="
?>    
";
//Write remote code
suWrite($remotePath, $remoteCode);
//Remote code ends
?>