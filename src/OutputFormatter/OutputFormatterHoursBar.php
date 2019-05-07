<?php

namespace App\OutputFormatter;

class OutputFormatterHoursBar
{
     /**
     * Exemple :
     * {
     *  data: {
     *       "columns": [
     *           ["click1", 7, 5, 2],
     *           ["click2", 6, 9, 5],
     *       ],
     *       'type': "bar"
     *       };
     *  axis: {
     *       'x': {
     *               'label': "heloo",
     *               'type': 'category',
     *               'categories': ["2018-03-011", "2018-04-011", "2018-05-011"],
     *           }    
     *       'y': {
     *               'label': "heloo", 
     *           }
     *       }       
     * }
     * @param array $data
     * @return array
     * @throws \Exception
     */
    function format(array $data)
    {
        $dataFormatted = [];
        $time = [];
        $name = $data[0]['name'];
        $line = [$name];
        foreach (range(0, 23) as $number) {
            array_push($time, $number);
            array_push($line, 0);
        }
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
            $dataFormatted[$index][$indexHour+1] = (int)$groupData['value'];
        }

        return ["data" => ["columns"=> $dataFormatted, "type" => "bar"], "axis"=>["x" =>['label' => 'hours', 'type' => 'category', "categories" => $time], "y" =>['label' => 'nb of clicks']]];
    }
}