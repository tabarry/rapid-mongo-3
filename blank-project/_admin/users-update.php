<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$lblClass = suShowLabels(TRUE); //Show labels on update page

$addAccess = FALSE;
$id = suSegment(1);
if ($id == $_SESSION[SESSION_PREFIX . 'user__ID']) {
    suRedirect(ADMIN_URL . 'users-update.php');
}
//Conditions for update profile
if ($id == '') {
    $editAccess = "style=display:none";
    $pageName = 'Update Profile';
    $pageTitle = 'Update Profile';
    $id = $_SESSION[SESSION_PREFIX . 'user__ID'];
} else {
    $pageName = 'Update Users';
    $pageTitle = 'Update Users';
}
$col = new MongoCollection($db, 'sulata_users');
try {
    $criteria = array('_id' => new MongoId($id));
} catch (MongoException $e) {
    $numDocs = 0;
}
$row = $col->findOne($criteria);


//Number of documents
$numDocs = $col->count($criteria);

if ($numDocs == 0) {
    suExit(INVALID_RECORD);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include('inc-head.php'); ?>
        <script type="text/javascript">
            $(document).ready(function() {
                //Keep session alive
                $(function() {
                    window.setInterval("suStayAlive('<?php echo PING_URL; ?>')", 300000);
                });
                //Disable submit button
                suToggleButton(1);
            });
        </script> 
    </head>

    <body>

        <div class="outer">

            <!-- Sidebar starts -->

            <?php include('inc-sidebar.php'); ?>
            <!-- Sidebar ends -->

            <!-- Mainbar starts -->
            <div class="mainbar">
                <?php include('inc-heading.php'); ?>
                <!-- Mainbar head starts -->
                <div class="main-head">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-6">
                                <!-- Bread crumbs -->
                                <?php include('inc-breadcrumbs.php'); ?>
                            </div>

                            <div class="col-md-3 col-sm-4 col-xs-6">
                                <!-- Search block -->

                            </div>

                            <div class="col-md-3 col-sm-4 hidden-xs">
                                <!-- Notifications -->
                                <div class="head-noty text-center">

                                </div>
                                <div class="clearfix"></div>
                            </div>


                            <div class="col-md-3 hidden-sm hidden-xs">
                                <!-- Head user -->

                                <?php include('inc-header.php'); ?>
                                <div class="clearfix"></div>
                            </div>
                        </div>	
                    </div>

                </div>

                <!-- Mainbar head ends -->

                <div class="main-content">
                    <div class="container">

                        <div class="page-content">

                            <!-- Heading -->
                            <div class="single-head">
                                <!-- Heading -->
                                <h3 class="pull-left"><i class="fa fa-desktop purple"></i> <?php echo $pageTitle; ?></h3>
                                <div class="pull-right">
                                    <a href="<?php echo ADMIN_URL; ?>users-cards.php"><i class="fa fa-th-large"></i></a>
                                    <a href="<?php echo ADMIN_URL; ?>users.php"><i class="fa fa-table"></i></a>
                                </div>

                                <div class="clearfix"></div>
                            </div>

                            <div id="content-area">

                                <div id="error-area">
                                    <ul></ul>
                                </div>    
                                <div id="message-area">
                                    <p></p>
                                </div>
                                <!--SU STARTS-->
                                <?php
                                if ((isset($row['user__Picture']) && $row['user__Picture'] != '') && (file_exists(ADMIN_UPLOAD_PATH . $row['user__Picture']))) {
                                    $userImage = BASE_URL . 'files/' . $row['user__Picture'];
                                } else {
                                    $userImage = BASE_URL . 'files/default-user.png';
                                }
                                ?>

                                <div class="imgThumb" style="background-image:url(<?php echo $userImage; ?>);"></div>

                                <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>users-remote.php/update/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" enctype="multipart/form-data">


                                    <div class="gallery clearfix">
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_users['user__Name_req'] . 'Name:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_users['user__Name_html5_type'], 'name' => 'user__Name', 'id' => 'user__Name', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Name_max'], 'value' => suUnstrip($row['user__Name']), $dbs_sulata_users['user__Name_html5_req'] => $dbs_sulata_users['user__Name_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Name');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>

                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                 
                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_users['user__Phone_req'] . 'Phone:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_users['user__Phone_html5_type'], 'name' => 'user__Phone', 'id' => 'user__Phone', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Phone_max'], 'value' => suUnstrip($row['user__Phone']), $dbs_sulata_users['user__Phone_html5_req'] => $dbs_sulata_users['user__Phone_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Phone');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                
                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_users['user__Email_req'] . 'Email:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_users['user__Email_html5_type'], 'name' => 'user__Email', 'id' => 'user__Email', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Email_max'], 'value' => suUnstrip($row['user__Email']), $dbs_sulata_users['user__Email_html5_req'] => $dbs_sulata_users['user__Email_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Email');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>
                                        </div>
                                        <?php if ($getSettings['google_login'] != 1) { ?>
                                            <div class="form-group">
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">            
                                                    <?php
                                                    //Label
                                                    $label = array('class' => $lblClass);
                                                    echo suInput('label', $label, $dbs_sulata_users['user__Password_req'] . 'Password:', TRUE);
                                                    //Input
                                                    $arg = array('type' => $dbs_sulata_users['user__Password_html5_type'], 'name' => 'user__Password', 'id' => 'user__Password', 'maxlength' => $dbs_sulata_users['user__Password_max'], 'value' => suDecrypt(suUnstrip($row['user__Password'])), $dbs_sulata_users['user__Password_html5_req'] => $dbs_sulata_users['user__Password_html5_req'], 'class' => 'form-control', 'onkeyup' => "doStrongPassword('user__Password','passwordStrength');");
                                                    //Placeholder
                                                    if ($showLabel == FALSE) {
                                                        $placeholder = array('placeholder' => 'Password');
                                                        $arg = array_merge($placeholder, $arg);
                                                    }
                                                    echo suInput('input', $arg);
                                                    ?>
                                                    <span id="passwordStrength">&nbsp;</span>
                                                    <input type="hidden" id="passwordStrength_hidden" name="passwordStrength_hidden"/>

                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                             
                                                    <?php
                                                    //Label
                                                    $label = array('class' => $lblClass);
                                                    echo suInput('label', $label, $dbs_sulata_users['user__Password_req'] . 'Confirm Password:', TRUE);
                                                    //Input
                                                    $arg = array('type' => $dbs_sulata_users['user__Password_html5_type'], 'name' => 'user__Password2', 'id' => 'user__Password2', 'maxlength' => $dbs_sulata_users['user__Password_max'], 'value' => suDecrypt(suUnstrip($row['user__Password'])), $dbs_sulata_users['user__Password_html5_req'] => $dbs_sulata_users['user__Password_html5_req'], 'class' => 'form-control');
                                                    //Placeholder
                                                    if ($showLabel == FALSE) {
                                                        $placeholder = array('placeholder' => 'Confirm Password');
                                                        $arg = array_merge($placeholder, $arg);
                                                    }
                                                    echo suInput('input', $arg);
                                                    ?>
                                                </div>                                
                                            </div>                                
                                        <?php } ?>
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" <?php echo $editAccess; ?>>
                                                <label><?php echo $dbs_sulata_users['user__Status_req']; ?>Status:

                                                </label>
                                                <?php
                                                $options = $dbs_sulata_users['user__Status_array'];
                                                $js = "class='form-control'";
                                                echo suDropdown('user__Status', $options, suUnstrip($row['user__Status']), $js)
                                                ?>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                  
                                                <label><?php echo $dbs_sulata_users['user__Picture_req']; ?>Picture:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_users['user__Picture_html5_type'], 'name' => 'user__Picture', 'id' => 'user__Picture');
                                                echo suInput('input', $arg);
                                                ?>
                                                <?php if ((isset($row['user__Picture']) && $row['user__Picture'] != '') && (file_exists(ADMIN_UPLOAD_PATH . $row['user__Picture']))) { ?>
                                                    <a href="<?php echo BASE_URL . 'files/' . $row['user__Picture']; ?>" target="_blank"><?php echo VIEW_FILE; ?></a>
                                                <?php } ?>    

                                                <div><?php echo $getSettings['allowed_image_formats']; ?></div>

                                            </div>
                                        </div>



                                        <?php
                                        if (!isset($row['user__Picture'])) {
                                            $row['user__Picture'] = '';
                                        }
                                        $arg = array('type' => 'hidden', 'name' => 'previous_user__Picture', 'id' => 'previous_user__Picture', 'value' => $row['user__Picture']);
                                        echo suInput('input', $arg);
                                        ?>   

                                    </div>
                                    <div class="lineSpacer clear"></div>
                                    <p>
                                        <?php
                                        $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-primary pull-right');
                                        echo suInput('input', $arg);
                                        ?>                              
                                    </p>
                                    <?php
                                    //Id field
                                    $arg = array('type' => 'hidden', 'name' => '_id', 'id' => '_id', 'value' => $id);
                                    echo suInput('input', $arg);
                                    ?>
                                    <p>&nbsp;</p>
                                </form>

                                <!--SU ENDS-->
                            </div>
                        </div>
                        <?php include('inc-site-footer.php'); ?>
                    </div>
                </div>

            </div>

            <!-- Mainbar ends -->

            <div class="clearfix"></div>
        </div>
        <?php include('inc-footer.php'); ?>
        <!-- load the password strength -->
        <script>
            doStrongPassword('user__Password', 'passwordStrength');
        </script>
        <?php suIframe(); ?>  
    </body>
    <!--PRETTY PHOTO-->
    <?php include('inc-pretty-photo.php'); ?>    
</html>