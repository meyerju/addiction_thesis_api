<?php

namespace App\OutputFormatter;

class OutputFormatterBar
{

    /**
     * On met un numero incrémenté en index et on met le nom du careCenter en name et sa dernière valeur en value (normalement,
     * il est cencé il y avoir qu'une seule valeur selon les critères 'from', 'to', et 'temporal field')
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
     *               'type': 'category',
     *               'categories': ["2018-03-011", "2018-04-011", "2018-05-011"],
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
        dump($columns);
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
            dump($columns);
            dump($xs);
            array_push($xs[$index], $groupData['date']);
            array_push($columns[$index], (int)$groupData['value']);
        }
        dump($xs);
        dump($columns);
        return ["data" => ["columns"=> $columns, "type" => "bar"], "axis"=>["x" =>['type' => 'category', "categories" => array_values($xs)[0]]]];
    }
}