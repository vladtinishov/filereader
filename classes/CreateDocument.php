<?php
namespace Classes;

use PhpOffice\PhpWord\IOFactory;

class CreateDocument
{
    public function getSection($phpWord)
    {
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Vladislav');
        $properties->setCompany('Hello, world Company');
        $properties->setTitle('My title');
        $properties->setDescription('My description');
        $properties->setCategory('My category');
        $properties->setLastModifiedBy('My name');
        $properties->setCreated(mktime(0, 0, 0, 3, 12, 2021));
        $properties->setModified(mktime(0, 0, 0, 3, 14, 2021));
        $properties->setSubject('My subject');
        $properties->setKeywords('my, key, word');

        return $phpWord;
    }
    public function createTable($tableData, $index, $phpWord)
    {
        $table = $this->getSection($phpWord)->addSection()->addTable(['borderSize' => '1', 'borderColor' => '808080']);

        $lessonTextStyle = [
            'size' => 10,
        ];

        $timeTextStyle = [
            'size' => 10,
            'color' => 'FF0000'
        ];

        $daysTextStyle = [
            'size' => 10,
            'color' => '02146B'
        ];
        $days = [
            '8:30-9:35',
            '8:45-9:50',
            '10:00-11:05',
            '11:30-12:35',
            '11:45-12:50',
            '13:00-14:05',
            '13:15-14:20',
            '14:30-15:35',
            '14:45-1550'
        ];
        $groupName = implode($tableData[$index][1]);
        if (strlen($groupName) > 1000) {
            $groupName = explode('/', $tableData[$index][1]);
        }
        for ($i = 0; $i < count($tableData[$index]); $i++) {
            $time = $i === 0 ? implode(' ', $tableData[0][$i])
                            : $this->formatTime(implode(' ', $tableData[1][$i]));
            $day = implode(' ', $tableData[0][$i]);
            $table->addRow();
            $table->addCell(1750, $this->daysStyle($day))->addText($day, $daysTextStyle);
            $table->addCell(1750, $this->daysStyle($day))->addText($time, $timeTextStyle);
            $table->addCell(5000, $this->daysStyle($day))->addText(implode(' ', $tableData[$index][$i]), $lessonTextStyle);
        }
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $files = [];
        $fileName = '';
        if (gettype($groupName) === 'array') {
            $fileName = 'dir/' . implode('', $groupName) . '.docx';
            // echo '<pre>';
            // var_dump($fileName);
            $objWriter->save($fileName);
            $files[] = $fileName;
        } else {
            $groupName = str_replace('/', '', $groupName);
            $groupName = 'dir/' . $groupName . '.docx';
            $objWriter->save($groupName);
            $files[] = $groupName;
        }
        return $files;
    }
    public function formatTime($time)
    {
        $time = str_replace(' ', '', $time);
        $timeArray = explode('-', $time);
        $formatedTime = '';
        if (strlen($time) === 0) return $formatedTime;
        for ($i=0; $i < count($timeArray); $i++) {
            if (strlen($timeArray[0]) === 3) {
                $formatedTime .= $timeArray[$i][0];
                $formatedTime .= ':';
                $formatedTime .= $timeArray[$i][1];
                $formatedTime .= $timeArray[$i][2];
            } else {
                $formatedTime .= $timeArray[$i][0];
                $formatedTime .= $timeArray[$i][1];
                $formatedTime .= ':';
                $formatedTime .= $timeArray[$i][2];
                $formatedTime .= $timeArray[$i][3];
            }
            if ($i !== count($timeArray) - 1) $formatedTime .= ' - ';
        }
        return $formatedTime;
    }
    public function daysStyle($day)
    {
        if ($day === '') return [];
        return ['borderTopSize' => 10];
    }
}