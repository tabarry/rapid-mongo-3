<?php

$forgetReferrer = TRUE;
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAsArray = array('mediafile__Category_validateas' => 'required', 'mediafile__Title_validateas' => 'required', 'mediafile__Picture_validateas' => 'image', 'mediafile__Short_Description_validateas' => 'required', 'mediafile__Long_Description_validateas' => 'required', 'mediafile__Sequence_validateas' => 'double', 'mediafile__Date_validateas' => 'required',);
//---------
//Check to stop page opening outside iframe
//Deliberately disabled for list and delete conditions
$do = suSegment(1);
if (($_GET["do"] != "check") && ($_GET["do"] != "autocomplete")) {
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
//To skip validation set '*' to '' like: $dbs_sulata_media_files['mediafile__ID_req']=''   
    suProcessForm($dbs_sulata_media_files, $validateAsArray);


//Print validation errors on parent
    suValdationErrors($vError);

//add record
    $extraSql = array();

    //for picture
    if ($_FILES['mediafile__Picture']['name'] != '') {
        $uid = uniqid();
        $mediafile__Picture = suSlugify($_FILES['mediafile__Picture']['name'], $uid);
        $extraSql['mediafile__Picture'] = $mediafile__Picture;
    }

    $col = $db->sulata_media_files;

    $data = array('mediafile__Category' => suStrip($_POST['mediafile__Category']), 'mediafile__Title' => suStrip($_POST['mediafile__Title']), 'mediafile__Title_slug' => suSlugifyString($_POST['mediafile__Title']), 'mediafile__Short_Description' => suStrip($_POST['mediafile__Short_Description']), 'mediafile__Long_Description' => suStrip($_POST['mediafile__Long_Description']), 'mediafile__Sequence' => suStrip($_POST['mediafile__Sequence']), 'mediafile__Date' => new MongoDate(strtotime(suDate2Db($_POST['mediafile__Date']) . ' ' . date('H:i:s'))), 'mediafile__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'mediafile__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'mediafile__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    try {
        $col->insert($data);
        $max_id = $data['_id'];
        //Upload files
        // picture
        if ($_FILES['mediafile__Picture']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $mediafile__Picture);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_mediafile__Picture']);
            suResize($defaultWidth, $defaultHeight, $_FILES['mediafile__Picture']['tmp_name'], ADMIN_UPLOAD_PATH . $mediafile__Picture);
        }

        /* POST INSERT PLACE */


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
                $error = sprintf(DUPLICATION_ERROR, 'Picture');
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
//To skip validation set '*' to '' like: $dbs_sulata_media_files['mediafile__ID_req']='' 
    $dbs_sulata_media_files['mediafile__Picture_req'] = '';

    suProcessForm($dbs_sulata_media_files, $validateAsArray);

    //Reset optional
//Print validation errors on parent
    suValdationErrors($vError);
//update record
    $extraSql = array();

    //for picture
    if ($_FILES['mediafile__Picture']['name'] != '') {
        $uid = uniqid();
        $mediafile__Picture = suSlugify($_FILES['mediafile__Picture']['name'], $uid);
        $extraSql['mediafile__Picture'] = $mediafile__Picture;
    } else {
        $extraSql['mediafile__Picture'] = $_POST['previous_mediafile__Picture'];
    }

    $col = $db->sulata_media_files;
    $data = array('mediafile__Category' => suStrip($_POST['mediafile__Category']), 'mediafile__Title' => suStrip($_POST['mediafile__Title']), 'mediafile__Title_slug' => suSlugifyString($_POST['mediafile__Title']), 'mediafile__Short_Description' => suStrip($_POST['mediafile__Short_Description']), 'mediafile__Long_Description' => suStrip($_POST['mediafile__Long_Description']), 'mediafile__Sequence' => suStrip($_POST['mediafile__Sequence']), 'mediafile__Date' => new MongoDate(strtotime(suDate2Db($_POST['mediafile__Date']) . ' ' . date('H:i:s'))), 'mediafile__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'mediafile__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'mediafile__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    $mongoID = new MongoID($_POST['_id']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);
        $max_id = $mongoID;

        // picture
        if ($_FILES['mediafile__Picture']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $mediafile__Picture);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_mediafile__Picture']);
            suResize($defaultWidth, $defaultHeight, $_FILES['mediafile__Picture']['tmp_name'], ADMIN_UPLOAD_PATH . $mediafile__Picture);
        }

        /* POST UPDATE PLACE */


        suPrintJs("
            parent.window.location.href='" . ADMIN_URL . "media-files{$tableCardLink}.php';
        ");
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Picture');
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

    $col = new MongoCollection($db, 'sulata_media_files');
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
        '$set' => array('mediafile__Picture' => $uid . suStrip($row['mediafile__Picture']),
            'mediafile__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
            'mediafile__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'],
            'mediafile__dbState' => 'Deleted')
    );
    $criteria = array('_id' => $mongoID);

    try {
        $col->update($criteria, $data);
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Picture');
            } else {
                $error = MONGODB_ERROR;
            }
        }
    }
}
?>    
