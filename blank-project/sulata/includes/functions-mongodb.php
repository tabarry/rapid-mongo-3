<?php

/*
 * MongoDB Functions
 */

/* Build dropdown pagination */
if (!function_exists('suPaginate')) {

    function suPaginate($cssClass = 'paginate') {
                                    //global $getSettings['page_size'];
                                    global $getSettings, $sr, $numDocs;
                                    
                                    $totalRecs = $numDocs;
                                    $opt = '';
                                    if ($totalRecs > 0) {
                                        if ($totalRecs > $getSettings['page_size']) {
                                            for ($i = 1; $i <= ceil($totalRecs / $getSettings['page_size']); $i++) {
                                                $j = $i - 1;
                                                $sr = $getSettings['page_size'] * $j;
                                                if ($_GET['start'] / $getSettings['page_size'] == $j) {
                                                    $sel = " selected='selected'";
                                                } else {
                                                    $sel = "";
                                                }
                                                $opt.= "<option {$sel} value='" . $_SERVER['PHP_SELF'] . "?sr=" . $sr . "&q=" . $_GET['q'] . "&f=".$_GET['f']."&sort=".$_GET['sort']."&start=" . ($getSettings['page_size'] * $j) . "'>$i</option>";
                                            }
                                            echo "<div style=\"height:30px\">Go to page: <select class='{$cssClass}' onchange=\"window.location.href = this.value\">{$opt}></select></div>";
                                        }
                                    } else {
                                        if ($_GET['q'] == '') {
                                            echo '<div class="blue">' . RECORD_NOT_FOUND . '</div>';
                                        } else {
                                            echo '<div class="blue">' . SEARCH_NOT_FOUND . '</div>';
                                        }
                                    }
                                }
}

/* Make label from field name */
if (!function_exists('makeFieldLabel')) {

    function makeFieldLabel($fld) {
        if (strstr($fld, "__")) {
            $fld = explode("__", $fld);
            $fld = $fld[1];
        } else {
            $fld = $fld;
        }

        return str_replace('_', ' ', $fld);
    }

}
/* Build dropdown for sorting */
if (!function_exists('suSort')) {

    function suSort($fieldsArray, $cssClass = 'paginate') {
        $opt = "<option value='".$_SERVER['PHP_SELF']."'>Sort by..</option>";
        for ($i = 0; $i <= sizeof($fieldsArray) - 1; $i++) {
            if ($_GET['f'] == $fieldsArray[$i] && $_GET['sort'] == 'asc') {
                $sel1 = " selected='selected' ";
            } else {
                $sel1 = '';
            }
            if ($_GET['f'] == $fieldsArray[$i] && $_GET['sort'] == 'desc') {
                $sel2 = " selected='selected' ";
            } else {
                $sel2 = '';
            }
            $opt.="<option $sel1 value=\"" . $_SERVER['PHP_SELF'] . "?f=" . $fieldsArray[$i] . "&sort=asc&q=" . $_GET['q'] . "\">" . str_replace('slug','',makeFieldLabel($fieldsArray[$i])) . " Asc</option>";
            $opt.="<option $sel2 value=\"" . $_SERVER['PHP_SELF'] . "?f=" . $fieldsArray[$i] . "&sort=desc&q=" . $_GET['q'] . "\">" . str_replace('slug','',makeFieldLabel($fieldsArray[$i])) . " Desc</option>";
        }

        echo "<div class='paginateWrapper'><select class=\"{$cssClass}\" onchange=\"window.location.href=this.value\">{$opt}</select></div>";
    }

}
