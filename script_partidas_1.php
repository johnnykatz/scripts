<?php

//Importa datos del csv partidas 

ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "2000M");
set_time_limit(0);

$conn_string = "host=localhost port=5432 dbname=BigD_prueba user=postgres password=postgres";
$conn = pg_connect($conn_string);

///datos de configuracion
$id_usuario = 1;
$tipo_domicilio_id = 1;
$fuente_datos_id = 1;


$arrayLocalidad = array(
    "APOSTOLES" => 1,
    "AZARA" => 2,
    "SAN JOSE" => 3,
    "TRES CAPONES" => 4,
    "ARISTOBULO DEL VALLE" => 5,
    "CAMPO GRANDE" => 6,
    "2 DE MAYO" => 7,
    "FACHINAL" => 8,
    "GARUPA" => 9,
    "POSADAS" => 10,
    "BOMPLAND" => 11,
    "CANDELARIA" => 12,
    "CERRO CORA" => 13,
    "LORETO" => 14,
    "MARTIRES" => 15,
    "PROFUNDIDAD" => 16,
    "SANTA ANA" => 17,
    "CONCEPCION DE LA SIERRA" => 18,
    "SANTA MARIA" => 19,
    "COLONIA DELICIA" => 20,
    "COLONIA VICTORIA" => 21,
    "ELDORADO" => 22,
    "9 DE JULIO" => 23,
    "SANTIAGO DE LINIERS" => 24,
    "BERNARDO DE YRIGOYEN" => 25,
    "SAN ANTONIO" => 26,
    "CMTE GUACURARY" => 27,
    "EL SOBERBIO" => 28,
    "SAN VICENTE" => 29,
    "COLONIA WANDA" => 30,
    "PUERTO ESPERANZA" => 31,
    "IGUAZU" => 32,
    "LIBERTAD" => 33,
    "CAPIOVI" => 34,
    "EL ALCAZAR" => 35,
    "GARUHAPE" => 36,
    "PUERTO LEONI" => 37,
    "PUERTO RICO" => 38,
    "RUIZ DE MONTOYA" => 39,
    "ALMAFUERTE" => 40,
    "ARROYO DEL MEDIO" => 41,
    "CAA YARI" => 42,
    "CERRO AZUL" => 43,
    "DOS ARROYOS" => 44,
    "GOBERNADOR LOPEZ" => 45,
    "LEANDRO N ALEM" => 46,
    "OLEGARIO ANDRADE" => 47,
    "CARAGUATAY" => 48,
    "MONTECARLO" => 49,
    "PUERTO PIRAY" => 50,
    "CAMPO RAMON" => 51,
    "CAMPO VIERA" => 52,
    "COLONIA ALBERDI" => 53,
    "GRAL ALVEAR" => 54,
    "GENERAL ALVEAR" => 54,
    "GUARANI" => 55,
    "LOS HELECHOS" => 56,
    "OBERA" => 57,
    "PANAMBI" => 58,
    "SAN MARTIN" => 59,
    "COLONIA POLANA" => 60,
    "CORPUS" => 61,
    "GRAL URQUIZA" => 62,
    "GOBERNADOR ROCA" => 63,
    "HIPOLITO YRIGOYEN" => 64,
    "JARDIN AMERICA" => 65,
    "SAN IGNACIO" => 66,
    "SANTO PIPO" => 67,
    "FLORENTINO AMHEGUINO" => 68,
    "ITACARUARE" => 69,
    "MOJON GRANDE" => 70,
    "SAN JAVIER" => 71,
    "SAN PEDRO" => 72,
    "ALBA POSSE" => 73,
    "COLONIA AURORA" => 74,
    "25 DE MAYO" => 75,
);
echo "Procesando...";
if (($fichero = fopen("partidas_prueba.csv", "r")) !== FALSE) {
    pg_query($conn, 'BEGIN work;');
    $i = 0;
    while (($datos = fgetcsv($fichero, 1000, ";")) !== FALSE) {
        if ($i > 0) {
            if ($i % 10000 == 0) {
                echo "\n" . $i . " Lineas y procesando... ";
            }

            //elimino segmentos entre parentesis de la calle
            $calle = preg_replace("/\(?[0-9](.*?)\)/i", "", $datos[12]);
            $calle = preg_replace("(')", "", $calle);
            $arrayDomicilio = explode('-', trim($calle));
            $bandera = 0;
            switch (count($arrayDomicilio)) {
                case 2:
                    $arrayDomicilio[0] = $arrayDomicilio[0];
                    $arrayDomicilio[1] = $arrayDomicilio[1];
                    break;
                case 3:
                    $arrayDomicilio[0] = $arrayDomicilio[0] . " " . $arrayDomicilio[1];
                    $arrayDomicilio[1] = $arrayDomicilio[2];
                    break;
                case 4:
                    $arrayDomicilio[0] = $arrayDomicilio[0] . " " . $arrayDomicilio[1] . " " . $arrayDomicilio[2];
                    $arrayDomicilio[1] = $arrayDomicilio[3];
                    break;
                case 5:
                    $arrayDomicilio[0] = $arrayDomicilio[0] . " " . $arrayDomicilio[1] . " " . $arrayDomicilio[2] . " " . $arrayDomicilio[3];
                    $arrayDomicilio[1] = $arrayDomicilio[4];
                    break;
                default :
                    $arrayDomicilio[0] = $calle;
                    $bandera = 1;
                    break;
            }
            if ($bandera == 0) {
                if (isset($arrayDomicilio[1]) && $arrayDomicilio[1] != "") {
                    if (isset($arrayLocalidad[trim($arrayDomicilio[1])])) {
                        $localidad = $arrayLocalidad[trim($arrayDomicilio[1])];
                    } else {
                        $localidad = 1;
                    }
                } else {
                    $localidad = 1;
                }
                $calle = $arrayDomicilio[0];
                $domicilio = explode(" ", trim($calle));
                $num = count($domicilio);
                $numero = $domicilio[$num - 1];
                if (!is_numeric($numero)) {
                    $numero = 0;
                } else {
                    $calle = preg_replace("($numero)", "", $arrayDomicilio[0]);
                }
            } else {
                $localidad = 1;
                $calle = $arrayDomicilio[0];
                $numero = 0;
            }




            $num_doc = trim($datos[8]);
            $result = pg_query("select id from personas where numero_documento='$num_doc' limit 1");
            if (pg_num_rows($result) > 0) {
                //si existe el dni en personas, actualizo el cuit
                //y creo un nuevo domicilio con principal=false, validado=false 
                //y fuente de datos configurada en datos de configuracion

                $id_persona = pg_fetch_row($result);
                $id_persona = $id_persona[0];

                $cuit = trim($datos[9]);
                $sql = "UPDATE personas set cuit_cuil='$cuit' where id=$id_persona";

                $result = pg_query($conn, $sql);

                if (!$result) {
                    pg_query($conn, 'Rollback');
                    pg_close($conn);
                    echo $sql;
                    exit();
                }
            } else {
                //si no existe agrego la persona
                //agrego el domicilio con seteo principal=false, validado=false
                //y fuente de datos configurada en datos de configuracion

                $result = pg_query($conn, "select nextval('personas_id_seq')");
                $id = pg_fetch_row($result);
                $id_persona = $id[0];
                //limpio de caracteres malos '
                $ape_y_nom = preg_replace("(')", "", $datos[7]);

                $ape_y_nom = explode(",", $ape_y_nom);

                $nombre = (sizeof($ape_y_nom) > 1) ? ($ape_y_nom[1]) : '';

                $sql = "INSERT INTO personas ("
                        . "id,"
                        . "tipo_documento_id,"
                        . "creado_por,"
                        . "actualizado_por,"
                        . "nombre,"
                        . "apellido,"
                        . "numero_documento,"
                        . "cuit_cuil,"
                        . "creado,"
                        . "actualizado)"
                        . "VALUES("
                        . "$id_persona,"
                        . "1,"
                        . "$id_usuario,"
                        . "$id_usuario,'"
                        . trim($nombre) . "','"
                        . (trim($ape_y_nom[0])) . "','"
                        . trim($datos[8]) . "','"
                        . trim($datos[9]) . "','"
                        . date("Y-m-d H:i:s") . "','"
                        . date("Y-m-d H:i:s") . "')";
                $result = pg_query($conn, $sql);

                if (!$result) {
                    pg_query($conn, 'Rollback');
                    pg_close($conn);
                    echo $sql;
                    echo $i;
                    exit();
                }
            }
            //agrego el domicilio 
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
                    . trim($datos[1]) . "','"
                    . trim($datos[2]) . "','"
                    . trim($datos[3]) . "','"
                    . trim($datos[4]) . "','"
                    . trim($datos[6]) . "','"
                    . (trim(utf8_encode($calle))) . "','"
                    . (trim($numero)) . "',"
                    . "$localidad,"
                    . $tipo_domicilio_id . ","
                    . "false,"
                    . "false,"
                    . "$fuente_datos_id,"
                    . "$id_persona,"
                    . "$id_usuario,"
                    . "$id_usuario,'"
                    . date("Y-m-d H:i:s") . "','"
                    . date("Y-m-d H:i:s") . "','"
                    . trim($datos[0]) . "')";

            $result = pg_query($conn, $sql);

            if (!$result) {
                pg_query($conn, 'Rollback');
                pg_close($conn);
                echo $sql;
                echo $i;
                exit();
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
