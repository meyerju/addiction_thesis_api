<?php

namespace App\OutputFormatter;

class OutputFormatterReversedLine
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
        $index=0;
        $columns = [];
        $name = $data[0]['name'];
        array_push($columns, ["name" => $name, "data"=> []]);
        
        foreach (range(0, $nbPeriode) as $number) {
            $date = date('Y-m-d', strtotime($periode[0]["start"]. ' + '.$number.' days'));
            $timestamp = strtotime($date);
            $day = date('D', $timestamp);
            array_push($days, $day." - ".$date);
            foreach (range(0,23) as $hour) {
                array_push($columns[$index]["data"],[$hour,(int)$number+1, 0]);
            }
        }

        $indexHour = 1;
        $time = $data[0]['date'];
        foreach ($data as $key => $groupData)
        {
            if($groupData['name'] !== $columns[$index]["name"]){
                $time = $groupData['date'];
                $index ++;
                $indexHour = 1;
                $name = $groupData['name'];
                array_push($columns, ["name" => $name, "data"=> []]);
                foreach (range(0, $nbPeriode) as $number) {
                    foreach (range(0, 23) as $hour) {
                        array_push($columns[$index]["data"],[$hour, (int)$number+1, 0]);
                    }
                }
            }
            if($time !== $groupData['date']){
                $time = $groupData['date'];
                $indexHour ++;
            }
            $hour = (float)$groupData['time'];
            if($hour === 0){
                $hour = 24;
            }
            $value = (int)$groupData['value'];
            if($value === 0){
                $value = 1;
            }
            $columns[$index]["data"][(int)$indexHour*(int)$hour] = [$hour, $indexHour, $value%4];
        }
        return ["data" => $columns, "days" => $days];
    }
}