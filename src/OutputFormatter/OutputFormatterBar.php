<?php

namespace App\OutputFormatter;

class OutputFormatterBar
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
        $columns = [];
        $name = $data[0]['name'];
        array_push($columns, [$name]);
        $xs = [[]];
        $index = 0;
        foreach ($data as $key => $groupData)
        {
            if($groupData['name'] !== $name){
                $index +=1;
                $name = $groupData['name'];
                array_push($columns, [$name]);
                array_push($xs, []);
            }
            array_push($xs[$index], $groupData['date']);
            array_push($columns[$index], (int)$groupData['value']);
        }
        return ["data" => ["columns"=> $columns, "type" => "bar"], "axis"=>["x" =>['label' => 'tracking days', 'type' => 'category', "categories" => array_values($xs)[0]], "y" =>['label' => 'nb of clicks']]];
    }
}