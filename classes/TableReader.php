<?php
namespace Classes;
use PhpOffice\PhpWord\Element\Table;
require 'vendor/autoload.php';

class TableReader
{
    public function tableRead($sections)
    {
        /**
         * table data
         */
        $data = [];
        /**
         * if cell is bigger then one cell, than value is true
         */
        $isLongCell = false;
        /**
         * count of cells for one lesson
         */
        $countOfCells = 0;
        /**
         * cell width in twips
         */
        $cellWidth = 0;
        /**
         * index of section that have need table
         */
        $index = 0;

        foreach ($sections as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                if ($element instanceof Table) {
                    if (array_search($element, $elements) > 1) {
                        $index = array_search($element, $elements);
                    }
                }
            }
            if ($elements[$index] instanceof Table) {
                foreach ($elements[$index]->getRows() as $row) {
                    $i = 0;
                    $column = [];
                    foreach ($row->getCells() as $cells) {
                        $cellText = [];
                        $cellsWidth = $cells->getWidth();
                        foreach ($cells->getElements() as $cell) {
                            foreach ($cell->getElements() as $element) {
                                $cellText[] = $element->getText();
                            }
                        }
                        if ($cellsWidth > 10000) $isLongCell = true;
                        else $isLongCell = false;
                        $currentCellsWidth = $cells->getWidth();
                        if ($cellWidth) {
                            if ($isLongCell) {
                                $countOfCells = intdiv($currentCellsWidth, $cellWidth);
                                for ($i=0; $i < $countOfCells - 1; $i++) {
                                    $column[] = $cellText;
                                }
                                $countOfCells = 0;
                            }
                            else if ($currentCellsWidth > $cellWidth + 200 && !$isLongCell) {
                                if ($cellText) {
                                    if ($cellText[0]) {
                                        $column[] = $cellText;
                                    }
                                }
                            }
                        }
                        $column[] = $cellText;
                        $i += 1;
                    }
                    if (count($data) === 1) {
                        $cellWidth = $elements[$index]->getRows()[1]->getCells()[2]->getWidth();
                    }
                    $data[] = $column;
                }
            }
            else echo get_class($elements[$index]);
        }
        return $data;
    }
}