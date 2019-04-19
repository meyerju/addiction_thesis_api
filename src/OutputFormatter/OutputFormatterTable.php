<?php

namespace App\OutputFormatter;

class OutputFormatterTable
{

    /**
     * Exemple :
     * {
     *  ["time", "0", "1", "2"],
     *  ["urge", "10", "11", "3"],
     *  ["consumption", "0", "1", "2"],
     * }
     * @param array $data
     * @return array
     * @throws \Exception
     */
    function format(array $data)
    {
        $dataFormatted = [];
        $time = ["time"];
        $name = $data[0]['name'];
        $line = [$name];
        foreach (range(0, 23) as $number) {
            array_push($time, $number);
            array_push($line, 0);
        }
        array_push($dataFormatted, $time);
        array_push($dataFormatted, $line);
        $index = 0;
        foreach ($data as $key => $groupData)
        {
            if($groupData['name'] !== $name){
                $index +=1;
                $name = $groupData['name'];
                $line = [$name];
                foreach (range(0, 23) as $number) {
                    array_push($line, 0);
                }
                array_push($dataFormatted, $line);
            }
            $indexHour = (int)$groupData['time'];
            $dataFormatted[$index+1][$indexHour] = (int)$groupData['value'];
        }
        return $dataFormatted;
    }
}