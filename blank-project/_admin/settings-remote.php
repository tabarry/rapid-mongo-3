<?php

$forgetReferrer = TRUE;
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAsArray = array('setting__Setting_validateas' => 'required', 'setting__Key_validateas' => 'required', 'setting__Value_validateas' => 'required', 'setting__Type_validateas' => 'required',);
//---------
//Check to stop page opening outside iframe
//Deliberately disabled for list and delete conditions
$do = suSegment(1);
if (isset($_GET["do"]) && ($_GET["do"] != "check") && ($_GET["do"] != "autocomplete")) {
    suFrameBuster();
}
?>
<?php

//Add record
if ($do == "add") {
//Check referrer
    suCheckRef();
//Validate
    $vError = array();

//
//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: $dbs_sulata_settings['setting__ID_req']=''   
    suProcessForm($dbs_sulata_settings, $validateAsArray);


//Print validation errors on parent
    suValdationErrors($vError);

//add record
    $extraSql = array();

    $col = $db->sulata_settings;

    $data = array('setting__Setting' => suStrip($_POST['setting__Setting']), 'setting__Key' => suStrip($_POST['setting__Key']), 'setting__Setting_slug' => suSlugifyString($_POST['setting__Setting']), 'setting__Value' => suStrip($_POST['setting__Value']), 'setting__Type' => suStrip($_POST['setting__Type']), 'setting__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'setting__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'setting__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    try {
        $col->insert($data);
        $max_id = $data['_id'];
        //Upload files

        /* POST INSERT PLACE */
        $_SESSION[SESSION_PREFIX . 'getSettings'] = '';

        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#error-area").hide();
            parent.$("#message-area").show();
            parent.$("#message-area").html("' . SUCCESS_MESSAGE . '");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
            parent.suForm.reset();
        ');
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Key');
            } else {
                $error = MONGODB_ERROR;
            }
            suPrintJs('
            parent.suToggleButton(0);
            parent.$("#message-area").hide();
            parent.$("#error-area").show();
            parent.$("#error-area").html("<ul><li>' . $error . '</li></ul>");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
        ');
        }
    }


//Get autocomplete insert ids
}
//Update record
if ($do == "update") {
//Check referrer
    suCheckRef();
//Validate
    $vError = array();

//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: $dbs_sulata_settings['setting__ID_req']=''   
    suProcessForm($dbs_sulata_settings, $validateAsArray);

    //Reset optional
//Print validation errors on parent
    suValdationErrors($vError);
//update record
    $extraSql = array();

    $col = $db->sulata_settings;
    $data = array('$set' => array('setting__Setting' => suStrip($_POST['setting__Setting']), 'setting__Key' => suStrip($_POST['setting__Key']), 'setting__Key_slug' => suSlugifyString($_POST['setting__Key']), 'setting__Value' => suStrip($_POST['setting__Value']), 'setting__Type' => suStrip($_POST['setting__Type']), 'setting__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'setting__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'setting__dbState' => 'Live'));
    $data = array_merge($data, $extraSql);

    $mongoID = new MongoID($_POST['_id']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);
        $max_id = $mongoID;

        /* POST UPDATE PLACE */
        $_SESSION[SESSION_PREFIX . 'getSettings'] = '';

        suPrintJs("
            parent.window.location.href='" . ADMIN_URL . "settings{$tableCardLink}.php';
        ");
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Key');
            } else {
                $error = MONGODB_ERROR;
            }
            suPrintJs('
            parent.suToggleButton(0);
            parent.$("#message-area").hide();
            parent.$("#error-area").show();
            parent.$("#error-area").html("<ul><li>' . $error . '</li></ul>");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
        ');
        }
    }
}

//Delete record
if ($do == "delete") {
//Check referrer
    suCheckRef();
    $id = suSegment(2);
//Delete from database by updating just the state
//First find the previous unique field value
    $mongoID = new MongoID($id);

    $col = new MongoCollection($db, 'sulata_settings');
    try {
        $criteria = array('_id' => $mongoID);
    } catch (MongoException $e) {
        $numDocs = 0;
    }
    $row = $col->findOne($criteria);

//Number of documents
    $numDocs = $col->count($criteria);

    //make a unique id attach to previous unique field and do update
    $uid = uniqid() . '-';
    $data = array(
        '$set' => array('setting__Key' => $uid . suStrip($row['setting__Key']),
            'setting__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
            'setting__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'],
            'setting__dbState' => 'Deleted')
    );
    $criteria = array('_id' => $mongoID);

    try {
        $col->update($criteria, $data);
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Key');
            } else {
                $error = MONGODB_ERROR;
            }
        }
    }
}
?>    
