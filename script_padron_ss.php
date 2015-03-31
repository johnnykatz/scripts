<?php
ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "2000M");
set_time_limit(0);


//conexion
$conn_string = "host=localhost port=5432 dbname=BigD_real user=postgres password=postgres";
$conn = pg_connect($conn_string);

//parametros
$id_usuario = 1;
$fuente_datos_id = 2;



$arrayTipoDocumento = array(
    "DNI-EC" => 1,
    "LT" => 3,
    "DNI10" => 1,
    "DNIC" => 1,
    "L" => 3,
    "DNI13" => 1,
    "LD" => 3,
    "DNI7" => 1,
    "LC" => 3,
    "DNI" => 1,
    "DNID" => 1,
    "DNI8" => 1,
    "DNI-EZ" => 1,
    "DNI-EB" => 1,
    "DNIT" => 1,
    "DNI6" => 1,
    "DNI-ES" => 1,
    "DNI5" => 1,
    " " => 1,
    "DNI-EE" => 1,
    "L6" => 3,
    "DNI11" => 1,
    "DNI-EH" => 1,
    "DNI-EA" => 1,
    "DNI-ED" => 1,
    "DNI9" => 1);


$sql = "SELECT 
            matricula,
            clase,
            apellido,
            nombre,
            domicilio,
            numero,
            puerta,
            profesion,
            tipo_documento,
            analfabeto,
            seccion,
            circuito,
            sexo,
            fecha_nacimiento,
            trash_column
      FROM padron_ss";

$result_padron = pg_query($conn, $sql);
pg_query($conn, 'BEGIN work;');
$i = 0;
while ($row = pg_fetch_array($result_padron)) {
    if ($i % 10000 == 0) {
        echo "\n" . $i . " Lineas y procesando... ";
    }
    $result = pg_query($conn, "select nextval('personas_id_seq')");
    $id = pg_fetch_row($result);
    $id_persona = $id[0];
    $tipo_documento_id = $arrayTipoDocumento[$row[8]];
    $apellido = preg_replace("(')", " ", (trim($row[2])));
    $nombre = preg_replace("(')", " ", (trim($row[3])));
    $fecha_nacimiento = fechaComoDB(trim($row[13]));
    if ($fecha_nacimiento != false) {
        $sql = "INSERT INTO personas ("
                . "id,"
                . "tipo_documento_id,"
                . "creado_por,"
                . "actualizado_por,"
                . "nombre,"
                . "apellido,"
                . "numero_documento,"
                . "creado,"
                . "actualizado,"
                . "fecha_nacimiento,"
                . "sexo)"
                . "VALUES("
                . "$id_persona,"
                . "$tipo_documento_id,"
                . "$id_usuario,"
                . "$id_usuario,'"
                . $nombre . "','"
                . $apellido . "','"
                . trim($row[0]) . "','"
                . date("Y-m-d H:i:s") . "','"
                . date("Y-m-d H:i:s") . "','"
                . fechaComoDB(trim($row[13])) . "','"
                . trim($row[12]) . "')";
    } else {
        $sql = "INSERT INTO personas ("
                . "id,"
                . "tipo_documento_id,"
                . "creado_por,"
                . "actualizado_por,"
                . "nombre,"
                . "apellido,"
                . "numero_documento,"
                . "creado,"
                . "actualizado,"
                . "sexo)"
                . "VALUES("
                . "$id_persona,"
                . "$tipo_documento_id,"
                . "$id_usuario,"
                . "$id_usuario,'"
                . $nombre . "','"
                . $apellido . "','"
                . trim($row[0]) . "','"
                . date("Y-m-d H:i:s") . "','"
                . date("Y-m-d H:i:s") . "','"
                . trim($row[12]) . "')";
    }


    $result = pg_query($conn, $sql);

    if (!$result) {
        pg_query($conn, 'Rollback');
        pg_close($conn);
        echo $sql;
        echo $i;
        exit();
    }
    $i++;
}
pg_query($conn, 'COMMIT');
pg_close($conn);
echo "\nEl proceso termino con exito. ".$i." lineas procesadas.";

function fechaComoDB($fecha) {
    if ($fecha != "") {
        $fecha = explode("/", $fecha);
        return $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
    } else {
        return false;
    }
}
?>



