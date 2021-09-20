<?php

namespace Classes;
require 'vendor/autoload.php';
class TableRender
{
    public function renderTable($tableData, $index)
    {
        $timeFormat = new CreateDocument;
        echo '<table class="table table-striped">';
        for ($i=0; $i < count($tableData[0]); $i++) {
            echo '<tr>';
            echo '<td>';
            echo implode(' ', $tableData[0][$i]);
            echo '</td>';
            echo '<td>';
            echo $timeFormat->formatTime(implode(' ', $tableData[1][$i]));
            echo '</td>';
            echo '<td>';
            echo implode('', $tableData[$index][$i]);
            echo '</td>';
            echo '</tr>';
        }
        echo '<table>';
    }
}