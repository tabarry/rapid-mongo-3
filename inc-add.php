<?php

$autoCompleteCount = "";
$remoteCodeAutoInsert = "";
$remoteCodeAutoComplete = "";
$linksPlace = "";

//Add section starts
$addPath = $appPath . $_POST['frmSubFolder'] . '/' . $_POST['frmFormsetvalue'] . '-add.php';


for ($i = 0; $i <= sizeof($_POST['frmField']) - 1; $i++) {
    if ($_POST['frmType'][$i] == 'Textbox') {
        include('inc-add-textbox.php');
    } elseif ($_POST['frmType'][$i] == 'URL') {
            echo "<script>alert('" . $_POST['frmType'][$i] . "')</script>";
        include('inc-add-textbox.php');
    } elseif ($_POST['frmType'][$i] == 'IP') {
        include('inc-add-textbox.php');
    } elseif ($_POST['frmType'][$i] == 'Credit Card') {
        include('inc-add-integerbox.php');
    } elseif ($_POST['frmType'][$i] == 'Password') {
        include('inc-add-passwordbox.php');
    } elseif ($_POST['frmType'][$i] == 'Email') {
        include('inc-add-textbox.php');
    } elseif ($_POST['frmType'][$i] == 'Date') {
        include('inc-add-datebox.php');
    } elseif ($_POST['frmType'][$i] == 'Integer') {
        include('inc-add-integerbox.php');
    } elseif ($_POST['frmType'][$i] == 'Double') {
        include('inc-add-integerbox.php');
    } elseif ($_POST['frmType'][$i] == 'Float') {
        include('inc-add-integerbox.php');
    } elseif ($_POST['frmType'][$i] == 'Textarea') {
        include('inc-add-textarea.php');
    } elseif ($_POST['frmType'][$i] == 'HTML Area') {
        include('inc-add-htmlarea.php');
    } elseif ($_POST['frmType'][$i] == 'Picture field') {
        include('inc-add-picturebox.php');
    } elseif ($_POST['frmType'][$i] == 'File field') {
        include('inc-add-filebox.php');
    } elseif ($_POST['frmType'][$i] == 'Attachment field') {
        include('inc-add-attachmentbox.php');
    } elseif ($_POST['frmType'][$i] == 'Enum') {
        include('inc-add-enumbox.php');
    } elseif ($_POST['frmType'][$i] == 'Dropdown from DB') {
        include('inc-add-dbdropdownbox.php');
    } elseif ($_POST['frmType'][$i] == 'Autocomplete') {
        include('inc-add-autocompletebox.php');
    } else {
        include('inc-add-textbox.php');
    }
}


if ($multipart == TRUE) {
    $multipart = 'enctype="multipart/form-data"';
} else {
    $multipart = '';
}
$pageTitle = 'Add ' . ucwords(str_replace('-', ' ', $_POST['frmFormsetvalue']));
$pageTitle = "\$pageName='" . $pageTitle . "';\$pageTitle='" . $pageTitle . "';";
$addCodeStart = '
        <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>' . $_POST['frmFormsetvalue'] . '-remote.php/add/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" ' . $multipart . '>
            
            <div class="gallery clearfix">';

$addCodeEnd = "
        <!--Child Table Place-->
        </div>
        <div class=\"lineSpacer clear\"></div>
        <p>
        <?php
        \$arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-primary pull-right');
        echo suInput('input', \$arg);
        ?>                              
        </p>
        <p>&nbsp;</p>
        </form>
";
//If child table checkboxes
include('inc-checkbox.php');
$addCode = $addCodeStart . $addCode . $addCodeEnd;
//Write add code
$linksPlace = "
<div class=\"pull-right\">
                                    <a href=\"<?php echo ADMIN_URL; ?>" . $_POST['frmFormsetvalue'] . "-cards.php\"><i class=\"fa fa-th-large\"></i></a>
                                    <a href=\"<?php echo ADMIN_URL; ?>" . $_POST['frmFormsetvalue'] . ".php\"><i class=\"fa fa-table\"></i></a>
                                </div>";

$addCode = str_replace('[RAPID-CODE]', $addCode, $template);
$addCode = str_replace("#LINKS_PLACE#", $linksPlace, $addCode);
$addCode = str_replace("/* rapidSql */", $pageTitle, $addCode);
$addCode = str_replace('<!--Child Table Place-->', $addCheckBox, $addCode);
suWrite($addPath, $addCode);
//Add section ends
?>
