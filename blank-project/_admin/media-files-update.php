<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$lblClass = suShowLabels(TRUE); //Show labels on update page

$id = suSegment(1);

$col = new MongoCollection($db, 'sulata_media_files');
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



$pageName = 'Update Media Files';
$pageTitle = 'Update Media Files';
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
                                    <a href="<?php echo ADMIN_URL; ?>media-files-cards.php"><i class="fa fa-th-large"></i></a>
                                    <a href="<?php echo ADMIN_URL; ?>media-files.php"><i class="fa fa-table"></i></a>
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

                                <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>media-files-remote.php/update/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" enctype="multipart/form-data">


                                    <div class="gallery clearfix">
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">        
                                                <label><?php echo $dbs_sulata_media_files['mediafile__Category_req']; ?>Category:
                                                    <?php if ($addAccess == 'true') { ?>    
                                                        <a title="Add new record.." rel="prettyPhoto[iframes]" href="<?php echo ADMIN_URL; ?>media-add.php?overlay=yes&iframe=true&width=80%&height=100%"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/add-icon.png'/></a>

                                                        <a onclick="suReload('mediafile__Category', '<?php echo ADMIN_URL; ?>', '<?php echo suCrypt('sulata_media_categories'); ?>', '<?php echo suCrypt('mediacat__Name'); ?>', '<?php echo suCrypt('mediacat__Name'); ?>');" href="javascript:;"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/reload-icon.png'/></a>    
                                                    <?php } ?>    
                                                </label>
                                                <?php
                                                $ddCol = new MongoCollection($db, 'sulata_media_categories');
                                                $ddCriteria = array('mediacat__dbState' => 'Live');
                                                $ddSort = array('mediacat__Name' => 1);
                                                $ddFields = array('mediacat__Name' => 1);
                                                $ddRows = $ddCol->find($ddCriteria, $ddFields)->sort($ddSort);
                                                $options = array('^' => 'Select..');
                                                foreach ($ddRows as $ddDoc) {
                                                    $options[suUnstrip($ddDoc['mediacat__Name'])] = suUnstrip($ddDoc['mediacat__Name']);
                                                }


                                                $js = "class='form-control'";
                                                echo suDropdown('mediafile__Category', $options, suUnstrip($row['mediafile__Category']), $js)
                                                ?>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                
                                                <label><?php echo $dbs_sulata_media_files['mediafile__Title_req']; ?>Title:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_media_files['mediafile__Title_html5_type'], 'name' => 'mediafile__Title', 'id' => 'mediafile__Title', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_media_files['mediafile__Title_max'], 'value' => suUnstrip($row['mediafile__Title']), $dbs_sulata_media_files['mediafile__Title_html5_req'] => $dbs_sulata_media_files['mediafile__Title_html5_req'], 'class' => 'form-control');
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                    
                                                <label><?php echo $dbs_sulata_media_files['mediafile__Picture_req']; ?>Picture:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_media_files['mediafile__Picture_html5_type'], 'name' => 'mediafile__Picture', 'id' => 'mediafile__Picture');
                                                echo suInput('input', $arg);
                                                ?>
                                                <?php if ((isset($row['mediafile__Picture']) && $row['mediafile__Picture'] != '') && (file_exists(ADMIN_UPLOAD_PATH . $row['mediafile__Picture']))) { ?>

                                                <?php } ?>    
                                                <div> 
                                                    <a href="<?php echo BASE_URL . 'files/' . $row['mediafile__Picture']; ?>" target="_blank" class="underline"><?php echo VIEW_FILE; ?></a>
                                                    <?php echo $getSettings['allowed_image_formats']; ?></div>

                                            </div>
                                        </div>





                                        <?php
                                        $arg = array('type' => 'hidden', 'name' => 'previous_mediafile__Picture', 'id' => 'previous_mediafile__Picture', 'value' => $row['mediafile__Picture']);
                                        echo suInput('input', $arg);
                                        ?>   

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_media_files['mediafile__Short_Description_req'] . 'Short Description:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_media_files['mediafile__Short_Description_html5_type'], 'name' => 'mediafile__Short_Description', 'id' => 'mediafile__Short_Description', $dbs_sulata_media_files['mediafile__Short_Description_html5_req'] => $dbs_sulata_media_files['mediafile__Short_Description_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Short Description');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('textarea', $arg, suUnstrip($row['mediafile__Short_Description']), TRUE);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">        
                                                <label><?php echo $dbs_sulata_media_files['mediafile__Long_Description_req']; ?>Long Description:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_media_files['mediafile__Long_Description_html5_type'], 'name' => 'mediafile__Long_Description', 'id' => 'mediafile__Long_Description');
                                                echo suInput('textarea', $arg, suUnstrip($row['mediafile__Long_Description']), TRUE);
                                                suCKEditor('mediafile__Long_Description');
                                                ?>
                                            </div>                                
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">            

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_media_files['mediafile__Sequence_req'] . 'Sequence:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_media_files['mediafile__Sequence_html5_type'], 'name' => 'mediafile__Sequence', 'id' => 'mediafile__Sequence', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_media_files['mediafile__Sequence_max'], 'value' => suUnstrip($row['mediafile__Sequence']), $dbs_sulata_media_files['mediafile__Sequence_html5_req'] => $dbs_sulata_media_files['mediafile__Sequence_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Sequence');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>    

                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">    

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_media_files['mediafile__Date_req'] . 'Date:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_media_files['mediafile__Date_html5_type'], 'name' => 'mediafile__Date', 'id' => 'mediafile__Date', 'autocomplete' => 'off', 'class' => 'form-control dateBox', 'maxlength' => $dbs_sulata_media_files['mediafile__Date_max'], $dbs_sulata_media_files['mediafile__Date_html5_req'] => $dbs_sulata_media_files['mediafile__Date_html5_req']);
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Date');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>
                                        </div>
                                        <script>
                                            $(function() {
                                                $('#mediafile__Date').datepicker({
                                                    changeMonth: true,
                                                    changeYear: true
                                                });
                                                $('#mediafile__Date').datepicker('option', 'yearRange', 'c-100:c+10');
                                                $('#mediafile__Date').datepicker('option', 'dateFormat', '<?php echo DATE_FORMAT; ?>');
                                                $('#mediafile__Date').datepicker('setDate', '<?php echo suDateFromDb(date('Y-m-d', $row['mediafile__Date']->sec)) ?>');
                                            });

                                        </script>                                  


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