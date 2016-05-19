<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$lblClass = suShowLabels(TRUE); //Show labels on update page

$id = suSegment(1);

$col = new MongoCollection($db, 'sulata_pages');
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



$pageName = 'Update Pages';
$pageTitle = 'Update Pages';
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
                                    <a href="<?php echo ADMIN_URL; ?>pages-cards.php"><i class="fa fa-th-large"></i></a>
                                    <a href="<?php echo ADMIN_URL; ?>pages.php"><i class="fa fa-table"></i></a>
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

                                <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>pages-remote.php/update/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" >
                                    <link rel="stylesheet" href="<?php echo BASE_URL; ?>sulata/themes/redmond/jquery-ui.css">

                                    <div class="gallery clearfix">
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_pages['page__Name_req'] . 'Name:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_pages['page__Name_html5_type'], 'name' => 'page__Name', 'id' => 'page__Name', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Name_max'], 'value' => suUnstrip($row['page__Name']), $dbs_sulata_pages['page__Name_html5_req'] => $dbs_sulata_pages['page__Name_html5_req'], 'class' => 'form-control');
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
                                                echo suInput('label', $label, $dbs_sulata_pages['page__Heading_req'] . 'Heading:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_pages['page__Heading_html5_type'], 'name' => 'page__Heading', 'id' => 'page__Heading', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Heading_max'], 'value' => suUnstrip($row['page__Heading']), $dbs_sulata_pages['page__Heading_html5_req'] => $dbs_sulata_pages['page__Heading_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Heading');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">               
                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_pages['page__Permalink_req'] . 'Permalink:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_pages['page__Permalink_html5_type'], 'name' => 'page__Permalink', 'id' => 'page__Permalink', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Permalink_max'], 'value' => suUnstrip($row['page__Permalink']), $dbs_sulata_pages['page__Permalink_html5_req'] => $dbs_sulata_pages['page__Permalink_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Permalink');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_pages['page__Title_req'] . 'Title:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_pages['page__Title_html5_type'], 'name' => 'page__Title', 'id' => 'page__Title', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Title_max'], 'value' => suUnstrip($row['page__Title']), $dbs_sulata_pages['page__Title_html5_req'] => $dbs_sulata_pages['page__Title_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Title');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_pages['page__Keyword_req'] . 'Keyword:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_pages['page__Keyword_html5_type'], 'name' => 'page__Keyword', 'id' => 'page__Keyword', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Keyword_max'], 'value' => suUnstrip($row['page__Keyword']), $dbs_sulata_pages['page__Keyword_html5_req'] => $dbs_sulata_pages['page__Keyword_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Keyword');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">                

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_pages['page__Description_req'] . 'Description:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_pages['page__Description_html5_type'], 'name' => 'page__Description', 'id' => 'page__Description', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Description_max'], 'value' => suUnstrip($row['page__Description']), $dbs_sulata_pages['page__Description_html5_req'] => $dbs_sulata_pages['page__Description_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Description');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">        
                                                <label><?php echo $dbs_sulata_pages['page__Header_req']; ?>Header:
                                                    <?php if ($addAccess == 'true') { ?>    
                                                        <a title="Add new record.." rel="prettyPhoto[iframes]" href="<?php echo ADMIN_URL; ?>headers-add.php?overlay=yes&iframe=true&width=80%&height=100%"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/add-icon.png'/></a>

                                                        <a onclick="suReload('page__Header', '<?php echo ADMIN_URL; ?>', '<?php echo suCrypt('sulata_headers'); ?>', '<?php echo suCrypt('header__Title'); ?>', '<?php echo suCrypt('header__Title'); ?>');" href="javascript:;"><img border='0' src='<?php echo BASE_URL; ?>sulata/images/reload-icon.png'/></a>    
                                                    <?php } ?>    
                                                </label>
                                                <?php
                                                $ddCol = new MongoCollection($db, 'sulata_headers');
                                                $ddCriteria = array('header__dbState' => 'Live');
                                                $ddSort = array('header__Title' => 1);
                                                $ddFields = array('header__Title' => 1);
                                                $ddRows = $ddCol->find($ddCriteria, $ddFields)->sort($ddSort);
                                                $options = array('^' => 'Select..');
                                                foreach ($ddRows as $ddDoc) {
                                                    $options[suUnstrip($ddDoc['header__Title'])] = suUnstrip($ddDoc['header__Title']);
                                                }


                                                $js = "class='form-control'";
                                                echo suDropdown('page__Header', $options, suUnstrip($row['page__Header']), $js)
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_pages['page__Short_Text_req'] . 'Short Text:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_pages['page__Short_Text_html5_type'], 'name' => 'page__Short_Text', 'id' => 'page__Short_Text', $dbs_sulata_pages['page__Short_Text_html5_req'] => $dbs_sulata_pages['page__Short_Text_html5_req'], 'class' => 'form-control');

                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Short Text');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('textarea', $arg, suUnstrip($row['page__Short_Text']), TRUE);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">        
                                                <label><?php echo $dbs_sulata_pages['page__Long_Text_req']; ?>Long Text:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_pages['page__Long_Text_html5_type'], 'name' => 'page__Long_Text', 'id' => 'page__Long_Text');
                                                echo suInput('textarea', $arg, suUnstrip($row['page__Long_Text']), TRUE);
                                                suCKEditor('page__Long_Text');
                                                ?>
                                            </div>                                
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">            

                                                <?php
                                                //Label
                                                $label = array('class' => $lblClass);
                                                echo suInput('label', $label, $dbs_sulata_pages['page__Sequence_req'] . 'Sequence:', TRUE);
                                                //Input
                                                $arg = array('type' => $dbs_sulata_pages['page__Sequence_html5_type'], 'name' => 'page__Sequence', 'id' => 'page__Sequence', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Sequence_max'], 'value' => suUnstrip($row['page__Sequence']), $dbs_sulata_pages['page__Sequence_html5_req'] => $dbs_sulata_pages['page__Sequence_html5_req'], 'class' => 'form-control');
                                                //Placeholder
                                                if ($showLabel == FALSE) {
                                                    $placeholder = array('placeholder' => 'Sequence');
                                                    $arg = array_merge($placeholder, $arg);
                                                }
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>    
                                        </div>


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