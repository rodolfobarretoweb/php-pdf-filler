<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("PhpPdfFiller.php");

$pdf = new PhpPdfFiller('file_example.pdf');
$pdf->writeFile(array('name' => 'Name 4'));
$pdf->writeFile(array('name' => 'Name 5'));
$pdf->showFile();