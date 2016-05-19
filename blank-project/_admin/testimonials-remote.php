<?php

include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAsArray = array('testimonial__Name_validateas' => 'required', 'testimonial__Designation_and_Company_validateas' => 'required', 'testimonial__Location_validateas' => 'required','testimonial__Status_validateas' => 'required', 'testimonial__Date_validateas' => 'required',);
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
//To skip validation set '*' to '' like: $dbs_sulata_testimonials['testimonial__ID_req']=''   
    suProcessForm($dbs_sulata_testimonials, $validateAsArray);


//Print validation errors on parent
    suValdationErrors($vError);

//add record
    $extraSql = array();

    $col = $db->sulata_testimonials;

    $data = array('testimonial__Name' => suStrip($_POST['testimonial__Name']), 'testimonial__Designation_and_Company' => suStrip($_POST['testimonial__Designation_and_Company']), 'testimonial__Designation_and_Company_slug' => suSlugifyString($_POST['testimonial__Designation_and_Company']), 'testimonial__Location' => suStrip($_POST['testimonial__Location']), 'testimonial__Status' => suStrip($_POST['testimonial__Status']), 'testimonial__Date' => new MongoDate(strtotime(suDate2Db($_POST['testimonial__Date']) . ' ' . date('H:i:s'))), 'testimonial__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'testimonial__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'testimonial__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    try {
        $col->insert($data);
        $max_id = $data['_id'];
        //Upload files

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
                $error = sprintf(DUPLICATION_ERROR, 'Designation and Company');
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
//To skip validation set '*' to '' like: $dbs_sulata_testimonials['testimonial__ID_req']=''   
    suProcessForm($dbs_sulata_testimonials, $validateAsArray);

    //Reset optional
//Print validation errors on parent
    suValdationErrors($vError);
//update record
    $extraSql = array();

    $col = $db->sulata_testimonials;
    $data = array('testimonial__Name' => suStrip($_POST['testimonial__Name']), 'testimonial__Designation_and_Company' => suStrip($_POST['testimonial__Designation_and_Company']), 'testimonial__Designation_and_Company_slug' => suSlugifyString($_POST['testimonial__Designation_and_Company']), 'testimonial__Location' => suStrip($_POST['testimonial__Location']), 'testimonial__Status' => suStrip($_POST['testimonial__Status']), 'testimonial__Date' => new MongoDate(strtotime(suDate2Db($_POST['testimonial__Date']) . ' ' . date('H:i:s'))), 'testimonial__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'testimonial__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'testimonial__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    $mongoID = new MongoID($_POST['_id']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);
        $max_id = $mongoID;

        /* POST UPDATE PLACE */


        suPrintJs("
            parent.window.location.href='" . ADMIN_URL . "testimonials{$tableCardLink}.php';
        ");
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Designation and Company');
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

    $col = new MongoCollection($db, 'sulata_testimonials');
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
        '$set' => array('testimonial__Designation_and_Company' => $uid . suStrip($row['testimonial__Designation_and_Company']),
            'testimonial__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
            'testimonial__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'],
            'testimonial__dbState' => 'Deleted')
    );
    $criteria = array('_id' => $mongoID);

    try {
        $col->update($criteria, $data);
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Designation and Company');
            } else {
                $error = MONGODB_ERROR;
            }
        }
    }
}
?>    
