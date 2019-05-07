<?php

namespace App\OutputFormatter;

class OutputFormatterHeatMap
{

    /**
     * Exemple :
     * {
     *  data: {
     *       days:["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],    
     *       times = ["1a", "2a", "3a", "4a", "5a", "6a", "7a", "8a", "9a", "10a", "11a", "12a", "1p", "2p", "3p", "4p", "5p", "6p", "7p", "8p", "9p", "10p", "11p", "12p"],
     *  
     * }
     * @param array $data
     * @return array
     * @throws \Exception
     */
    function format(array $data,  array $periode)
    {
        $nbPeriode = round((strtotime($periode[0]["end"])-strtotime($periode[0]["start"]))/ (60 * 60 * 24));
        $days = [];

        $columns = [];
        $name = $data[0]['name'];
        $columns[$name] = [];
        
        foreach (range(0, $nbPeriode) as $number) {
            $date = date('Y-m-d', strtotime($periode[0]["start"]. ' + '.$number.' days'));
            $timestamp = strtotime($date);
            $day = date('D', $timestamp);
            array_push($days, $day." - ".$date);
            foreach (range(1, 24) as $hour) {
                array_push($columns[$name],["day"=>(int)$number+1, "hour"=>$hour, "value"=> 0]);
            }
        }

        $indexDay = 1;
        $day = $data[0]['date'];
        foreach ($data as $key => $groupData)
        {
            if(!array_key_exists($groupData['name'],$columns)){
                $day = $groupData['date'];
                $indexDay = 1;
                $name = $groupData['name'];
                $columns[$name] = [];
                foreach (range(0, $nbPeriode) as $number) {
                    foreach (range(1, 24) as $hour) {
                        array_push($columns[$name],["day"=>(int)$number+1, "hour"=>$hour, "value"=> 0]);
                    }
                }
            }
            if($day !== $groupData['date']){
                $day = $groupData['date'];
                $indexDay ++;
            }
            $hour = (int)$groupData['time'];
            if($hour === 0){
                $hour = 24;
            }
            $columns[$name][(int)($indexDay-1)*24+$hour-1]["value"] = (int)$groupData['value'];
        }
        return ["data" => $columns, "days" => $days];
    }
}