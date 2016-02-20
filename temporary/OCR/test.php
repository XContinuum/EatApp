<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once('TR/TesseractOCR/TesseractOCR.php');
$tesseract = new TesseractOCR('images/img.jpg');
$tesseract->setTempDir('/var/www/dir_name/imgRead/');
echo $tesseract->recognize();
?>
