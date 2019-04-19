<?php

namespace App\OutputFormatter;

class OutputFormatterLine
{

    /**
     * Exemple :
     * {
     *  data: {
     *       "xs": {
     *          setosa: 'setosa_x',
     *          versicolor: 'versicolor_x',
     *       },
     *       "columns": [
     *          ["setosa_x", 3.5, 3.0, 3.2, 3.1, 3.6, 3.9, 3.4, 3.4, 2.9, 3.1, 3.7, 3.4, 3.0, 3.0, 4.0, 4.4, 3.9, 3.5, 3.8, 3.8, 3.4, 3.7, 3.6, 3.3, 3.4, 3.0, 3.4, 3.5, 3.4, 3.2, 3.1, 3.4, 4.1, 4.2, 3.1, 3.2, 3.5, 3.6, 3.0, 3.4, 3.5, 2.3, 3.2, 3.5, 3.8, 3.0, 3.8, 3.2, 3.7, 3.3],
     *          ["versicolor_x", 3.2, 3.2, 3.1, 2.3, 2.8, 2.8, 3.3, 2.4, 2.9, 2.7, 2.0, 3.0, 2.2, 2.9, 2.9, 3.1, 3.0, 2.7, 2.2, 2.5, 3.2, 2.8, 2.5, 2.8, 2.9, 3.0, 2.8, 3.0, 2.9, 2.6, 2.4, 2.4, 2.7, 2.7, 3.0, 3.4, 3.1, 2.3, 3.0, 2.5, 2.6, 3.0, 2.6, 2.3, 2.7, 3.0, 2.9, 2.9, 2.5, 2.8],
     *          ["setosa", 0.2, 0.2, 0.2, 0.2, 0.2, 0.4, 0.3, 0.2, 0.2, 0.1, 0.2, 0.2, 0.1, 0.1, 0.2, 0.4, 0.4, 0.3, 0.3, 0.3, 0.2, 0.4, 0.2, 0.5, 0.2, 0.2, 0.4, 0.2, 0.2, 0.2, 0.2, 0.4, 0.1, 0.2, 0.2, 0.2, 0.2, 0.1, 0.2, 0.2, 0.3, 0.3, 0.2, 0.6, 0.4, 0.3, 0.2, 0.2, 0.2, 0.2],
     *          ["versicolor", 1.4, 1.5, 1.5, 1.3, 1.5, 1.3, 1.6, 1.0, 1.3, 1.4, 1.0, 1.5, 1.0, 1.4, 1.3, 1.4, 1.5, 1.0, 1.5, 1.1, 1.8, 1.3, 1.5, 1.2, 1.3, 1.4, 1.4, 1.7, 1.5, 1.0, 1.1, 1.0, 1.2, 1.6, 1.5, 1.6, 1.5, 1.3, 1.3, 1.3, 1.2, 1.4, 1.2, 1.0, 1.3, 1.2, 1.3, 1.3, 1.1, 1.3],
        
     *       ],
     *       'type': "scatter"
     *       };
     *  axis: {
     *       'x': {
     *               tick: {
     *                   fit: false
     *               }
     *           }     
     *       }       
     * }
     * @param array $data
     * @return array
     * @throws \Exception
     */
    function format(array $data)
    {
        $columns = [];
        $name = $data[0]['name'];
        array_push($columns, [$name]);
        array_push($columns, [$name."_x"]);
        
        $xs = [$name => $name."_x"];
        dump($columns);
        dump($xs);
        $index = 0;
        foreach ($data as $key => $groupData)
        {
           
            if($groupData['name'] !== $name){
                $index +=1;
                $name = $groupData['name'];
                $xs[$name]= $name."_x";
                array_push($columns, [$name]);
                array_push($columns, [$name."_x"]);
                dump($columns);
                dump($xs);
            }
            array_push($columns[$index*2], (float)$groupData['time']);
            array_push($columns[$index*2+1], $groupData['date']);
        }
        dump($columns);
        dump($xs);
        return ["data" => ["xs" => $xs, "columns"=> $columns, "type" => "scatter"], "axis"=>["x" =>['label' => 'tracking days', 'type'=> 'category','tick' => ["fit" => false]], "y" =>['label' => 'hours of the day']]];
    }
}