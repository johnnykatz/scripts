<?php

ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "2000M");
set_time_limit(0);

//parametros
$fuente_datos_id = 3; //IPA
$localidad_id = 10; // POSADAS
$tipo_domicilio_id = 1; //principal
$id_usuario = 1;


$conn_string = "host=localhost port=5432 dbname=BigD_prueba user=postgres password=postgres";
$conn = pg_connect($conn_string);
//$cont = 0; //cuenta la cantidad de personas que ya existen en la BD

echo "Procesando...";
if (($fichero = fopen("patentes.csv", "r")) !== FALSE) {
    pg_query($conn, 'BEGIN work;');
    $i = 0;
    while (($datos = fgetcsv($fichero, 1000, ";")) !== FALSE) {
        if ($i > 0) { //el valor 0 es la barra de titulo
            if ($i % 1000 == 0) {
                echo "\n" . $i . " Lineas y procesando... ";
            }
            $cuit = trim($datos[8]);
            //consulto si el cuit es distinto de null, si es null el registro se descarta, 
            //sino se procesa
            if ($cuit != 0) {
                $dni = substr(substr(trim($datos[8]), 2), 0, -1);
                $sql = "select id from personas where numero_documento='$dni' limit 1";
                $result = pg_query($conn, $sql);
                //consulto si existe la persona el la BD,
                //si existe actualizo el cuit, sino agrego la persona
                if (pg_num_rows($result) > 0) {
                    //si existe actualizo el cuit
                    $id_persona = pg_fetch_row($result);
                    $id_persona = $id_persona[0];
                    $sql = "UPDATE personas set cuit_cuil='$cuit' where id=$id_persona";

                    $result = pg_query($conn, $sql);

                    if (!$result) {
                        pg_query($conn, 'Rollback');
                        pg_close($conn);
                        echo $sql;
                        exit();
                    }
                } else {
                    //sino existe agrego la persona
                    $result = pg_query($conn, "select nextval('personas_id_seq')");
                    $id = pg_fetch_row($result);
                    $id_persona = $id[0];
                    //limpio de caracteres malos '
                    $ape_y_nom = preg_replace("(')", "", $datos[7]);
                    $ape_y_nom = explode(" ", $ape_y_nom);
                    $apellido = $ape_y_nom[0];
                    $nombre = "";
                    for ($j = 1; $j < sizeof($ape_y_nom); $j++) {
                        $nombre.=$ape_y_nom[$j] . " ";
                    }

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
                            . trim($apellido) . "','"
                            . $dni . "','"
                            . $cuit . "','"
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

                $calle = preg_replace("/\(?[0-9](.*?)\)/i", "", $datos[9]);
                $calle = preg_replace("(')", "", $calle);
                $domicilio = explode(" ", $calle);
                $num = count($domicilio);
                $numero = $domicilio[$num - 1];
                if (!is_numeric($numero)) {
                    $numero = 0;
                } else {
                    $calle = preg_replace("($numero)", "", $calle);
                }

                $sql = "INSERT INTO domicilios ("
                        . "id,"
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
                        . "actualizado)"
                        . "VALUES("
                        . "$id,'"
                        . trim($calle) . "','"
                        . trim($numero) . "',"
                        . "$localidad_id,"
                        . $tipo_domicilio_id . ","
                        . "false,"
                        . "false,"
                        . "$fuente_datos_id,"
                        . "$id_persona,"
                        . "$id_usuario,"
                        . "$id_usuario,'"
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

                //AGREGO EL RODADO
                $result = pg_query($conn, "select nextval('rodados_id_seq')");
                $id = pg_fetch_row($result);
                $id = $id[0];

                //pregunto el campo marca es distinto de limpio            
                if (trim($datos[1]) != "") {
                    //si el campo marca tiene un valor, consulto si existe en BD si tomo el id, sino agrego
                    $marca = strtoupper(trim($datos[1]));
                    $sql = "select id from marca_rodado where descripcion='$marca' limit 1";
                    $result = pg_query($conn, $sql);
                    if (pg_num_rows($result) > 0) {
                        //si existe tomo el id
                        $marca_rodado_id = pg_fetch_row($result);
                        $marca_rodado_id = $marca_rodado_id[0];
                    } else {
                        //sino agrego la marca a la BD
                        $result = pg_query($conn, "select nextval('marca_rodado_id_seq')");
                        $marca_rodado_id = pg_fetch_row($result);
                        $marca_rodado_id = $marca_rodado_id[0];
                        $sql = "INSERT INTO marca_rodado("
                                . "id,"
                                . "creado_por,"
                                . "actualizado_por,"
                                . "descripcion,"
                                . "creado,"
                                . "actualizado)"
                                . "VALUES("
                                . "$marca_rodado_id,"
                                . "$id_usuario,"
                                . "$id_usuario,'"
                                . $marca . "','"
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
//                        $marca_rodado_id = pg_query($conn, "SELECT CURRVAL(pg_get_serial_sequence('marca_rodado','id'));");
//                        $marca_rodado_id = pg_fetch_row($marca_rodado_id);
//                        $marca_rodado_id = $marca_rodado_id[0];
                    }
                } else {
                    //SI el campo marca esta vacio guardo como null
                    $marca_rodado_id = 'null';
                }


                $tipo_rodado_id = $datos[5] + 1; //sumo 1 porque en el csv comienza en 0 y en BD en 1
                if ($datos[2] != "") {
                    $modelo = trim($datos[2]);
                } else {
                    $modelo = '';
                }

                if ($datos[3] != "") {
                    $anio_modelo = trim($datos[3]);
                } else {
                    $anio_modelo = '';
                }
                if ($datos[4] != "") {
                    $precio = trim($datos[4]);
                } else {
                    $precio ='null';
                }
                if ($datos[6] != "") {
                    $fecha_baja = fechaComoDB(trim($datos[6]));
                    $sql = "INSERT INTO rodados("
                        . "id,"
                        . "fuente_datos_id,"
                        . "tipo_rodado_id,"
                        . "persona_id,"
                        . "marca_rodado_id,"
                        . "creado_por,"
                        . "actualizado_por,"
                        . "dominio,"
                        . "modelo,"
                        . "anio_modelo,"
                        . "precio,"
                        . "fecha_baja,"
                        . "creado,"
                        . "actualizado)"
                        . "VALUES ("
                        . "$id,"
                        . "$fuente_datos_id,"
                        . "$tipo_rodado_id,"
                        . "$id_persona,"
                        . "$marca_rodado_id,"
                        . "$id_usuario,"
                        . "$id_usuario,'"
                        . trim($datos[0]) . "','"
                        . $modelo . "','"
                        . $anio_modelo . "',"
                        . $precio . ",'"
                        . $fecha_baja . "','"
                        . date("Y-m-d H:i:s") . "','"
                        . date("Y-m-d H:i:s") . "')";
                } else {
                    $fecha_baja = "NULL";
                    $sql = "INSERT INTO rodados("
                        . "id,"
                        . "fuente_datos_id,"
                        . "tipo_rodado_id,"
                        . "persona_id,"
                        . "marca_rodado_id,"
                        . "creado_por,"
                        . "actualizado_por,"
                        . "dominio,"
                        . "modelo,"
                        . "anio_modelo,"
                        . "precio,"
                        . "fecha_baja,"
                        . "creado,"
                        . "actualizado)"
                        . "VALUES ("
                        . "$id,"
                        . "$fuente_datos_id,"
                        . "$tipo_rodado_id,"
                        . "$id_persona,"
                        . "$marca_rodado_id,"
                        . "$id_usuario,"
                        . "$id_usuario,'"
                        . trim($datos[0]) . "','"
                        . $modelo . "','"
                        . $anio_modelo . "',"
                        . $precio . ","
                        . $fecha_baja . ",'"
                        . date("Y-m-d H:i:s") . "','"
                        . date("Y-m-d H:i:s") . "')";
                    
                }

                

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
}

pg_query($conn, 'COMMIT');
pg_close($conn);

echo "\nEl proceso termino con exito. " . $i . " Lineas procesadas";

function fechaComoDB($fecha) {
    if ($fecha != "") {
        $fecha = explode("/", $fecha);
        return $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
    } else {
        return false;
    }
}

?>