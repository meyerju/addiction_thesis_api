<?php

namespace App\OutputFormatter;

class OutputFormatterPieMap
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
        foreach ($data as $key => $groupData)
        {
            if(((int)$groupData["latitude"] === 0)||((int)$groupData["longitude"] === 0)){
                continue;
            }
            $name = $groupData['name'];
            if(array_search($name,array_keys($dataFormatted)) === false){
               $dataFormatted[$name] = [];
            }
            $lat= sprintf("%.3f", $groupData["latitude"]);
            $long = sprintf("%.3f", $groupData["longitude"]);
            $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&key=AIzaSyBOtEvQWbAb5KaTA7puxO7PbE7zYw8bUvI";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_ENCODING, "");
            $curlData = curl_exec($curl);
            curl_close($curl);

            $address = json_decode($curlData);
            $addressResult = $address->{'results'};
            $addressResultStreet = array_values($addressResult)[0]->{'address_components'}[1]->{'short_name'};
            if(array_search($addressResultStreet,array_keys($dataFormatted[$name])) !== false){
                $dataFormatted[$name][$addressResultStreet] =  $dataFormatted[$name][$addressResultStreet] + 1;
            }else{
                $dataFormatted[$name][$addressResultStreet] = 1;
            }
        }
        $data = [];
        foreach ($dataFormatted as $key => $line)
        {
            $columns = [];
            foreach ($line as $street => $number)
            {
                array_push($columns, [$street, $number]);
            }
            $data[$key] = ["data" => ["columns"=> $columns, "type" => "pie"]];
        }
        return $data;
    }
}