<?php
if (!file_exists('cache')) {
    mkdir('cache', 0777, true);
}
//generate random file name
$randFileName = 'cache/'.$_POST['file_name'];
$jpgFileName = $randFileName.".jpg";
$imageData = base64_decode($_POST['base64ImageContent']);
$source = imagecreatefromstring($imageData);
$rotate = imagerotate($source, 0, 0); // if want to rotate the image
$imageSave = imagejpeg($rotate,$jpgFileName,100);
imagedestroy($source);

file_put_contents($randFileName, $_POST['base64ImageContent']);
ob_start();
ob_clean();

header('Content-type: text/plain');
echo $_POST['file_name'].".jpg";
ob_end_flush();
exit();

?>