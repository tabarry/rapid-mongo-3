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

$colSize = round(85 / ($colSize - 1));
$csvHeaders = "";
$fieldsArray = "";
$selectedFields = "";
for ($i = 0; $i <= sizeof($_POST['frmShow']) - 1; $i++) {
    if ($_POST['frmShow'][$i] != $_POST['primary']) {
        if (strstr($_POST['frmShow'][$i], '_Date')) {

            $fieldsToLoop .= "\$" . $_POST['frmShow'][$i] . "[\$key] = \$value['" . $_POST['frmShow'][$i] . "'];\n";
            if ($_POST['frmShow'][$i] == $_POST['frmSearch']) {
                $tag = "h1";
                $suSubstr = "echo date('F d, Y', \$doc['" . $_POST['frmShow'][$i] . "']->sec);";
            } else {
                $tag = "p";
                $suSubstr = "echo date('F d, Y', \$doc['" . $_POST['frmShow'][$i] . "']->sec);";
            }
            $fieldsToShow .= "<label>" . makeFieldLabel($_POST['frmShow'][$i]) . "</label>\n
                              <{$tag}>
                              <?php
                                if (suUnstrip(\$doc['" . $_POST['frmShow'][$i] . "']) == '') {
                                    echo \"-\";
                                } else {
                                    {$suSubstr}
                                }
                                ?>
                                </{$tag}>\n";
        } else {
            if ($_POST['frmShow'][$i] == $_POST['frmSearch']) {
                $tag = "h1";
                $suSubstr = "echo suSubstr(suUnstrip(\$doc['" . $_POST['frmShow'][$i] . "']));";
            } else {
                $tag = "p";
                $suSubstr = "echo suUnstrip(\$doc['" . $_POST['frmShow'][$i] . "']);";
            }
            $fieldsToShow .= "<label>" . makeFieldLabel($_POST['frmShow'][$i]) . "</label>\n
                              <{$tag}>
                              <?php
                                if (suUnstrip(\$doc['" . $_POST['frmShow'][$i] . "']) == '') {
                                    echo \"-\";
                                } else {
                                    {$suSubstr}
                                }
                                ?>
                                </{$tag}>\n";
            $fieldsToLoop .= "\$" . $_POST['frmShow'][$i] . "[\$key] = \$value['" . $_POST['frmShow'][$i] . "'];\n";
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
$fieldsToShow1 .= "<?php if ((\$editAccess == TRUE) || (\$deleteAccess == TRUE)) { ?>"
        . "\n<th style=\"width:10%\">&nbsp;</th>\n"
        . "<?php } ?>";

//$fieldsToShow = substr($fieldsToShow, 0, -1);


$fieldsToShowRemote = substr($fieldsToShowRemote, 0, -1);
$fieldsWithQuotes = substr($fieldsWithQuotes, 0, -1);

$fieldsArray = substr($fieldsArray, 0, -1);
$selectedFields = substr($selectedFields, 0, -1);


$cardsPath = $appPath . $_POST['frmSubFolder'] . '/' . $_POST['frmFormsetvalue'] . '-cards.php';
$cardCode = "
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
<!-- CARDS -->
                                <div class=\"row\">
                                    <?php foreach (\$row as \$doc) { ?>
                                        <div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\" id=\"card_<?php echo \$doc['_id']; ?>\">
                                            <div class=\"card\">
                                                <?php if ((\$editAccess == TRUE) || (\$deleteAccess == TRUE)) { ?>

                                                    <header>
                                                        <?php if (\$editAccess == TRUE) { ?>

                                                            <a href=\"<?php echo ADMIN_URL; ?>" . $_POST['frmFormsetvalue'] . "-update.php/<?php echo \$doc['_id']; ?>/\"><i class=\"fa fa-edit\"></i></a>
                                                        <?php } ?>

                                                        <?php if (\$deleteAccess == TRUE) { ?>

                                                            <a onclick=\"return delById('card_<?php echo \$doc['_id']; ?>', '<?php echo CONFIRM_DELETE; ?>')\" href=\"<?php echo ADMIN_URL; ?>" . $_POST['frmFormsetvalue'] . "-remote.php/delete/<?php echo \$doc['_id']; ?>/\" target=\"remote\"><i class=\"fa fa-trash\"></i></a>
                                                        <?php } ?>

                                                    </header>
                                                <?php } ?>
                                                $fieldsToShow
                                                <div>&nbsp;</div>
                                            </div>

                                        </div>
                                    <?php } ?>
                                </div>
                                <!-- /CARDS -->                                    
                    <?php
                                
                                suPaginate();
                                ?>
                                <?php if (\$downloadAccessCSV == TRUE && \$numDocs > 0) { ?>
                                    <p>&nbsp;</p>
                                    <p><a target=\"remote\" href=\"<?php echo ADMIN_URL;?>" . $_POST['frmFormsetvalue'] . ".php/stream-csv/\" class=\"btn btn-black pull-right\"><i class=\"fa fa-file-excel-o\"></i> Download CSV</a></p>
                                    <p>&nbsp;</p>
                                    <div class=\"clearfix\"></div>
                                <?php } ?>
                                <?php if (\$downloadAccessPDF == TRUE && \$numDocs > 0) { ?>
                                    <p>&nbsp;</p>
                                    <p><a target=\"remote\" href=\"<?php echo ADMIN_URL;?>" . $_POST['frmFormsetvalue'] . ".php/stream-pdf/\" class=\"btn btn-black pull-right\"><i class=\"fa fa-file-pdf-o\"></i> Download PDF</a></p>
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

";

//Write card code
$linksPlace = "
<div class=\"pull-right\">
                                    <a href=\"<?php echo ADMIN_URL; ?>" . $_POST['frmFormsetvalue'] . ".php\"><i class=\"fa fa-table\"></i></a>
                                </div>";
$cardCode = str_replace('[RAPID-CODE]', $cardCode, $template);
$cardCode = str_replace("#LINKS_PLACE#", $linksPlace, $cardCode);
$cardCode = str_replace("/* rapidSql */", $pageTitle . "\n" . $csvDownloadCode, $cardCode);
suWrite($cardsPath, $cardCode);
//View card ends
?>
