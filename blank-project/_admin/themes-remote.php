<?php

$forgetReferrer = TRUE;
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

$do = suSegment(1);
$newTheme = suSegment(2);
//Update record
if ($do == "change") {
//Check referrer
    suCheckRef();
    $col = $db->sulata_users;
    $data = array(
        '$set' => array('user__Theme' => $newTheme)
    );
    $mongoID = new MongoID($_SESSION[SESSION_PREFIX . 'user__ID']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);

        $_SESSION[SESSION_PREFIX . 'user__Theme'] = $newTheme;
        suPrintJs('
            parent.document.getElementById("themeCss").setAttribute("href", "' . ADMIN_URL . 'css/themes/' . $newTheme . '/style.css");
        ');
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            $error = MONGODB_ERROR;
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
