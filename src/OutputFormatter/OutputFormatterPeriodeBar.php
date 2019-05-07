<?php

namespace App\OutputFormatter;

class OutputFormatterPeriodeBar
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
        $time = ["morning (6H to 12H)","afternoon (12H to 18H)","evening (18H to 00H)","night(00H to 6H)"];
        $name = $data[0]['name'];
        $line = [$name];
        foreach (range(0, 3) as $number) {
            array_push($line, 0);
        }
        array_push($dataFormatted, $line);
        $index =0;
        foreach ($data as $key => $groupData)
        {
            if($groupData['name'] !== $name){
                $name = $groupData['name'];
                $line = [$name];
                $index +=1;
                foreach (range(0, 3) as $number) {
                    array_push($line, 0);
                }
                array_push($dataFormatted, $line);
            }
            $indexHour = (int)$groupData['time'];
            if(6 < $indexHour &&  $indexHour<= 12){ //morning
                $dataFormatted[$index][1] += (int)$groupData['value'];
            }
            if(12 < $indexHour &&  $indexHour <= 18){ //afternoon
                $dataFormatted[$index][2] += (int)$groupData['value'];
            }
            if(18 < $indexHour &&  $indexHour <= 24){ //evening
                $dataFormatted[$index][3] += (int)$groupData['value'];
            } 
            if($indexHour <= 6){//night
                $dataFormatted[$index][4] += (int)$groupData['value'];
            }
        }

        return ["data" => ["columns"=> $dataFormatted, "type" => "bar"], "axis"=>["x" =>['label' => 'hours', 'type' => 'category', "categories" => $time], "y" =>['label' => 'nb of clicks']]];
    }
}