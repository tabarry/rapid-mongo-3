<?php

$forgetReferrer = TRUE;
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAsArray = array('mediacat__Name_validateas' => 'required', 'mediacat__Picture_validateas' => 'image', 'mediacat__Description_validateas' => 'required', 'mediacat__Thumbnail_Width_validateas' => 'int', 'mediacat__Thumbnail_Height_validateas' => 'int', 'mediacat__Image_Width_validateas' => 'int', 'mediacat__Image_Height_validateas' => 'int', 'mediacat__Sequence_validateas' => 'double',);
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
//To skip validation set '*' to '' like: $dbs_sulata_media_categories['mediacat__ID_req']=''   
    suProcessForm($dbs_sulata_media_categories, $validateAsArray);


//Print validation errors on parent
    suValdationErrors($vError);

//add record
    $extraSql = array();

    //for picture
    if ($_FILES['mediacat__Picture']['name'] != '') {
        $uid = uniqid();
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $mediacat__Picture = suSlugify($_FILES['mediacat__Picture']['name'], $uid);
        $extraSql['mediacat__Picture'] = $uploadPath . $mediacat__Picture;
    }

    $col = $db->sulata_media_categories;

    $data = array('mediacat__Name' => suStrip($_POST['mediacat__Name']), 'mediacat__Name_slug' => suSlugifyString($_POST['mediacat__Name']), 'mediacat__Description' => suStrip($_POST['mediacat__Description']), 'mediacat__Thumbnail_Width' => suStrip($_POST['mediacat__Thumbnail_Width']), 'mediacat__Thumbnail_Height' => suStrip($_POST['mediacat__Thumbnail_Height']), 'mediacat__Image_Width' => suStrip($_POST['mediacat__Image_Width']), 'mediacat__Image_Height' => suStrip($_POST['mediacat__Image_Height']), 'mediacat__Sequence' => suStrip($_POST['mediacat__Sequence']), 'mediacat__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'mediacat__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'mediacat__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    try {
        $col->insert($data);
        $max_id = $data['_id'];
        //Upload files
        // picture
        if ($_FILES['mediacat__Picture']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $mediacat__Picture);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_mediacat__Picture']);
            suResize($defaultWidth, $defaultHeight, $_FILES['mediacat__Picture']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $mediacat__Picture);
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
                $error = sprintf(DUPLICATION_ERROR, 'Name');
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
//To skip validation set '*' to '' like: $dbs_sulata_media_categories['mediacat__ID_req']=''  
    $dbs_sulata_media_categories['mediacat__Picture_req'] = '';
    suProcessForm($dbs_sulata_media_categories, $validateAsArray);

    //Reset optional
//Print validation errors on parent
    suValdationErrors($vError);
//update record
    //for picture
    if ($_FILES['mediacat__Picture']['name'] != '') {
        $uid = uniqid();
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $mediacat__Picture = suSlugify($_FILES['mediacat__Picture']['name'], $uid);
        $extraSql['mediacat__Picture'] = $uploadPath . $mediacat__Picture;
    } else {
        $extraSql['mediacat__Picture'] = $_POST['previous_mediacat__Picture'];
    }

    $col = $db->sulata_media_categories;
    $data = array('$set' => array('mediacat__Name' => suStrip($_POST['mediacat__Name']), 'mediacat__Name_slug' => suSlugifyString($_POST['mediacat__Name']), 'mediacat__Description' => suStrip($_POST['mediacat__Description']), 'mediacat__Thumbnail_Width' => suStrip($_POST['mediacat__Thumbnail_Width']), 'mediacat__Thumbnail_Height' => suStrip($_POST['mediacat__Thumbnail_Height']), 'mediacat__Image_Width' => suStrip($_POST['mediacat__Image_Width']), 'mediacat__Image_Height' => suStrip($_POST['mediacat__Image_Height']), 'mediacat__Sequence' => suStrip($_POST['mediacat__Sequence']), 'mediacat__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'mediacat__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'mediacat__dbState' => 'Live'));
    $data = array_merge($data, $extraSql);

    $mongoID = new MongoID($_POST['_id']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);
        $max_id = $mongoID;

        // picture
        if ($_FILES['mediacat__Picture']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $mediacat__Picture);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_mediacat__Picture']);
            suResize($defaultWidth, $defaultHeight, $_FILES['mediacat__Picture']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $mediacat__Picture);
        }

        /* POST UPDATE PLACE */


        suPrintJs("
            parent.window.location.href='" . ADMIN_URL . "media-categories{$tableCardLink}.php';
        ");
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Name');
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

    $col = new MongoCollection($db, 'sulata_media_categories');
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
        '$set' => array('mediacat__Name' => $uid . suStrip($row['mediacat__Name']),
            'mediacat__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
            'mediacat__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'],
            'mediacat__dbState' => 'Deleted')
    );
    $criteria = array('_id' => $mongoID);

    try {
        $col->update($criteria, $data);
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Name');
            } else {
                $error = MONGODB_ERROR;
            }
        }
    }
}
?>    
