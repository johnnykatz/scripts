<?php

//cuenta la cantidad de coincidencias que hay con la tabla personas 
//utilizando en campo DNI

ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "2000M");
set_time_limit(0);

$conn_string = "host=localhost port=5432 dbname=BigD_prueba user=postgres password=postgres";
$conn = pg_connect($conn_string);
$cont = 0; //cuenta la cantidad de personas que ya existen en la BD

echo "Procesando...";
if (($fichero = fopen("patentes.csv", "r")) !== FALSE) {
    $i = 0;
    while (($datos = fgetcsv($fichero, 1000, ";")) !== FALSE) {
        if ($i > 0) {
            if ($i % 10000 == 0) {
                echo "\n" . $i . " Lineas y procesando... ";
            }
            $dni = substr(substr(trim($datos[8]), 2), 0, -1);
            $sql = "select id from personas where numero_documento='$dni' limit 1";
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) == 1) {
                $cont+=1;
            }
        }
        $i++;
    }
}
pg_close($conn);
echo "\n*********";
echo "\nEl proceso termino con exito. " . $i . " Lineas procesadas";
echo "\n" . "Se encontraron  $cont coincidencias";
echo "\n*********";
?>	