<?php
$forgetReferrer = TRUE;
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');

//Unset login sessions
$_SESSION[SESSION_PREFIX . 'user__ID'] = '';
$_SESSION[SESSION_PREFIX . 'user__Name'] = '';
$_SESSION[SESSION_PREFIX . 'user__Email'] = '';
$_SESSION[SESSION_PREFIX . 'user__Picture'] = '';
$_SESSION[SESSION_PREFIX . 'user__Status'] = '';
$_SESSION[SESSION_PREFIX . 'user__Theme'] = '';

if ($getSettings['google_login'] == 1) {
    suRedirect(GOOGLE_LOGOUT_URL . BASE_URL . 'google-plus');
}
//--
//Validation array
$validateAsArray = array('user__Name_validateas' => 'required', 'user__Phone_validateas' => 'required', 'user__Email_validateas' => 'email', 'user__Password_validateas' => 'required', 'user__Status_validateas' => 'enum', 'user__Picture_validateas' => 'image',);
//---------

/* login */
if ($_GET['do'] == 'login') {

    $col = new MongoCollection($db, 'sulata_users');
    try {
        $criteria = array('user__Email' => $_POST['user__Email'], 'user__Password' => suCrypt($_POST['user__Password']));
    } catch (MongoException $e) {
        $numDocs = 0;
    }
    $row = $col->findOne($criteria);

//Number of documents
    $numDocs = $col->count($criteria);
    if ($numDocs == 1) {
        //Set sessions
        $_SESSION[SESSION_PREFIX . 'user__ID'] = $row['_id'];
        $_SESSION[SESSION_PREFIX . 'user__Name'] = suUnstrip($row['user__Name']);
        $_SESSION[SESSION_PREFIX . 'user__Email'] = suUnstrip($row['user__Email']);
        $_SESSION[SESSION_PREFIX . 'user__Picture'] = $row['user__Picture'];
        $_SESSION[SESSION_PREFIX . 'user__Status'] = $row['user__Status'];
        $_SESSION[SESSION_PREFIX . 'user__Theme'] = $row['user__Theme'];
        $_SESSION[SESSION_PREFIX . 'user__IP'] = $_SERVER['REMOTE_ADDR'];
//set remember cookie
        if ($_POST['user__Remember'] == 'yes') {
            $cookieExpires = time() + (COOKIE_EXPIRY_DAYS * 86400);
            setcookie(SESSION_PREFIX . '_user__Remember', $_POST['user__Email'], $cookieExpires);
        } else {
            setcookie(SESSION_PREFIX . '_user__Remember', '');
        }
        //Update user IP
        $data = array(
            '$set' => array('user__IP' => $_SESSION[SESSION_PREFIX . 'user__IP'],
                'user__Last_Action_On' => new MongoDate(strtotime(date('Y-m-d H:i:s'))),
                'user__Last_Action_By' => 'Login')
        );

        $mongoID = new MongoID($_SESSION[SESSION_PREFIX . 'user__ID']);
        $criteria = array('_id' => $mongoID);
        try {
            $col->update($criteria, $data);
        } catch (MongoWriteConcernException $e) {
            if ($e->getCode() > 0) {
                exit($e->getMessage());
            }
        }
        if ($_SESSION[SESSION_PREFIX . 'REFERRER'] == '') {
            suPrintJS("parent.suRedirect('index.php');");
        } else {
            suPrintJS("parent.suRedirect('" . $_SESSION[SESSION_PREFIX . 'REFERRER'] . "');");
        }
    } else {
        $vError = array();
//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: $dbs_sulata_users['user__ID_req']=''               
        suProcessForm($dbs_sulata_users, $validateAsArray);
        $dbs_sulata_users['user__Email_req'] = '';
//Print validation errors on parent
        $vError[] = INVALID_LOGIN;
        suValdationErrors($vError);
    }

    exit();
}
/* logout */
if ($_GET['do'] == 'logout') {
    $_SESSION[SESSION_PREFIX . 'user__ID'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Name'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Email'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Picture'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Status'] = '';
    $_SESSION[SESSION_PREFIX . 'user__IP'] = '';
    $_SESSION[SESSION_PREFIX . 'REFERRER'] = '';
    session_unset();
//Redirect
    suPrintJS("top.suRedirect('login.php');");
    exit();
}
/* retrieve */
if ($_GET['do'] == 'retrieve') {
    $col = new MongoCollection($db, 'sulata_users');
    try {
        $criteria = array('_id' => new MongoId($id));
    } catch (MongoException $e) {
        $numDocs = 0;
    }
    $row = $col->findOne($criteria);

//Number of documents
    $numDocs = $col->count($criteria);
    if ($numDocs == 1) {
        $row = suFetch($result);
        $email = file_get_contents('../sulata/mails/lost-password.html');
        $email = str_replace('#NAME#', suUnstrip($row['user__Name']), $email);
        $email = str_replace('#SITE_NAME#', $getSettings['site_name'], $email);
        $email = str_replace('#EMAIL#', suUnstrip($row['user__Email']), $email);
        $email = str_replace('#PASSWORD#', suDecrypt(suUnstrip($row['user__Password'])), $email);
        $subject = sprintf(LOST_PASSWORD_SUBJECT, $getSettings['site_name']);
        //Send mails
        suMail(suUnstrip($row['user__Email']), $subject, $email, $getSettings['site_name'], $getSettings['site_email'], TRUE);
        suFree($result);
//Redirect
        suPrintJS("alert('" . LOST_PASSWORD_DATA_SENT . "');parent.suRedirect('login.php');");
    } else {
        $vError = array();
        $vError[] = NO_LOST_PASSWORD_DATA;
        suValdationErrors($vError);
        suFree($result);
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include('inc-head.php'); ?>
        <script type="text/javascript">
            $(document).ready(function() {

                //Disable submit button
                suToggleButton(1);
            });
        </script> 
    </head>

    <body>

        <div class="outer-page">
            <center><h1><?php echo $getSettings['site_name']; ?></h1></center>
            <p>&nbsp;</p>
            <!-- Login page -->
            <div class="login-page">
                <div id="content-area">
                    <div id="error-area">
                        <ul></ul>
                    </div>    
                    <div id="message-area">
                        <p></p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#login" data-toggle="tab" class="br-lblue"><i class="fa fa-sign-in"></i> Sign In</a></li>
                    <li><a href="#contact" data-toggle="tab" class="br-lblue"><i class="fa fa-envelope"></i> Lost Password</a></li>
                </ul>


                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="login">

                        <!-- Login form -->

                        <form action="<?php echo ADMIN_URL; ?>login.php?do=login" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" >			
                            <div class="form-group">
                                <?php
                                if ($_COOKIE[SESSION_PREFIX . '_user__Remember'] != '') {
                                    $userVal = $_COOKIE[SESSION_PREFIX . '_user__Remember'];
                                    $checkedValArray = array('checked' => 'checked');
                                } else {
                                    $userVal = '';
                                    $checkedValArray = array();
                                }
                                ?>
                                <label for="email"><?php echo $dbs_sulata_users['user__Email_req']; ?>Email:</label>
                                <?php
                                $arg = array('type' => 'text', 'name' => 'user__Email', 'id' => 'user__Email', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Email_max'], 'class' => 'form-control', 'value' => $userVal, $checkedVal);
                                echo suInput('input', $arg);
                                ?>						  </div>
                            <div class="form-group">
                                <label for="password"><?php echo $dbs_sulata_users['user__Password_req']; ?>Password:</label>
                                <?php
                                $arg = array('type' => 'password', 'name' => 'user__Password', 'id' => 'user__Password', 'maxlength' => $dbs_sulata_users['user__Password_max'], 'class' => 'form-control');
                                echo suInput('input', $arg);
                                ?> 
                                <table>
                                    <tr>
                                        <td>
                                            <label>
                                                <?php
                                                $arg = array('type' => 'checkbox', 'name' => 'user__Remember', 'id' => 'user__Remember', 'value' => 'yes', 'class' => 'form-control');
                                                $arg = array_merge($arg, $checkedValArray);
                                                echo suInput('input', $arg);
                                                ?> 
                                            </label>
                                        </td>
                                        <td style="width:20px;">
                                            &nbsp;
                                        </td>
                                        <td>
                                            <label for="user__Remember">Remember me for <?php echo COOKIE_EXPIRY_DAYS; ?> days. </label>
                                        </td>
                                    </tr>
                                </table>


                            </div>
                            <?php
                            $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-info btn-sm');
                            echo suInput('input', $arg);
                            ?>

                        </form>

                    </div>




                    <div class="tab-pane fade" id="contact">

                        <!-- Lost Password -->

                        <form action="<?php echo ADMIN_URL; ?>login.php?do=retrieve" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" >	
                            <div class="form-group">
                                <label for="email"><?php echo $dbs_sulata_users['user__Email_req']; ?>Email:</label>
                                <?php
                                $arg = array('type' => 'text', 'name' => 'user__Email', 'id' => 'user__Email', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Email_max'], 'class' => 'form-control');
                                echo suInput('input', $arg);
                                ?>						  </div>

                            <?php
                            $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-info btn-sm');
                            echo suInput('input', $arg);
                            ?>
                        </form>

                    </div>
                </div>

            </div>	

        </div>
        <?php include('inc-footer.php'); ?>

    </body>
    <?php suIframe(); ?>  

</html>