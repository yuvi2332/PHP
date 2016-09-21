<?php


// the below Code Will Download the Images from the Albums whose id is received in Argument

$arrContextOptions = array(                     // Parameters From File_get_content function
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);


$cur_date = date("d-m-Y");
$count = 0;
$imgurl = array();

if (isset($_REQUEST["albumid"])) {

$responseimage = $fb->get('/' . $_REQUEST['albumid'] . '/photos?fields=images', $_SESSION['access_token']);
$photos = $responseimage->getGraphEdge();


mkdir("./lib/downloads/" . $_REQUEST['name'] . $cur_date);

foreach ($photos as $photoitem) {
$count++;
$imgurl = $photoitem['images'][0]['source'];
$data = file_get_contents($imgurl, false, stream_context_create($arrContextOptions));
$fp = fopen('./lib/downloads/' . $_REQUEST['name'] . $cur_date . '/image' . $count . '.jpg', 'w');
fwrite($fp, $data);
fclose($fp);
}


$rootPath = realpath('./lib/downloads');

// Initialize archive object
$zip = new ZipArchive();
$zip->open("./lib/zipfiles/" . $_REQUEST['name'] . $cur_date . ".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
new RecursiveDirectoryIterator($rootPath),
RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
// Skip directories (they would be added automatically)
if (!$file->isDir()) {
// Get real and relative path for current file
$filePath = $file->getRealPath();
$relativePath = substr($filePath, strlen($rootPath) + 1);
// Add current file to archive
$zip->addFile($filePath, $relativePath);
}
}

// Zip archive will be created only after closing object
$zip->close();
$filename = $_REQUEST['name'];
$path = './lib/downloads/' . $_REQUEST['name'] . $cur_date;

Delete($path);

$zippath = './lib/zipfiles/' . $_REQUEST['name'] . $cur_date . '.zip';

header("Content-type: application/octet-stream");
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $filename . $cur_date . '.zip"');
readfile($zippath);
}


?>

<?php

function Delete($path)
{
    if (is_dir($path) === true) {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file) {
            Delete(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    } else if (is_file($path) === true) {
        return unlink($path);
    }

    return false;
}


?>
