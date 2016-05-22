<?php

$forgetReferrer = TRUE;
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAsArray = array('page__Name_validateas' => 'required', 'page__Heading_validateas' => 'required', 'page__Permalink_validateas' => 'required', 'page__Title_validateas' => 'required', 'page__Keyword_validateas' => 'required', 'page__Description_validateas' => 'required', 'page__Header_validateas' => 'required', 'page__Short_Text_validateas' => 'required', 'page__Long_Text_validateas' => 'required', 'page__Sequence_validateas' => 'double',);
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
//To skip validation set '*' to '' like: $dbs_sulata_pages['page__ID_req']=''   
    suProcessForm($dbs_sulata_pages, $validateAsArray);


//Print validation errors on parent
    suValdationErrors($vError);

//add record
    $extraSql = array();

    $col = $db->sulata_pages;

    $data = array('page__Name' => suStrip($_POST['page__Name']), 'page__Name_slug' => suSlugifyString($_POST['page__Name']), 'page__Heading' => suStrip($_POST['page__Heading']), 'page__Permalink' => suSlugifyName(suStrip($_POST['page__Permalink'])), 'page__Title' => suStrip($_POST['page__Title']), 'page__Keyword' => suStrip($_POST['page__Keyword']), 'page__Description' => suStrip($_POST['page__Description']), 'page__Header' => suStrip($_POST['page__Header']), 'page__Short_Text' => suStrip($_POST['page__Short_Text']), 'page__Long_Text' => suStrip($_POST['page__Long_Text']), 'page__Sequence' => suStrip($_POST['page__Sequence']), 'page__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'page__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'page__dbState' => 'Live');
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
//To skip validation set '*' to '' like: $dbs_sulata_pages['page__ID_req']=''   
    suProcessForm($dbs_sulata_pages, $validateAsArray);

    //Reset optional
//Print validation errors on parent
    suValdationErrors($vError);
//update record
    $extraSql = array();

    $col = $db->sulata_pages;
    $data = array('page__Name' => suStrip($_POST['page__Name']), 'page__Name_slug' => suSlugifyString($_POST['page__Name']), 'page__Heading' => suStrip($_POST['page__Heading']), 'page__Permalink' => suSlugifyName(suStrip($_POST['page__Permalink'])), 'page__Title' => suStrip($_POST['page__Title']), 'page__Keyword' => suStrip($_POST['page__Keyword']), 'page__Description' => suStrip($_POST['page__Description']), 'page__Header' => suStrip($_POST['page__Header']), 'page__Short_Text' => suStrip($_POST['page__Short_Text']), 'page__Long_Text' => suStrip($_POST['page__Long_Text']), 'page__Sequence' => suStrip($_POST['page__Sequence']), 'page__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'page__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'page__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    $mongoID = new MongoID($_POST['_id']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);
        $max_id = $mongoID;

        /* POST UPDATE PLACE */


        suPrintJs("
            parent.window.location.href='" . ADMIN_URL . "pages{$tableCardLink}.php';
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

    $col = new MongoCollection($db, 'sulata_pages');
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
        '$set' => array('page__Name' => $uid . suStrip($row['page__Name']),
            'page__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
            'page__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'],
            'page__dbState' => 'Deleted')
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
