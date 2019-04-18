<?php

namespace App\OutputFormatter;

class OutputFormatterBar
{

    /**
     * On met un numero incrémenté en index et on met le nom du careCenter en name et sa dernière valeur en value (normalement,
     * il est cencé il y avoir qu'une seule valeur selon les critères 'from', 'to', et 'temporal field')
     * Exemple :
     * {
     *   xs: {
     *       'click1': 'x1',
     *       'click2': 'x2',
     *   },
     *   columns: [
     *       ['x1', 10, 30, 45, 50, 70, 100],
     *       ['x2', 30, 50, 75, 100, 120],
     *       ['click1', 30, 200, 100, 400, 150, 250],
     *       ['click2', 20, 180, 240, 100, 190]
     *   ],
     *   type: 'bar'
     * }
     * @param array $data
     * @return array
     * @throws \Exception
     */
    function format(array $data)
    {
        $columns = [];
        array_push($columns, ['x1']);
        array_push($columns, ['click1']);
        $xs = ['click1' =>'x1'];
        $index = 0;
        $name = $data[0]['name'];
        foreach ($data as $key => $groupData)
        {
           
            if($groupData['name'] !== $name){
                $index +=1;
                $name = $groupData['name'];
                $xs['click'.($index+1)]='x'.($index+1);
                array_push($columns, ['x'.($index+1)]);
                array_push($columns, ['click'.($index+1)]);
            }
            array_push($columns[$index*2], $groupData['date']);
            array_push($columns[$index*2+1], $groupData['value']);
        }
        return ["xs" => $xs, "columns"=> $columns, "type" => "bar"];
    }
}