<?php

namespace App\OutputFormatter;

class OutputFormatterProgress
{

    /**
     * Exemple :
     * {
     *  data: {
     *       "columns": [
     *           ["click1", 7, 5, 2],
     *           ["click2", 6, 9, 5],
     *       ],
     *       'type': "step"
     *       };  
     * }
     * @param array $data
     * @return array
     * @throws \Exception
     */
    function format(array $data, array $periode)
    {
        $nbPeriode = round((strtotime($periode[0]["end"])-strtotime($periode[0]["start"]))/ (60 * 60 * 24));
        $date = date('Y-m-d', strtotime($periode[0]["start"]. ' + 0 days'));
        $columns = [];
        $name = $data[0]['name'];
        array_push($columns, [$name]);
        $index = 0;
        foreach ($data as $key => $groupData)
        {
            if($groupData['name'] !== $name){
                $name = $groupData['name'];
                array_push($columns, [$name]);
            }
        }    

        foreach ($columns as $keyName => $groupName)
            {    
                foreach (range(0, $nbPeriode) as $number) {
                
                    $isIn = false;
                    $date = date('Y-m-d', strtotime($periode[0]["start"]. ' + '.$number.' days'));
                    foreach ($data as $key => $groupData)
                    {
                        if(($date == $groupData['date']) && ($groupName[0] == $groupData['name'])){
                            $isIn = true;
                        }
                    }
                if($isIn === true){
                    array_push($columns[$keyName], 0);
                 
                }else{
                    $index = sizeof($columns[$keyName])-1;
                    if($index === 0){
                        $value = 0;
                    }else{
                        $value = $columns[$keyName][$index];
                    }
                    array_push($columns[$keyName], $value+1);
                }
            }
                
        }
            
    return ["data" => ["columns"=> $columns, "type" => "step"]];
    }
}