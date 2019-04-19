<?php

namespace App\OutputFormatter;

class OutputFormatterTimePie
{

    /**
     * Exemple :
     * {
     *  urge:{
     *   columns: [
     *       ['data1', 30],
     *       ['data2', 120],
     *   ],
     *   type : 'pie',
     *   },
     * consumption:{
     *   columns: [
     *       ['data1', 30],
     *       ['data2', 120],
     *   ],
     *   type : 'pie',
    *   }
     *}
     * @param array $data
     * @return array
     * @throws \Exception
     */
    function format(array $data)
    {
        $dataFormatted = [];
        $name = $data[0]['name'];
        $dataFormatted[$name] = [["morning",0], ["afternoon",0], ["evening",0], ["night",0]];
        $index = 0;
        foreach ($data as $key => $groupData)
        {
            if($groupData['name'] !== $name){
                $index +=1;
                $name = $groupData['name'];
                $dataFormatted[$name] = [["morning",0], ["afternoon",0], ["evening",0], ["night",0]];
            }
            $indexHour = (int)$groupData['time'];
            if(6 < $indexHour &&  $indexHour<= 12){ //morning
                $dataFormatted[$name][0][1] += (int)$groupData['value'];
            }
            if(12 < $indexHour &&  $indexHour <= 18){ //afternoon
                $dataFormatted[$name][1][1] += (int)$groupData['value'];
            }
            if(18 < $indexHour &&  $indexHour <= 0){ //evening
                $dataFormatted[$name][2][1] += (int)$groupData['value'];
            }else{ //night
                $dataFormatted[$name][3][1] += (int)$groupData['value'];
            }
        }
        $data = [];
        foreach ($dataFormatted as $key => $line)
        {
            $data[$key] = ["data" => ["columns"=> $line, "type" => "pie"]];
        }
        return $data;
    }
}