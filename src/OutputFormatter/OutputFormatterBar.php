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
        return [];
    }
}