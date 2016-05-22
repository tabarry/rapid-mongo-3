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

$col = new MongoCollection($db, 'sulata_settings');
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



$pageName = 'Update Settings';
$pageTitle = 'Update Settings';
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
                                    <a href="<?php echo ADMIN_URL; ?>settings-cards.php"><i class="fa fa-th-large"></i></a>
                                    <a href="<?php echo ADMIN_URL; ?>settings.php"><i class="fa fa-table"></i></a>
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

                                <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>settings-remote.php/update/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" >


                                    <div class="gallery clearfix">
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                 

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_settings['setting__Setting_req'] . 'Setting:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_settings['setting__Setting_html5_type'], 'name' => 'setting__Setting', 'id' => 'setting__Setting', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_settings['setting__Setting_max'], 'value' => suUnstrip($row['setting__Setting']), $dbs_sulata_settings['setting__Setting_html5_req'] => $dbs_sulata_settings['setting__Setting_html5_req'], 'class' => 'form-control', 'readonly' => 'readonly');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Setting');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>

                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display:none">                 

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_settings['setting__Key_req'] . 'Key:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_settings['setting__Key_html5_type'], 'name' => 'setting__Key', 'id' => 'setting__Key', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_settings['setting__Key_max'], 'value' => suUnstrip($row['setting__Key']), $dbs_sulata_settings['setting__Key_html5_req'] => $dbs_sulata_settings['setting__Key_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Key');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">                 
                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_settings['setting__Value_req'] . 'Value:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_settings['setting__Value_html5_type'], 'name' => 'setting__Value', 'id' => 'setting__Value', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_settings['setting__Value_max'], 'value' => suUnstrip($row['setting__Value']), $dbs_sulata_settings['setting__Value_html5_req'] => $dbs_sulata_settings['setting__Value_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Value');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>

                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display:none">        
                                                <label><?php echo $dbs_sulata_settings['setting__Type_req']; ?>Type:

                                                </label>
                                                <?php
                                                $options = $dbs_sulata_settings['setting__Type_array'];
                                                $js = "class='form-control'";
                                                echo suDropdown('setting__Type', $options, suUnstrip($row['setting__Type']), $js)
                                                ?>
                                            </div>
                                        </div>

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
        <?php suIframe(); ?>  
    </body>
    <!--PRETTY PHOTO-->
    <?php include('inc-pretty-photo.php'); ?>    
</html>