<?php

//View code starts
//Get fields to show
$fieldsToShow = "";
$fieldsToShowRemote = "";
$fieldsWithQuotes = "";
$fieldsToLoop = "";
$f1 = explode('.', $_POST['frmDetailsDestText']);
$f1 = end($f1);
$setSql = "";
$linksPlace = "";

for ($i = 0; $i <= sizeof($_POST['frmShow']) - 1; $i++) {
    if (strstr($_POST['frmShow'][$i], '_Date')) {
    } else {
    }
    $colSize = $colSize + 1;
}

$colSize = round(85 / ($colSize - 1));
$colData = "";
$csvHeaders = "";
$fieldsArray = "";
$selectedFields = "";
for ($i = 0; $i <= sizeof($_POST['frmShow']) - 1; $i++) {
    if ($_POST['frmShow'][$i] != $_POST['primary']) {
        if (strstr($_POST['frmShow'][$i], '_Date')) {
            $fieldsToShow .= "<th style=\"width:" . $colSize . "%\">" . makeFieldLabel($_POST['frmShow'][$i]) . "</th>\n";
            $fieldsToLoop .= "\$" . $_POST['frmShow'][$i] . "[\$key] = \$value['" . $_POST['frmShow'][$i] . "'];\n";
            /* $colData .= "<td><?php echo suUnstrip(\$doc['" . $_POST['frmShow'][$i] . "2']);?></td>\n"; */
            $colData .= "<td><?php echo date('F d, Y', \$doc['" . $_POST['frmShow'][$i] . "']->sec);?></td>\n";
        } else {
            $fieldsToShow .= "<th style=\"width:" . $colSize . "%\">" . makeFieldLabel($_POST['frmShow'][$i]) . "</th>\n";
            $fieldsToLoop .= "\$" . $_POST['frmShow'][$i] . "[\$key] = \$value['" . $_POST['frmShow'][$i] . "'];\n";
            /*
            if ($_POST['frmShow'][$i] == $f1) {

                $colData .= "<td>\n<?php\n"
                        . "sort(\$doc['" . $_POST['frmShow'][$i] . "']);\n"
                        . "foreach (\$doc['" . $_POST['frmShow'][$i] . "'] as \$fieldValue) {\n\techo suUnstrip(\$fieldValue).'. ';\n}\n"
                        . "?>\n</td>\n";
            } else {
                $colData .= "<td><?php echo suUnstrip(\$doc['" . $_POST['frmShow'][$i] . "']);?></td>\n";
            }
             * 
             */
            $colData .= "<td><?php echo suUnstrip(\$doc['" . $_POST['frmShow'][$i] . "']);?></td>\n";
        }
        if (!stristr($_POST['frmShow'][$i], '__ID')) {
             if($_POST['frmShow'][$i]==$_POST['frmSearch']) {
                $fieldsArray.="'" . $_POST['frmShow'][$i] . "_slug',";
            } else {
                $fieldsArray.="'" . $_POST['frmShow'][$i] . "',";
            }
            $selectedFields.="'" . $_POST['frmShow'][$i] . "'=>1,";
        }


        $csvHeaders .= "'" . makeFieldLabel($_POST['frmShow'][$i]) . "',";
    }


    if (strstr($_POST['frmShow'][$i], '_Date')) {
        $fieldsToShowRemote .= $_POST['frmShow'][$i] . ",";
        //$fieldsToShowRemote .= " DATE_FORMAT(" . $_POST['frmShow'][$i] . ", '%b %d, %y') AS " . $_POST['frmShow'][$i] . "2,";
        //$fieldsWithQuotes .= " DATE_FORMAT(" . $_POST['frmShow'][$i] . ", '%b %d, %y') AS " . $_POST['frmShow'][$i] . "2,";
        if (!stristr($_POST['frmShow'][$i], '__ID')) {
            $fieldsToShowRemote .= $_POST['frmShow'][$i] . ",";

            $fieldsWithQuotes .= " '" . $_POST['frmShow'][$i] . "',";
        }
    } else {
        if (!stristr($_POST['frmShow'][$i], '__ID')) {
            $fieldsToShowRemote .= $_POST['frmShow'][$i] . ",";
            $fieldsWithQuotes .= " '" . $_POST['frmShow'][$i] . "',";
        }
    }
}
$csvHeaders = substr($csvHeaders, 0, -1);
$colData.="
    <?php if ((\$editAccess == TRUE) || (\$deleteAccess == TRUE)) { ?>
    <td style=\"text-align: center;\">
    <?php if (\$editAccess == TRUE) { ?>
                                                <a href=\"<?php echo ADMIN_URL;?>" . $_POST['frmFormsetvalue'] . "-update.php/<?php echo \$doc['_id'];?>/\"><img border=\"0\" src=\"<?php echo BASE_URL; ?>sulata/images/edit.png\" title=\"<?php echo EDIT_RECORD; ?>\"/></a>
                                                    <?php } ?>
<?php if (\$deleteAccess == TRUE) { ?>
                                                <a onclick=\"return delRecord(this, '<?php echo CONFIRM_DELETE; ?>')\" href=\"<?php echo ADMIN_URL; ?>" . $_POST['frmFormsetvalue'] . "-remote.php/delete/<?php echo \$doc['_id']; ?>/\" target=\"remote\"><img border=\"0\" src=\"<?php echo BASE_URL; ?>sulata/images/delete.png\" title=\"<?php echo DELETE_RECORD; ?>\"/></a>
                                                    <?php } ?>
                                            </td>
                                            <?php } ?>
                                            
";
/* $fieldsToShow .= "
  edit: {title: '',width: '2%',sorting:false,list:<?php echo \$editAccess; ?>},"; */

$fieldsToShow .= "<?php if ((\$editAccess == TRUE) || (\$deleteAccess == TRUE)) { ?>"
        . "\n<th style=\"width:10%\">&nbsp;</th>\n"
        . "<?php } ?>";

//$fieldsToShow = substr($fieldsToShow, 0, -1);


$fieldsToShowRemote = substr($fieldsToShowRemote, 0, -1);
$fieldsWithQuotes = substr($fieldsWithQuotes, 0, -1);

$fieldsArray = substr($fieldsArray, 0, -1);
$selectedFields = substr($selectedFields, 0, -1);


$viewPath = $appPath . $_POST['frmSubFolder'] . '/' . $_POST['frmFormsetvalue'] . '.php';
$viewCode = "
                                <form class=\"form-horizontal\" name=\"searchForm\" id=\"searchForm\" method=\"get\" action=\"\">
                                    <fieldset id=\"search-area1\">
                                        <label class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\"><i class=\"fa fa-search blue\"></i> Search by " . makeFieldLabel($_POST['frmSearch']) . "</label>
                                        <div class=\"col-xs-7 col-sm-10 col-md-10 col-lg-10\">
                                        <input id=\"q\" type=\"text\" value=\"\" name=\"q\" class=\"form-control\" autocomplete=\"off\">
                                        </div>
                                        <div class=\"col-xs-5 col-sm-2 col-md-2 col-lg-2\">
                                        <input id=\"Submit\" type=\"submit\" value=\"Search\" name=\"Submit\" class=\"btn btn-primary pull-right\">
                                        </div>
                                        <?php if(\$_GET['q']){?>
                                        <div class=\"lineSpacer clear\"></div>
                                         <div class=\"pull-right\"><a style=\"text-decoration:underline !important;\" href=\"<?php echo ADMIN_URL;?>" . $_POST['frmFormsetvalue'] . ".php\">Clear search.</a></div>
                                        <?php } ?>
                                    </fieldset>
                                </form>
                               
                                
                    <div class=\"lineSpacer clear\"></div>
                    <?php if (\$addAccess == 'true') { ?>
                    <div id=\"table-area\"><a href=\"" . $_POST['frmFormsetvalue'] . "-add.php\" class=\"btn btn-black\">Add new..</a></div>
                        <?php } ?>
                        <?php
                                    \$fieldsArray = array(" . $fieldsArray . ");
                                    suSort(\$fieldsArray);
                                    ?>
<!-- TABLE -->

   <table width=\"100%\" class=\"table table-hover table-bordered tbl\">
                                    <thead>
                                        <tr>
                                            <th style=\"width:5%\">
                                                Sr.
                                            </th>
                                          
                                           $fieldsToShow
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach (\$row as \$doc) { ?>
                                        <tr>
                                            <td>
                                                <?php echo \$sr = \$sr + 1; ?>.
                                            </td>
                                            $colData
                                           
                                        </tr>
                                    <?php } ?>

                                    </tbody>
                                </table>
<!-- /TABLE -->
                    <?php
                                
                                suPaginate();
                                ?>
                                <?php if (\$downloadAccessCSV == TRUE && \$numDocs > 0) { ?>
                                    <p>&nbsp;</p>
                                    <p><a target=\"remote\" href=\"<?php echo \$_SERVER['PHP_SELF']; ?>/stream-csv/\" class=\"btn btn-black pull-right\"><i class=\"fa fa-file-excel-o\"></i> Download CSV</a></p>
                                    <p>&nbsp;</p>
                                    <div class=\"clearfix\"></div>
                                <?php } ?>
                                <?php if (\$downloadAccessPDF == TRUE && \$numDocs > 0) { ?>
                                    <p>&nbsp;</p>
                                    <p><a target=\"remote\" href=\"<?php echo \$_SERVER['PHP_SELF']; ?>/stream-pdf/\" class=\"btn btn-black pull-right\"><i class=\"fa fa-file-pdf-o\"></i> Download PDF</a></p>
                                    <p>&nbsp;</p>
                                    <div class=\"clearfix\"></div>
                                <?php } ?>
";

$pageTitle = 'Manage ' . ucwords(str_replace('-', ' ', $_POST['frmFormsetvalue']));
$pageTitle = "\$pageName='" . $pageTitle . "';\$pageTitle='" . $pageTitle . "';";
$csvDownloadCode = "
\$col = new MongoCollection(\$db, '" . $_POST['table'] . "');

//Search
if (\$_GET['q'] != '') {
    \$criteria = array('" . $fieldPrefix . "__dbState' => 'Live', '" . $_POST['frmSearch'] . "' => new MongoRegex(\"/\" . \$_GET['q'] . \"/i\"));
} else {
    \$criteria = array('" . $fieldPrefix . "__dbState' => 'Live');
}
//Paginate
if (!\$_GET['start']) {
    \$_GET['start'] = 0;
}
if (!\$_GET['sr']) {
    \$sr = 0;
} else {
    \$sr = \$_GET['sr'];
}

\$selectedFields = array(" . $selectedFields . ");

//Default sort
\$sortOrder = array('" . $_POST['frmSearch'] . "_slug' => 1);
//Sort
if (\$_GET['sort'] != '') {
    if (\$_GET['sort'] == 'asc') {
        \$sortOrder = array(\$_GET['f'] => 1);
    } else {
        \$sortOrder = array(\$_GET['f'] => -1);
    }
    \$row = \$col->find(\$criteria,\$selectedFields)->sort(\$sortOrder)->limit(\$getSettings['page_size'])->skip(\$_GET['start']);
} else {
    \$row = \$col->find(\$criteria,\$selectedFields)->sort(\$sortOrder)->limit(\$getSettings['page_size'])->skip(\$_GET['start']);
}

\$numDocs = \$col->count(\$criteria,\$selectedFields);

//Download CSV
if (suSegment(1) == 'stream-csv' && \$downloadAccessCSV == TRUE) {
    \$allRows = \$col->find(\$criteria,\$selectedFields);
    \$outputFileName = '" . $_POST['frmFormsetvalue'] . ".csv';
    \$fieldsArray = array(" . $fieldsWithQuotes . ");
    \$headerArray = array(" . $csvHeaders . ");
    suMongoDbToCSV(\$fieldsArray, \$headerArray, \$outputFileName);
    exit;
}
//Download PDF
if (suSegment(1) == 'stream-pdf' && \$downloadAccessPDF == TRUE) {
    \$allRows = \$col->find(\$criteria,\$selectedFields);
    \$outputFileName = '" . $_POST['frmFormsetvalue'] . ".pdf';
    \$fieldsArray = array(" . $fieldsWithQuotes . ");
    \$headerArray = array(" . $csvHeaders . ");
    suMongoDbToPDF(\$fieldsArray, \$headerArray, \$outputFileName);
    exit;
}
";

//Write view code
$linksPlace = "<div class=\"pull-right\">
                                    <a href=\"<?php echo ADMIN_URL; ?>" . $_POST['frmFormsetvalue'] . "-cards.php\"><i class=\"fa fa-th-large\"></i></a>
                                </div>";
$viewCode = str_replace('[RAPID-CODE]', $viewCode, $template);
$viewCode = str_replace("#LINKS_PLACE#", $linksPlace, $viewCode);
$viewCode = str_replace("/* rapidSql */", $pageTitle . "\n" . $csvDownloadCode, $viewCode);
suWrite($viewPath, $viewCode);
//View code ends
?>
