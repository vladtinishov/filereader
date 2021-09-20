<?php

namespace Classes;

class TableFormat
{
    public function flip_row_col_array($array)
    {
        $out = array();
        foreach ($array as  $rowkey => $row) {
            foreach($row as $colkey => $col){
                $out[$colkey][$rowkey]=$col;
            }
        }
        return $out;
    }
    public function addEmptyCell($array)
    {
        for ($i=0; $i < count($array); $i++) {
            if ($i > 2) {
                array_unshift($array[$i], []);
            }
        }
        return $array;
    }
    public function getFormatedTable($array)
    {
        $flipedArray = $this->flip_row_col_array($array);
        return $this->addEmptyCell($flipedArray);
    }
}