<?php

/* DB FUNCTIONS */

function suQuery($sql) {
    global $link;
    return mysqli_query($link, $sql);
}

function suError() {
    global $link;
    return mysqli_error($link);
}

function suErrorNo() {
    global $link;
    return mysqli_errno($link);
}

function suFetch($result) {
    return mysqli_fetch_array($result);
}

function suFree($result) {
    return mysqli_free_result($result);
}

function suClose($link) {
    return mysqli_close($link);
}

function suNumRows($result) {
    return mysqli_num_rows($result);
}

function suInsertId() {
    global $link;
    return mysqli_insert_id($link);
}

/* // */

//Make form label
function makeFieldLabel($fld) {
    if (strstr($fld, "__")) {
        $fld = explode("__", $fld);
        $fld = $fld[1];
    } else {
        $fld = $fld;
    }

    return str_replace('_', ' ', $fld);
}

//Print array
function print_array($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

//Make fields drop down
function makeFieldType($comments, $fld_name, $fld_type, $ddValue) {
    $sel = '';
    if (stristr($fld_type, "int(")) {
        if (strstr($comments, ",")) {
            $sel = "Dropdown from DB";
        } else {
            $sel = "Integer";
        }
    } elseif (stristr($fld_type, "double")) {
        $sel = "Double";
    } elseif (stristr($fld_type, "float")) {
        $sel = "Float";
    } elseif (stristr($fld_type, "text")) {
        $sel = "Textarea";
    } elseif (stristr($fld_type, "date")) {
        $sel = "Date";
    } elseif (stristr($fld_type, "enum")) {
        $sel = "Enum";
    } elseif (stristr($fld_type, "varchar(")) {
        if (strstr($comments, ",")) {
            $sel = "Dropdown from DB";
        } else {
            if (stristr($fld_name, "email")) {
                $sel = "Email";
            } elseif (stristr($fld_name, "file")) {
                $sel = "File field";
            } elseif (stristr($fld_name, "attachment")) {
                $sel = "Attachment field";
            } elseif (stristr($fld_name, "picture")) {
                $sel = "Picture field";
            } elseif (stristr($fld_name, "password")) {
                $sel = "Password";
            } else {
                $sel = "Textbox";
            }
        }
    } else {
        $sel = "Textbox";
    }
    if (stristr($fld_name, "__date")) {
        $sel = "Date";
    }

    if ($sel == $ddValue) {
        echo "selected=\"selected\"";
    } else {
        echo "";
    }
}

//explode and extract
function explodeExtract($str, $toExplode, $excludeArray) {
    $str = explode($toExplode, $str);
    for ($i = 0; $i <= sizeof($str) - 1; $i++) {
        if ($i != $excludeArray) {
            $pageset.=$str[$i] . $toExplode;
        }
    }
    $pageset = substr($pageset, 0, -1);
    return $pageset;
}

//Build directory
function buildWww($path, $selected = "") {

    if ($handle = opendir($path)) {

        /* This is the correct way to loop over the directory. */
        while (false !== ($entry = readdir($handle))) {
            if (!strstr($entry, ".")) {
                if ($entry == $_SESSION[SESSION_PREFIX . 'folder']) {
                    $sel = "selected";
                } else {
                    $sel = "";
                }
                $opt.= "<option $sel>$entry</option>\n";
            }
        }

        closedir($handle);
    }
    return $opt;
}

//Make table DD
function tableDd($table, $select) {

    $sql = "SHOW TABLES FROM " . $table;
    $rs = suQuery($sql) or die(suError());
    while ($row = suFetch($rs)) {
        $sql2 = "SHOW FULL FIELDS FROM " . $row[0];
        $rs2 = suQuery($sql2) or die(suError());
        while ($row2 = suFetch($rs2)) {

            if (trim($row2[0]) == trim($select)) {
                $sel = "selected=\"selected\"";
            } else {
                $sel = "0";
            }
            if (strstr($row2[0], '__ID')) {
                $row2[0] = '_id';
            }
            $opt.="<option $sel>" . $row[0] . "." . $row2[0] . "</option>";
        }suFree($rs2);
    }suFree($rs);
    return $opt;
}

//iframe
function suIframe($name = 'remote') {
    global $debug;
    if ($debug == TRUE) {
        $display = 'block';
    } else {
        $display = 'none';
    }

    echo "
	<div style='height:35px;line-height:35px;font-family:Arial;color:#000;background-color:#FFCD9B;display:{$display};'>&nbsp;This is debug window. Set \$debug=FALSE in config.php file to hide it.</div>
	<iframe frameborder='0' name='{$name}' id='{$name}' width='100%' height='300' style='display:{$display};border:1px solid #FFCD9B;'/>
	Sorry, your browser does not support frames.
	</iframe>
	";
}

//Write to file
function suWrite($path, $content) {
    global $backupPath;
    $filename = $path;
    global $appPath;
    global $date_time;

    if (!is_dir($backupPath)) {
        $backupPath;
        mkdir($backupPath);
    }
    if (file_exists($filename) == true) {
        $pageName = preg_replace("/.*\/\w+\//", "", $filename);
        @copy($filename, $backupPath . date('Y-m-d-H-i-s') . "-backup." . $pageName);
    }

    @unlink($filename);
    $handle = @fopen($filename, 'w+');
//	or die("
//	       <script>
//	       alert('Destination folder `$appPath` does not exist.');
//	       </script>
//	       ");

    fwrite($handle, $content);
    fclose($handle);
}

//Copy blank project
//directory = www folder name
function recurse_copy($src, $dst, $directory, $db, $db_user, $db_password) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file, $directory, $db, $db_user, $db_password);
            } else {
                if ($file == 'config.php') {
                    $content = file_get_contents($src . '/' . $file);
                    $content = str_replace("#DB_NAME#", $db, $content);
                    $content = str_replace("#DB_USER#", $db_user, $content);
                    $content = str_replace("#DB_PASSWORD#", $db_password, $content);
                    $content = str_replace("#SITE_FOLDER#", $directory, $content);
                    //Generate session prefix
                    $uid = uniqid();
                    $uid = substr($uid, 4, 3);
                    $uid = $uid . '_';
                    $content = str_replace("#SESSION_PREFIX#", $uid, $content);
                    //Version
                    $content = str_replace("#VERSION#", VERSION, $content);
                    $content = str_replace("#DATE#", date('M d, Y'), $content);
                    suWrite($dst . '/' . $file, $content);
                } else {
                    @unlink($dst . '/' . $file);
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
    }
    closedir($dir);
}

?>
