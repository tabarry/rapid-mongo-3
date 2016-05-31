<?php

$forgetReferrer = TRUE;
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAsArray = array('user__Name_validateas' => 'required', 'user__Phone_validateas' => 'required', 'user__Email_validateas' => 'email', 'user__Password_validateas' => 'password', 'user__Status_validateas' => 'required', 'user__Picture_validateas' => 'image', 'user__Type_validateas' => 'required', 'user__Notes_validateas' => 'required', 'user__Theme_validateas' => 'required',);
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
    //If Google Login then set password
    if ($getSettings['google_login'] == 1) {
        $_POST['user__Password'] = '0123456789';
        $_POST['user__Password2'] = '0123456789';
    }
//Validate
    $vError = array();

//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: $dbs_sulata_users['user__ID_req']=''   
    suProcessForm($dbs_sulata_users, $validateAsArray);
//Check password strength
    if ($_POST['passwordStrength_hidden'] < 2) {
        $vError[] = WEAK_PASSWORD;
        //suPrintJS("parent.$('passwordStrength').html('');");
    }
//
//Print validation errors on parent
    suValdationErrors($vError);

//add record
    $extraSql = array();

    //for picture
    if ($_FILES['user__Picture']['name'] != '') {
        $uid = uniqid();
        $user__Picture = suSlugify($_FILES['user__Picture']['name'], $uid);
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $extraSql['user__Picture'] = $uploadPath . $user__Picture;
    }

    $col = $db->sulata_users;

    $data = array('user__Name' => suStrip($_POST['user__Name']), 'user__Phone' => suStrip($_POST['user__Phone']), 'user__Email' => suStrip($_POST['user__Email']), 'user__Name_slug' => suSlugifyString($_POST['user__Name']), 'user__Password' => suCrypt(suStrip($_POST['user__Password'])), 'user__Status' => suStrip($_POST['user__Status']), 'user__Type' => 'Admin', 'user__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'user__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'user__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    try {
        $col->insert($data);
        $max_id = $data['_id'];
        //Upload files
        // picture
        if ($_FILES['user__Picture']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $user__Picture);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_user__Picture']);
            suResize($getSettings['employee_image_width'], $getSettings['employee_image_height'], $_FILES['user__Picture']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $user__Picture);
        }

        /* POST INSERT PLACE */

//Send login details to user
        if ((isset($_POST['send_to_user'])) && ($_POST['send_to_user'] == 'Yes')) {
            if ($getSettings['google_login'] == 1) {
                $email = file_get_contents('../sulata/mails/new-user-gmail.html');
            } else {
                $email = file_get_contents('../sulata/mails/new-user.html');
            }
            $email = str_replace('#NAME#', $_POST['user__Name'], $email);
            $email = str_replace('#SITE_NAME#', $getSettings['site_name'], $email);
            $email = str_replace('#EMAIL#', $_POST['user__Email'], $email);
            $email = str_replace('#USER#', $_SESSION[SESSION_PREFIX . 'user__Name'], $email);
            $email = str_replace('#PASSWORD#', $_POST['user__Password'], $email);
            $subject = sprintf(LOST_PASSWORD_SUBJECT, $getSettings['site_name']);

            //Send mails
            suMail($_POST['user__Email'], $subject, $email, $getSettings['site_name'], $getSettings['site_email'], TRUE);
        }
        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#error-area").hide();
            parent.$("#message-area").show();
            parent.$("#message-area").html("' . SUCCESS_MESSAGE . '");
            parent.$("#passwordStrength").html("");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
            parent.suForm.reset();
        ');
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Email');
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
    //If Google Login then set password
    if ($getSettings['google_login'] == 1) {
        $_POST['user__Password'] = '0123456789';
        $_POST['user__Password2'] = '0123456789';
    }
//Validate
    $vError = array();

//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: $dbs_sulata_users['user__ID_req']=''   
    suProcessForm($dbs_sulata_users, $validateAsArray);

    //Reset optional
//Check password strength
    if ($_POST['passwordStrength_hidden'] < 2) {
        $vError[] = WEAK_PASSWORD;
    }
//
//Print validation errors on parent
    suValdationErrors($vError);
//update record
    $extraSql = array();

    //for picture
    if ($_FILES['user__Picture']['name'] != '') {
        $uid = uniqid();
        $user__Picture = suSlugify($_FILES['user__Picture']['name'], $uid);
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $extraSql['user__Picture'] = $uploadPath . $user__Picture;
    }

    $col = $db->sulata_users;

    $data = array('$set' => array('user__Name' => suStrip($_POST['user__Name']), 'user__Phone' => suStrip($_POST['user__Phone']), 'user__Email' => suStrip($_POST['user__Email']), 'user__Email_slug' => suSlugifyString($_POST['user__Email']), 'user__Password' => suCrypt(suStrip($_POST['user__Password'])), 'user__Status' => suStrip($_POST['user__Status']), 'user__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'user__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'user__dbState' => 'Live'));
    $data = array_merge($data, $extraSql);


    $data = array('$set' => $data);

    $mongoID = new MongoID($_POST['_id']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);
        $max_id = $mongoID;

        // picture
        if ($_FILES['user__Picture']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $user__Picture);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_user__Picture']);
            suResize($getSettings['employee_image_width'], $getSettings['employee_image_height'], $_FILES['user__Picture']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $user__Picture);
            //Reset picture session
            if ($_SESSION[SESSION_PREFIX . 'user__ID'] == $_POST['_id']) {
                $_SESSION[SESSION_PREFIX . 'user__Picture'] = $uploadPath . $user__Picture;
            }
        }

        /* POST UPDATE PLACE */


        suPrintJs("
            parent.window.location.href='" . ADMIN_URL . "users{$tableCardLink}.php';
        ");
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Email');
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

    $col = new MongoCollection($db, 'sulata_users');
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
        '$set' => array('user__Email' => $uid . suStrip($row['user__Email']),
            'user__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
            'user__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'],
            'user__dbState' => 'Deleted')
    );
    $criteria = array('_id' => $mongoID);

    try {
        $col->update($criteria, $data);
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Email');
            } else {
                $error = MONGODB_ERROR;
            }
        }
    }
}
?>    
