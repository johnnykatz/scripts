<?php

ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "2000M");
set_time_limit(0);

$conn_string = "host=localhost port=5432 dbname=BigD_prueba user=postgres password=postgres";
$conn = pg_connect($conn_string);

//parametros
$id_localidad = 10;
$id_usuario = 1;
$tipo_domicilio_id = 1;
$fuente_datos_id = 1;


echo "Procesando...";
if (($fichero = fopen("samsa.csv", "r")) !== FALSE) {
    pg_query($conn, 'BEGIN work;');
    $i = 0;
    while (($datos = fgetcsv($fichero, 1000, ";")) !== FALSE) {
        if ($i > 0) {
            if ($i % 10000 == 0) {
                echo "\n" . $i . " Lineas y procesando... ";
            }
            $partida = trim($datos[7]);
            $result = pg_query("select persona_id from domicilios where partida='$partida' limit 1");
            if (pg_num_rows($result) > 0) {
                //si existe un domicilio con esta partida, tomo el id de persona y creo uno nuevo
                //con los nuevos datos y seteo principal=true validado=false
                //fuente de datos= samsa
                $id_persona = pg_fetch_row($result);
                $id_persona = $id_persona[0];
                $calle = preg_replace("/\(?[0-9](.*?)\)/i", "", $datos[14]);
//                $calle = explode(")", ($datos[14]));

                $result = pg_query($conn, "select nextval('domicilios_id_seq')");
                $id = pg_fetch_row($result);
                $id = $id[0];

                $sql = "INSERT INTO domicilios ("
                        . "id,"
                        . "seccion,"
                        . "chacra,"
                        . "manzana,"
                        . "parcela,"
                        . "lote,"
                        . "calle,"
                        . "numero,"
                        . "localidad_id,"
                        . "tipo_domicilio_id,"
                        . "principal,"
                        . "validado,"
                        . "fuente_datos_id,"
                        . "persona_id,"
                        . "creado_por,"
                        . "actualizado_por,"
                        . "creado,"
                        . "actualizado,"
                        . "partida)"
                        . "VALUES("
                        . "$id,'"
                        . trim($datos[9]) . "','"
                        . trim($datos[10]) . "','"
                        . trim($datos[11]) . "','"
                        . trim($datos[13]) . "','"
                        . trim($datos[12]) . "','"
                        . trim($calle) . "','"
                        . trim($datos[15]) . "',"
                        . "$id_localidad,"
                        . $tipo_domicilio_id . ","
                        . "true,"
                        . "false,"
                        . "$fuente_datos_id,"
                        . "$id_persona,"
                        . "$id_usuario,"
                        . "$id_usuario,'"
                        . date("Y-m-d H:i:s") . "','"
                        . date("Y-m-d H:i:s") . "','"
                        . trim($partida) . "')";

                $result = pg_query($conn, $sql);

                if (!$result) {
                    pg_query($conn, 'Rollback');
                    pg_close($conn);
                    echo $sql;
                    echo $i;
                    exit();
                }
            }
        }
        $i++;
    }
    pg_query($conn, 'COMMIT');
    pg_close($conn);
    echo "\n El proceso termino con exito. " . $i . " lineas procesadas.";
} else {
    echo "error";
}
?>
