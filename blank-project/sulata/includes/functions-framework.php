<?php

include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

//Validation array
$validateAsArray = array('master__Textbox_validateas' => 'required', 'master__Email_validateas' => 'email', 'master__Password_validateas' => 'password', 'master__Textarea_validateas' => 'required', 'master__HTMLArea_validateas' => 'required', 'master__Integer_validateas' => 'int', 'master__Float_validateas' => 'float', 'master__Double_validateas' => 'float', 'master__Date_validateas' => 'required', 'master__Enum_validateas' => 'enum', 'master__DDFB_validateas' => 'required', 'master__File_validateas' => 'file', 'master__Picture_validateas' => 'image', 'master__Attachment_validateas' => 'attachment', 'master__URL_validateas' => 'url', 'master__IP_validateas' => 'ip', 'master__CC_validateas' => 'cc',);
//---------
//Check to stop page opening outside iframe
//Deliberately disabled for list and delete conditions
$do = suSegment(1);
if (isset($_GET["do"]) && ($_GET["do"] != "check") && ($_GET["do"] != "autocomplete") && ($_GET["do"] != "autocomplete1")) {
    suFrameBuster();
}
//Handle the unset POST variables
if (!isset($_POST['master__Textbox'])) {
    $_POST['master__Textbox'] = '';
}if (!isset($_POST['master__Email'])) {
    $_POST['master__Email'] = '';
}if (!isset($_POST['master__Password'])) {
    $_POST['master__Password'] = '';
}if (!isset($_POST['master__Textarea'])) {
    $_POST['master__Textarea'] = '';
}if (!isset($_POST['master__HTMLArea'])) {
    $_POST['master__HTMLArea'] = '';
}if (!isset($_POST['master__Integer'])) {
    $_POST['master__Integer'] = '';
}if (!isset($_POST['master__Float'])) {
    $_POST['master__Float'] = '';
}if (!isset($_POST['master__Double'])) {
    $_POST['master__Double'] = '';
}if (!isset($_POST['master__Date'])) {
    $_POST['master__Date'] = '';
}if (!isset($_POST['master__Enum'])) {
    $_POST['master__Enum'] = '';
}if (!isset($_POST['master__DDFB'])) {
    $_POST['master__DDFB'] = '';
}if (!isset($_POST['master__Autocomplete'])) {
    $_POST['master__Autocomplete'] = '';
}if (!isset($_POST['master__URL'])) {
    $_POST['master__URL'] = '';
}if (!isset($_POST['master__IP'])) {
    $_POST['master__IP'] = '';
}if (!isset($_POST['master__CC'])) {
    $_POST['master__CC'] = '';
}if (!isset($_POST['master__CBFDB'])) {
    $_POST['master__CBFDB'] = '';
};
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
//To skip validation set '*' to '' like: $dbs_sulata_master['master__ID_req']=''   
    suProcessForm($dbs_sulata_master, $validateAsArray);

//Check if at least one checkbox is selected
    if (isset($_POST['master__CBFDB']) && sizeof($_POST['master__CBFDB']) == 0) {
        $vError[] = VALIDATE_EMPTY_CHECKBOX;
    }



    //Check if autocomplete value exists in the collection
    $col = new MongoCollection($db, 'sulata_faqs');
    $numDocs = 0;
    try {
        $criteria = array('faq__Question' => $_POST['master__Autocomplete'], 'faq__dbState' => 'Live');
        $row = $col->findOne($criteria);
    } catch (MongoException $e) {
        if ($e->getCode() > 0) {
            $vError[] = MONGODB_ERROR;
        }
    }
//Number of documents
    $numDocs = $col->count($criteria);
    if ($numDocs < 1) {
        $vError[] = sprintf(INCORRECT_AUTO_COMPLETE_VALUE, 'Autocomplete');
    }
    ///==
    //Check if autocomplete value exists in the collection
    $col = new MongoCollection($db, 'sulata_faqs');
    $numDocs = 0;
    try {
        $criteria = array('faq__Question' => $_POST['master__Autocomplete'], 'faq__dbState' => 'Live');
        $row = $col->findOne($criteria);
    } catch (MongoException $e) {
        if ($e->getCode() > 0) {
            $vError[] = MONGODB_ERROR;
        }
    }
//Number of documents
    $numDocs = $col->count($criteria);
    if ($numDocs < 1) {
        $vError[] = sprintf(INCORRECT_AUTO_COMPLETE_VALUE, 'Autocomplete');
    }
    ///==
//Print validation errors on parent

    suValdationErrors($vError);

//add record
    $extraSql = array();

    //for picture
    if ($_FILES['master__Picture']['name'] != '') {
        $uid = uniqid();
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $master__Picture = suSlugify($_FILES['master__Picture']['name'], $uid);
        $extraSql['master__Picture'] = $uploadPath . $master__Picture;
    } else {
        if (isset($_POST['master__Picture']) && ($_POST['master__Picture'] != '')) {
            $extraSql['master__Picture'] = $_POST['previous_master__Picture'];
        }
    }

    //for file
    if ($_FILES['master__File']['name'] != '') {
        $uid = uniqid();
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $master__File = suSlugify($_FILES['master__File']['name'], $uid);
        $extraSql['master__File'] = $uploadPath . $master__File;
    } else {
        if (isset($_POST['master__File']) && ($_POST['master__File'] != '')) {
            $extraSql['master__File'] = $_POST['previous_master__File'];
        }
    }

    //for attachment
    if ($_FILES['master__Attachment']['name'] != '') {
        $uid = uniqid();
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $master__Attachment = suSlugify($_FILES['master__Attachment']['name'], $uid);
        $extraSql['master__Attachment'] = $uploadPath . $master__Attachment;
    } else {
        if (isset($_POST['master__Attachment']) && ($_POST['master__Attachment'] != '')) {
            $extraSql['master__Attachment'] = $_POST['previous_master__Attachment'];
        }
    }

    $col = $db->sulata_master;

    $data = array('master__Textbox' => suStrip($_POST['master__Textbox']), 'master__Textbox_slug' => suSlugifyString($_POST['master__Textbox']), 'master__Email' => suStrip($_POST['master__Email']), 'master__Password' => suStrip($_POST['master__Password']), 'master__Textarea' => suStrip($_POST['master__Textarea']), 'master__HTMLArea' => suStrip($_POST['master__HTMLArea']), 'master__Integer' => suStrip($_POST['master__Integer']), 'master__Float' => suStrip($_POST['master__Float']), 'master__Double' => suStrip($_POST['master__Double']), 'master__Date' => new MongoDate(strtotime(suDate2Db($_POST['master__Date']) . ' ' . date('H:i:s'))), 'master__Enum' => suStrip($_POST['master__Enum']), 'master__DDFB' => suStrip($_POST['master__DDFB']), 'master__Autocomplete' => suStrip($_POST['master__Autocomplete']), 'master__URL' => suStrip($_POST['master__URL']), 'master__IP' => suStrip($_POST['master__IP']), 'master__CC' => suStrip($_POST['master__CC']), 'master__CBFDB' => suStrip($_POST['master__CBFDB']), 'master__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'master__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'master__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    try {
        $col->insert($data);
        $max_id = $data['_id'];
        //Upload files
        // file
        if ($_FILES['master__File']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $master__File);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_master__File']);
            copy($_FILES['master__File']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $master__File);
        }

        // picture
        if ($_FILES['master__Picture']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $master__Picture);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_master__Picture']);
            suResize($defaultWidth, $defaultHeight, $_FILES['master__Picture']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $master__Picture);
        }

        // attachment
        if ($_FILES['master__Attachment']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $master__Attachment);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_master__Attachment']);
            copy($_FILES['master__Attachment']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $master__Attachment);
        }

        /* POST INSERT PLACE */

//Add details data
        $chkArray = array();
        for ($i = 0; $i <= sizeof($_POST['master__CBFDB']) - 1; $i++) {
            if (isset($_POST['master__CBFDB'][$i])) {
                array_push($chkArray, $_POST['master__CBFDB'][$i]);
            }
        }
        $data = array(
            '$set' => array('master__CBFDB' => $chkArray)
        );
        $criteria = array('_id' => $max_id);
        $col->update($criteria, $data);




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
                $error = sprintf(DUPLICATION_ERROR, 'Textbox');
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
//To skip validation set '*' to '' like: $dbs_sulata_master['master__ID_req']=''   

    $dbs_sulata_master['master__File_req'] = '';


    $dbs_sulata_master['master__Picture_req'] = '';


    $dbs_sulata_master['master__Attachment_req'] = '';


    $dbs_sulata_master['master__File_req'] = '';


    $dbs_sulata_master['master__Picture_req'] = '';


    $dbs_sulata_master['master__Attachment_req'] = '';



    suProcessForm($dbs_sulata_master, $validateAsArray);

//Check if at least one checkbox is selected
    if (isset($_POST['master__CBFDB']) && sizeof($_POST['master__CBFDB']) == 0) {
        $vError[] = VALIDATE_EMPTY_CHECKBOX;
    }



    //Check if autocomplete value exists in the collection
    $col = new MongoCollection($db, 'sulata_faqs');
    $numDocs = 0;
    try {
        $criteria = array('faq__Question' => $_POST['master__Autocomplete'], 'faq__dbState' => 'Live');
        $row = $col->findOne($criteria);
    } catch (MongoException $e) {
        if ($e->getCode() > 0) {
            $vError[] = MONGODB_ERROR;
        }
    }
//Number of documents
    $numDocs = $col->count($criteria);
    if ($numDocs < 1) {
        $vError[] = sprintf(INCORRECT_AUTO_COMPLETE_VALUE, 'Autocomplete');
    }
    ///==
    //Check if autocomplete value exists in the collection
    $col = new MongoCollection($db, 'sulata_faqs');
    $numDocs = 0;
    try {
        $criteria = array('faq__Question' => $_POST['master__Autocomplete'], 'faq__dbState' => 'Live');
        $row = $col->findOne($criteria);
    } catch (MongoException $e) {
        if ($e->getCode() > 0) {
            $vError[] = MONGODB_ERROR;
        }
    }
//Number of documents
    $numDocs = $col->count($criteria);
    if ($numDocs < 1) {
        $vError[] = sprintf(INCORRECT_AUTO_COMPLETE_VALUE, 'Autocomplete');
    }
    ///==
    //Reset optional
//Print validation errors on parent

    suValdationErrors($vError);
//update record
    $extraSql = array();

    //for picture
    if ($_FILES['master__Picture']['name'] != '') {
        $uid = uniqid();
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $master__Picture = suSlugify($_FILES['master__Picture']['name'], $uid);
        $extraSql['master__Picture'] = $uploadPath . $master__Picture;
    } else {
        if (isset($_POST['master__Picture']) && ($_POST['master__Picture'] != '')) {
            $extraSql['master__Picture'] = $_POST['previous_master__Picture'];
        }
    }

    //for file
    if ($_FILES['master__File']['name'] != '') {
        $uid = uniqid();
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $master__File = suSlugify($_FILES['master__File']['name'], $uid);
        $extraSql['master__File'] = $uploadPath . $master__File;
    } else {
        if (isset($_POST['master__File']) && ($_POST['master__File'] != '')) {
            $extraSql['master__File'] = $_POST['previous_master__File'];
        }
    }

    //for attachment
    if ($_FILES['master__Attachment']['name'] != '') {
        $uid = uniqid();
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $master__Attachment = suSlugify($_FILES['master__Attachment']['name'], $uid);
        $extraSql['master__Attachment'] = $uploadPath . $master__Attachment;
    } else {
        if (isset($_POST['master__Attachment']) && ($_POST['master__Attachment'] != '')) {
            $extraSql['master__Attachment'] = $_POST['previous_master__Attachment'];
        }
    }

    $col = $db->sulata_master;
    $data = array('master__Textbox' => suStrip($_POST['master__Textbox']), 'master__Textbox_slug' => suSlugifyString($_POST['master__Textbox']), 'master__Email' => suStrip($_POST['master__Email']), 'master__Password' => suStrip($_POST['master__Password']), 'master__Textarea' => suStrip($_POST['master__Textarea']), 'master__HTMLArea' => suStrip($_POST['master__HTMLArea']), 'master__Integer' => suStrip($_POST['master__Integer']), 'master__Float' => suStrip($_POST['master__Float']), 'master__Double' => suStrip($_POST['master__Double']), 'master__Date' => new MongoDate(strtotime(suDate2Db($_POST['master__Date']) . ' ' . date('H:i:s'))), 'master__Enum' => suStrip($_POST['master__Enum']), 'master__DDFB' => suStrip($_POST['master__DDFB']), 'master__Autocomplete' => suStrip($_POST['master__Autocomplete']), 'master__URL' => suStrip($_POST['master__URL']), 'master__IP' => suStrip($_POST['master__IP']), 'master__CC' => suStrip($_POST['master__CC']), 'master__CBFDB' => suStrip($_POST['master__CBFDB']), 'master__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))), 'master__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'], 'master__dbState' => 'Live');
    $data = array_merge($data, $extraSql);

    $mongoID = new MongoID($_POST['_id']);
    $criteria = array('_id' => $mongoID);
    try {
        $col->update($criteria, $data);
        $max_id = $mongoID;

        // file
        if ($_FILES['master__File']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $master__File);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_master__File']);
            copy($_FILES['master__File']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $master__File);
        }

        // picture
        if ($_FILES['master__Picture']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $master__Picture);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_master__Picture']);
            suResize($defaultWidth, $defaultHeight, $_FILES['master__Picture']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $master__Picture);
        }

        // attachment
        if ($_FILES['master__Attachment']['name'] != '') {
            @unlink(ADMIN_UPLOAD_PATH . $uploadPath . $master__Attachment);
            @unlink(ADMIN_UPLOAD_PATH . $_POST['previous_master__Attachment']);
            copy($_FILES['master__Attachment']['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath . $master__Attachment);
        }

        /* POST UPDATE PLACE */

//update details data
        $chkArray = array();
        for ($i = 0; $i <= sizeof($_POST['master__CBFDB']) - 1; $i++) {
            array_push($chkArray, $_POST['master__CBFDB'][$i]);
        }
        $data = array(
            '$set' => array('master__CBFDB' => $chkArray)
        );
        $criteria = array('_id' => $max_id);
        $col->update($criteria, $data);




        suPrintJs("
            parent.window.location.href='" . ADMIN_URL . "master{$tableCardLink}.php';
        ");
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Textbox');
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

    $col = new MongoCollection($db, 'sulata_master');
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
        '$set' => array('master__Textbox' => $uid . suStrip($row['master__Textbox']),
            'master__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
            'master__Last_Action_By' => $_SESSION[SESSION_PREFIX . 'user__Name'],
            'master__dbState' => 'Deleted')
    );
    $criteria = array('_id' => $mongoID);

    try {
        $col->update($criteria, $data);
    } catch (MongoWriteConcernException $e) {
        if ($e->getCode() > 0) {
            if ($e->getCode() == 11000) {
                $error = sprintf(DUPLICATION_ERROR, 'Textbox');
            } else {
                $error = MONGODB_ERROR;
            }
        }
    }
}



//if autocomplete
if (isset($_GET['do']) && ($_GET['do'] == 'autocomplete1')) {


    $col = new MongoCollection($db, 'sulata_faqs');
    $sort = array('faq__Question' => 1);
    $fields = array('faq__Question' => 1);
    $criteria = array('faq__dbState' => 'Live', 'faq__Question' => new MongoRegex("/" . suUnstrip($_REQUEST['term']) . "/i"));
    $numDocs = $col->count($criteria);
    $row = $col->find($criteria)->sort($sort);


    $data = array();
    if ($numDocs > 0) {
        foreach ($row as $doc) {
            $data[] = array(
                'label' => $doc['faq__Question'],
                'value' => $doc['faq__Question']
            );
        }
    }

    echo json_encode($data);
    flush();
}
?>    
