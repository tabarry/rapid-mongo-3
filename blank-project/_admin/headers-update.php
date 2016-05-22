<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$lblClass = suShowLabels(TRUE); //Show labels on update page

$id = suSegment(1);

$col = new MongoCollection($db, 'sulata_headers');
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



$pageName = 'Update Headers';
$pageTitle = 'Update Headers';
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
                                    <a href="<?php echo ADMIN_URL; ?>headers-cards.php"><i class="fa fa-th-large"></i></a>
                                    <a href="<?php echo ADMIN_URL; ?>headers.php"><i class="fa fa-table"></i></a>
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

                                <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>headers-remote.php/update/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" enctype="multipart/form-data">


                                    <div class="gallery clearfix">
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_headers['header__Title_req'] . 'Title:', TRUE);

                                                //Input
                                                $arg = array('type' => $dbs_sulata_headers['header__Title_html5_type'], 'name' => 'header__Title', 'id' => 'header__Title', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_headers['header__Title_max'], 'value' => suUnstrip($row['header__Title']), $dbs_sulata_headers['header__Title_html5_req'] => $dbs_sulata_headers['header__Title_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Title');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                    
                                                <label><?php echo $dbs_sulata_headers['header__Picture_req']; ?>Picture:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_headers['header__Picture_html5_type'], 'name' => 'header__Picture', 'id' => 'header__Picture');
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>
                                        </div>

                                        <?php if ((isset($row['header__Picture']) && $row['header__Picture'] != '') && (file_exists(ADMIN_UPLOAD_PATH . $row['header__Picture']))) { ?>
                                            <a href="<?php echo BASE_URL . 'files/' . $row['header__Picture']; ?>" target="_blank" class="underline"><?php echo VIEW_FILE; ?></a>
                                        <?php } ?>    

                                        <div><?php echo $getSettings['allowed_image_formats']; ?></div>


                                        <?php
                                        $arg = array('type' => 'hidden', 'name' => 'previous_header__Picture', 'id' => 'previous_header__Picture', 'value' => $row['header__Picture']);
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
        <?php suIframe(); ?>  
    </body>
    <!--PRETTY PHOTO-->
    <?php include('inc-pretty-photo.php'); ?>    
</html>