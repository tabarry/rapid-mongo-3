<?php include('includes/include.php'); ?>
<?php

//print_array($_POST);

@mkdir('../' . $_POST['directory']);
//Copy folder
//function recurse_copy($src,$dst,$directory,$db,$db_user,$db_password)
recurse_copy('./blank-project', '../' . $_POST['directory'], $_POST['directory'], $_POST['db'], $_POST['db_user'], $_POST['db_password']);
//Create MongoDB
include('blank-project/db/db.php');
//Create MySQL database
$sql = "DROP DATABASE IF EXISTS " . $_POST['db'];
suQuery($sql) or die("1. " . suError());
$sql = "CREATE DATABASE " . $_POST['db'];
suQuery($sql) or die("2. " . suError());
$sql = "USE " . $_POST['db'];
suQuery($sql) or die("3. " . suError());
$sql = file_get_contents('./blank-project/db/db.sql');
$sql = trim($sql);
$sql = explode(';', $sql);
for ($i = 0; $i <= sizeof($sql); $i++) {
    //echo $sql[$i].'<br>'.'<br>';
    if (isset($sql[$i])) {
        if ($sql[$i] != '') {
            suQuery($sql[$i]) or die("4. " . suError());
        }
    }
}
//Drop unwanted tables and files
if ($_POST['sulata_faqs'] == 'drop') {
    echo $sql = "DROP TABLE IF EXISTS sulata_faqs";
    suQuery($sql) or die(suError());
    unlink('../' . $_POST['directory'] . '/_admin/faqs.php');
    unlink('../' . $_POST['directory'] . '/_admin/faqs-add.php');
    unlink('../' . $_POST['directory'] . '/_admin/faqs-update.php');
    unlink('../' . $_POST['directory'] . '/_admin/faqs-remote.php');
    unlink('../' . $_POST['directory'] . '/_admin/faqs-cards.php');

    $col = new MongoCollection($db, 'sulata_faqs');
    $col->drop();
}
if ($_POST['sulata_media_files'] == 'drop') {
    $sql = "DROP TABLE IF EXISTS sulata_media_categories";
    suQuery($sql) or die(suError());
    $col = new MongoCollection($db, 'sulata_media_categories');
    $col->drop();
    $sql = "DROP TABLE IF EXISTS sulata_media_files";
    suQuery($sql) or die(suError());
    unlink('../' . $_POST['directory'] . '/_admin/media-categories.php');
    unlink('../' . $_POST['directory'] . '/_admin/media-categories-add.php');
    unlink('../' . $_POST['directory'] . '/_admin/media-categories-update.php');
    unlink('../' . $_POST['directory'] . '/_admin/media-categories-remote.php');
    unlink('../' . $_POST['directory'] . '/_admin/media-categories-cards.php');

    unlink('../' . $_POST['directory'] . '/_admin/media-files.php');
    unlink('../' . $_POST['directory'] . '/_admin/media-files-add.php');
    unlink('../' . $_POST['directory'] . '/_admin/media-files-update.php');
    unlink('../' . $_POST['directory'] . '/_admin/media-files-remote.php');
    unlink('../' . $_POST['directory'] . '/_admin/media-files-cards.php');

    $col = new MongoCollection($db, 'sulata_media_files');
    $col->drop();
}

if ($_POST['sulata_pages'] == 'drop') {
    $sql = "DROP TABLE IF EXISTS sulata_pages";
    suQuery($sql) or die(suError());
    $col = new MongoCollection($db, 'sulata_pages');
    $col->drop();
    $sql = "DROP TABLE IF EXISTS sulata_headers";
    $col = new MongoCollection($db, 'sulata_headers');
    $col->drop();
    suQuery($sql) or die(suError());
    unlink('../' . $_POST['directory'] . '/_admin/pages.php');
    unlink('../' . $_POST['directory'] . '/_admin/pages-add.php');
    unlink('../' . $_POST['directory'] . '/_admin/pages-update.php');
    unlink('../' . $_POST['directory'] . '/_admin/pages-remote.php');
    unlink('../' . $_POST['directory'] . '/_admin/pages-cards.php');
    unlink('../' . $_POST['directory'] . '/_admin/headers.php');
    unlink('../' . $_POST['directory'] . '/_admin/headers-add.php');
    unlink('../' . $_POST['directory'] . '/_admin/headers-update.php');
    unlink('../' . $_POST['directory'] . '/_admin/headers-remote.php');
    unlink('../' . $_POST['directory'] . '/_admin/headers-cards.php');
}
if ($_POST['sulata_testimonials'] == 'drop') {
    $sql = "DROP TABLE IF EXISTS sulata_testimonials";
    suQuery($sql) or die(suError());
    unlink('../' . $_POST['directory'] . '/_admin/testimonials.php');
    unlink('../' . $_POST['directory'] . '/_admin/testimonials-add.php');
    unlink('../' . $_POST['directory'] . '/_admin/testimonials-update.php');
    unlink('../' . $_POST['directory'] . '/_admin/testimonials-remote.php');
    unlink('../' . $_POST['directory'] . '/_admin/testimonials-cards.php');

    $col = new MongoCollection($db, 'sulata_testimonials');
    $col->drop();
}
echo "
<script>
top.$('#result').html('Project created.');
</script>
";
?>