<?php

include('includes/include.php');

set_time_limit(0);
if (!is_dir($sitePath)) {
    mkdir($sitePath)
            or die("
	       <script>
	       alert('Destination folder `$sitePath` does not exist.');
	       </script>
	       ");
    if (file_exists('files/access-denied.html')) {
        copy('files/access-denied.html', $sitePath . '/index.html');
    }
}
//Get template
$template1 = file_get_contents('template/template.php');
$template=  str_replace("#VIEW_PAGE#", "<?php echo ADMIN_URL;?>".$_POST['frmFormsetvalue'], $template1);

//Add section
$uploadCheck = '';
include('inc-add.php');
//Update section
$uploadCheck = '';
include('inc-update.php');
//View section
include('inc-view.php');
//Cards section
include('inc-cards.php');
//remote section
include('inc-remote.php');

echo "
<script>
top.$('#result').html(top.$('#result').html()+'" . $_POST['frmFormsetvalue'] . " formset created.<br/>');
</script>
";
?>