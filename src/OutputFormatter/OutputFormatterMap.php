<?php

namespace App\OutputFormatter;

class OutputFormatterMap
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
        $avgLatitude = 0;
        $avgLongitude = 0;
        $nb = 0;
        foreach ($data as $key => $groupData)
        {
            if($groupData["latitude"]!==null & $groupData["longitude"]!==null){
                $avgLatitude = ($avgLatitude*$nb + $groupData["latitude"])/($nb + 1);
                $avgLongitude = ($avgLongitude*$nb + $groupData["longitude"])/($nb + 1);
                $nb ++;
            }
        }
        
        return ["data" => $data, "center" => [$avgLatitude, $avgLongitude]];
    }
}