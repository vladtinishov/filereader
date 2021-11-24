<?php
require 'vendor/autoload.php';
require 'classes/TableFormat.php';
require 'classes/TableReader.php';
require 'classes/CreateDocument.php';
require 'classes/TableRender.php';
use PhpOffice\PhpWord\IOFactory;

$file = $_GET['file'];

$objReader = IOFactory::createReader('Word2007');
$phpObject = $objReader->load($file);
$sections = $phpObject->getSections();

$tableReader = new Classes\TableReader;
$data = $tableReader->tableRead($sections);

$tableFormater = new Classes\TableFormat;
$tableData = $tableFormater->getFormatedTable($data);

$table = new Classes\TableRender;
$table->renderTable($tableData, 2);

// if (isset($_GET['page']) && isset($_GET['file'])) {
//     $objReader = IOFactory::createReader('Word2007');
//     $phpObject = $objReader->load($_GET['file']);
//     $sections = $phpObject->getSections();

//     $tableReader = new Classes\TableReader;
//     $data = $tableReader->tableRead($sections);

//     $tableFormater = new Classes\TableFormat;
//     $tableData = $tableFormater->getFormatedTable($data);

//     $table = new Classes\TableRender;
//     $table->renderTable($tableData, $_GET['page'] + 1);
// }