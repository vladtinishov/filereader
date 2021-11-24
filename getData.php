<?php
require 'vendor/autoload.php';

require 'classes/TableFormat.php';
require 'classes/TableReader.php';
require 'classes/CreateDocument.php';
require 'classes/TableRender.php';
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

if (!empty($_GET['get_faculties'])) {
    getFaculties();
} else if (!empty($_GET['uploadData'])) {
    echo json_encode($_GET['uploadData']);
} else if (isset($_GET['timetableName'])) {
    $timetableName = $_GET['timetableName'];
    $filesPath = $_GET['path'];
    createFiles($timetableName, $filesPath);
} else if (isset($_GET['get_groups'])) {
    getGroups($_GET['get_groups']);
}


if (isset($_FILES['timetable']['name'])) {
    uploadFile();
}



function getFaculties() {
    $scan = scandir('uploads');
    $faculties = [];
    foreach($scan as $dir) {
        if (is_dir("uploads/$dir") && $dir[0] != '.') {
            $faculties[] = $dir;
        }
    }

    echo json_encode(['faculties' => $faculties]);
}

function uploadFile() {
    $uploadDir = 'uploadedTimetable/';
    $uploadFile = $uploadDir . basename($_FILES['timetable']['tmp_name'] . '.docx');
    echo  __DIR__ . '/' . $uploadFile;
    move_uploaded_file($_FILES['timetable']['tmp_name'], $uploadFile);
}

function createFiles($file, $directory) {
    /**
     * .docx file reader
     */
    $objReader = IOFactory::createReader('Word2007');
    /**
     * .docx file data
     */
    $phpObject = $objReader->load($file);
    array_map('unlink', glob('uploadedTimetable/*.docx'));
    array_map('unlink', glob('uploads/' . $directory . '/*.docx'));
    /**
     * table body
     */
    $body = '';
    /**
     * file sections
     */
    $sections = $phpObject->getSections();
    // /**
    //  * reading tables
    //  */
    $tableReader = new Classes\TableReader;
    $data = $tableReader->tableRead($sections);
    // /**
    //  * create document
    //  */
    $tableFormater = new Classes\TableFormat;
    $tableData = $tableFormater->getFormatedTable($data);
    $tableCreater = new Classes\CreateDocument;

    for ($i=2; $i < count($tableData); $i++) {
        $phpWord = new PhpWord();
        $section = $tableCreater->getSection($phpWord);
        $files = $tableCreater->createTable($tableData, $i, $section, $directory);
        foreach ($files as $file) {
            $dataToReturn['files'][] = $file;
        }
    }

    echo json_encode($dataToReturn['files']);
}

function getGroups($directory) {
    $directory = 'uploads/' . $directory;
    $scan = scandir($directory);
    $groups = [];
    foreach($scan as $dir) {
        if (is_dir($directory) && $dir[0] != '.') {
            $groups[] = $dir;
        }
    }

    echo json_encode(['groups' => $groups]);
}