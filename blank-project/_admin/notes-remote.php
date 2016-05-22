<?php

$forgetReferrer = TRUE;
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAsArray = array('user__Name_validateas' => 'required', 'user__Notes_validateas' => 'required',);
//---------
//Check to stop page opening outside iframe
//Deliberately disabled for list and delete conditions
$do = suSegment(1);
if (isset($_GET["do"]) && ($_GET["do"] != "check") && ($_GET["do"] != "autocomplete")) {
    suFrameBuster();
}
?>
<?php

//Update record
if ($do == "update") {
//Check referrer
    suCheckRef();
//Validate
    $vError = array();

//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: $dbs_sulata_users['user__ID_req']=''   
    //Reset optional


    suProcessForm($dbs_sulata_users, $validateAsArray);

//Print validation errors on parent
    suValdationErrors($vError);

//Get autocomplete insert ids
//update record
    $extraSql = '';
    $col = $db->sulata_users;
    $data = array(
        '$set' => array('user__Notes' => suStrip($_POST['user__Notes']))
    );
    $mongoID = new MongoID($_SESSION[SESSION_PREFIX . 'user__ID']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);

        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#error-area").hide();
            parent.$("#message-area").show();
            parent.$("#message-area").html("' . NOTES_UPDATE_MESSAGE . '");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
        ');
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Note');
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
?>    
