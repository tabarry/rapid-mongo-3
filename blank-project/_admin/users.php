<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$pageName = 'Manage Users';
$pageTitle = 'Manage Users';

$col = new MongoCollection($db, 'sulata_users');
//Search
if ($_GET['q'] != '') {
    $criteria = array('user__dbState' => 'Live', 'user__Email' => new MongoRegex("/" . $_GET['q'] . "/i"));
} else {
    $criteria = array('user__dbState' => 'Live');
}
//Paginate
if (!$_GET['start']) {
    $_GET['start'] = 0;
}
if (!$_GET['sr']) {
    $sr = 0;
} else {
    $sr = $_GET['sr'];
}

$selectedFields = array('user__Name' => 1, 'user__Phone' => 1, 'user__Email' => 1, 'user__Status' => 1, 'user__Type' => 1);

//Default sort
$sortOrder = array('user__Name_slug' => 1);
//Sort
if ($_GET['sort'] != '') {
    if ($_GET['sort'] == 'asc') {
        $sortOrder = array($_GET['f'] => 1);
    } else {
        $sortOrder = array($_GET['f'] => -1);
    }
    $row = $col->find($criteria, $selectedFields)->sort($sortOrder)->limit($getSettings['page_size'])->skip($_GET['start']);
} else {
    $row = $col->find($criteria, $selectedFields)->sort($sortOrder)->limit($getSettings['page_size'])->skip($_GET['start']);
}

$numDocs = $col->count($criteria, $selectedFields);

//Download CSV
if (suSegment(1) == 'stream-csv' && $downloadAccessCSV == TRUE) {
    $allRows = $col->find($criteria, $selectedFields);
    $outputFileName = 'users.csv';
    $fieldsArray = array('user__Name', 'user__Phone', 'user__Email', 'user__Status', 'user__Type');
    $headerArray = array('Name', 'Phone', 'Email', 'Status', 'Type');
    suMongoDbToCSV($fieldsArray, $headerArray, $outputFileName);
    exit;
}
//Download PDF
if (suSegment(1) == 'stream-pdf' && $downloadAccessPDF == TRUE) {
    $allRows = $col->find($criteria, $selectedFields);
    $outputFileName = 'users.pdf';
    $fieldsArray = array('user__Name', 'user__Phone', 'user__Email', 'user__Status', 'user__Type');
    $headerArray = array('Name', 'Phone', 'Email', 'Status', 'Type');
    suMongoDbToPDF($fieldsArray, $headerArray, $outputFileName);
    exit;
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

                                <form class="form-horizontal" name="searchForm" id="searchForm" method="get" action="">
                                    <fieldset id="search-area1">
                                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><i class="fa fa-search blue"></i> Search by Name</label>
                                        <div class="col-xs-7 col-sm-10 col-md-10 col-lg-10">
                                            <input id="q" type="text" value="" name="q" class="form-control" autocomplete="off">
                                        </div>
                                        <div class="col-xs-5 col-sm-2 col-md-2 col-lg-2">
                                            <input id="Submit" type="submit" value="Search" name="Submit" class="btn btn-primary pull-right">
                                        </div>
                                        <?php if ($_GET['q']) { ?>
                                            <div class="lineSpacer clear"></div>
                                            <div class="pull-right"><a style="text-decoration:underline !important;" href="<?php echo ADMIN_URL; ?>users.php">Clear search.</a></div>
                                        <?php } ?>
                                    </fieldset>
                                </form>


                                <div class="lineSpacer clear"></div>
                                <?php if ($addAccess == 'true') { ?>
                                    <div id="table-area"><a href="users-add.php" class="btn btn-black">Add new..</a></div>
                                <?php } ?>
                                <?php
                                $fieldsArray = array('user__Name_slug', 'user__Phone', 'user__Email', 'user__Status', 'user__Type');
                                suSort($fieldsArray);
                                ?>
                                <!-- TABLE -->

                                <table width="100%" class="table table-hover table-bordered tbl">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">
                                                Sr.
                                            </th>

                                            <th style="width:17%">Name</th>
                                            <th style="width:17%">Phone</th>
                                            <th style="width:17%">Email</th>
                                            <th style="width:17%">Status</th>
                                            <th style="width:17%">Type</th>
                                            <?php if (($editAccess == TRUE) || ($deleteAccess == TRUE)) { ?>
                                                <th style="width:10%">&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($row as $doc) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $sr = $sr + 1; ?>.
                                                </td>
                                                <td><?php echo suUnstrip($doc['user__Name']); ?></td>
                                                <td><?php echo suUnstrip($doc['user__Phone']); ?></td>
                                                <td><?php echo suUnstrip($doc['user__Email']); ?></td>
                                                <td><?php echo suUnstrip($doc['user__Status']); ?></td>
                                                <td><?php echo suUnstrip($doc['user__Type']); ?></td>

                                                <?php if (($editAccess == TRUE) || ($deleteAccess == TRUE)) { ?>
                                                    <td style="text-align: center;">
                                                        <?php if ($editAccess == TRUE) { ?>
                                                            <a href="<?php echo ADMIN_URL; ?>users-update.php/<?php echo $doc['_id']; ?>/"><img border="0" src="<?php echo BASE_URL; ?>sulata/images/edit.png" title="<?php echo EDIT_RECORD; ?>"/></a>
                                                        <?php } ?>
                                                        <?php if ($deleteAccess == TRUE) { ?>
                                                            <?php if ($doc['_id'] != $_SESSION[SESSION_PREFIX . 'user__ID']) { ?>
                                                                <a onclick="return delRecord(this, '<?php echo CONFIRM_DELETE; ?>')" href="<?php echo ADMIN_URL; ?>users-remote.php/delete/<?php echo $doc['_id']; ?>/" target="remote"><img border="0" src="<?php echo BASE_URL; ?>sulata/images/delete.png" title="<?php echo DELETE_RECORD; ?>"/></a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>



                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                                <!-- /TABLE -->
                                <?php
                                suPaginate();
                                ?>
                                <?php if ($downloadAccessCSV == TRUE && $numDocs > 0) { ?>
                                    <p>&nbsp;</p>
                                    <p><a target="remote" href="<?php echo $_SERVER['PHP_SELF']; ?>/stream-csv/" class="btn btn-black pull-right"><i class="fa fa-file-excel-o"></i> Download CSV</a></p>
                                    <p>&nbsp;</p>
                                    <div class="clearfix"></div>
                                <?php } ?>
                                <?php if ($downloadAccessPDF == TRUE && $numDocs > 0) { ?>
                                    <p>&nbsp;</p>
                                    <p><a target="remote" href="<?php echo $_SERVER['PHP_SELF']; ?>/stream-pdf/" class="btn btn-black pull-right"><i class="fa fa-file-pdf-o"></i> Download PDF</a></p>
                                    <p>&nbsp;</p>
                                    <div class="clearfix"></div>
                                <?php } ?>

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