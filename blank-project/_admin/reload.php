<?php
//ini_set('display_errors', 1);
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/connection.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');


//Check to stop page opening outside iframe
//Check referrer
//suCheckRef();
if ($_GET['type'] == 'chk') {
    $tbl = suDecrypt($_GET['tbl']);
    $f1 = suDecrypt($_GET['f1']);
    $f2 = suDecrypt($_GET['f2']);
    $tblb = suDecrypt($_GET['tblb']);
    $f1b = suDecrypt($_GET['f1b']);
    $f2b = suDecrypt($_GET['f2b']);
    $id = suDecrypt($_GET['id']);


    //State field
    $stateField = explode('__', $f1);
    $stateField = $stateField[0] . '__dbState';

    $stateField2 = explode('__', $f2b);
    $stateField2 = $stateField2[0] . '__dbState';

    $chkArr = array();
    //Get entered data

    if ($tblb != '') {
        $id = new MongoId($id);
        $col = new MongoCollection($db, $tbl);
        $criteria = array($stateField2 => 'Live', '_id' => $id);
        $fields = array($f2b => 1);
        $row = $col->findOne($criteria, $fields);
        $chkArr = array();
        sort($row[$f2b]);
        foreach ($row[$f2b] as $fieldValue) {
            $chkUid = suSlugifyName($fieldValue);
            array_push($chkArr, addslashes(suUnstrip($fieldValue)));
        }
    }

    //Build checkboxes
    $chkCol = new MongoCollection($db, $tbl);
    $chkCriteria = array($stateField => 'Live');
    $chkSort = array($f2 => 1);
    $chkFields = array($f2 => 1);
    $chkRows = $chkCol->find($chkCriteria, $chkFields)->sort($chkSort);



    foreach ($chkRows as $chkDoc) {
        $chkUid = suSlugifyName($chkDoc[$f2]);
        if (in_array($chkDoc[$f2], $chkArr)) {
            $display = "style='display:none'";
        } else {
            $display = '';
        }
        ?>


        <a <?php echo $display; ?> id="chk<?php echo $chkUid; ?>" href="javascript:;" class="underline" onclick="loadCheckbox('<?php echo $chkUid; ?>', '<?php echo addslashes(suUnstrip($chkDoc[$f2])); ?>', '<?php echo $f2b; ?>')" onmouseover="toggleCheckboxClass('over', '<?php echo $chkUid; ?>');" onmouseout="toggleCheckboxClass('out', '<?php echo $chkUid; ?>');"><i id="fa<?php echo $chkUid; ?>" class="fa fa-square-o"></i> <?php echo suUnstrip($chkDoc[$f2]); ?>.</a>


        <?php
    }
} else {
    $dd = "<option value='^'>Select..</option>";
    $tbl = suDecrypt($_GET['tbl']);
    $f1 = suDecrypt($_GET['f1']);
    $f2 = suDecrypt($_GET['f2']);
    $stateField = explode('__', $f2);
    $stateField = $stateField[0] . '__dbState';

    $col = new MongoCollection($db, $tbl);
    $criteria = array($stateField => 'Live');
    $sort = array($f2 => 1);
    $fields = array($f1 => 1,$f2 => 1);
    $row = $col->find($criteria, $fields)->sort($sort);
    foreach ($row as $doc) {
        $dd.="<option value='" . $doc[$f1] . "'>" . suUnstrip($doc[$f2]) . "</option>";
    }
    echo $dd;
}
?>