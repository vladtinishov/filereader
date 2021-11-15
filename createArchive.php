<?php

require 'vendor/autoload.php';

require 'classes/TableFormat.php';
require 'classes/TableReader.php';
require 'classes/CreateDocument.php';
require 'classes/TableRender.php';

use Classes\TableReader;
use Classes\TableRender;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use \ZipArchive as ZipArchive;
$file = '';
$dataToReturn = [];

$tempFolder = ini_get('upload_tmp_dir');
if(isset($_FILES['file']['name'])){
    $filename = $_FILES['file']['name'];
    $location = "upload/$filename";
    $file = __DIR__ . '/' .$location;
    $dataToReturn['file'] = $file;
    $response = 0;
    if(move_uploaded_file($_FILES['file']['tmp_name'], $location)){
        $response = 1;
    }
}

/**
 * .docx file reader
 */
$objReader = IOFactory::createReader('Word2007');
/**
 * .docx file data
 */
$phpObject = $objReader->load($file);
/**
 * table body
 */
$body = '';
/**
 * file sections
 */
$sections = $phpObject->getSections();
/**
 * reading tables
 */
$tableReader = new Classes\TableReader;
$data = $tableReader->tableRead($sections);
/**
 * create document
 */
$tableFormater = new Classes\TableFormat;
$tableData = $tableFormater->getFormatedTable($data);
$tableCreater = new Classes\CreateDocument;

for ($i=2; $i < count($tableData); $i++) {
    $phpWord = new PhpWord();
    $section = $tableCreater->getSection($phpWord);
    $files = $tableCreater->createTable($tableData, $i, $section);
    foreach ($files as $file) {
        $dataToReturn['files'][] = $file;
    }
}

$dataToReturn['pages'] = count($tableData) - 2;

echo json_encode($dataToReturn);

?>