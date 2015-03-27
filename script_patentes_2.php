<?php
$conn_string = "host=localhost port=5432 dbname=BigD user=postgres password=postgres";
$conn = pg_connect($conn_string);
$cont=0; //cuenta la cantidad de personas que ya existen en la BD

echo "Procesando...";
if (($fichero = fopen("patentes.csv", "r")) !== FALSE) {
    pg_query($conn, 'BEGIN work;');
    $i = 0;
    while (($datos = fgetcsv($fichero, 1000, ";")) !== FALSE) {
		if($i%1000==0){
			echo "\n".$i." Lineas y procesando... ";

		}
		$cuit=trim($datos[8];
		if($cuit!=0){
			$dni=substr(substr(trim($datos[8]),2),0,-1);
			$sql="select id from personas where numero_documento='$dni' limit 1";
			$result=pg_query($conn,$sql);
			if(pg_num_rows($result)>0){
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




			}else{
				//sino existe agrego la persona
				$result = pg_query($conn, "select nextval('personas_id_seq')");
                $id = pg_fetch_row($result);
                $id_persona = $id[0];
				//limpio de caracteres malos '
				$ape_y_nom=preg_replace("(')", "", $datos[7]);				
                $ape_y_nom = explode(" ", $ape_y_nom);
                $apellido=$ape_y_nom[0];
                $nombre="";
                for($j=1;$j<=sizeof($ape_y_nom);$j++){
                	$nombre.=$ape_y_nom[$j]." ";
                	}
               
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
                        . (trim($apellido)) . "','"
                        . $dni. "','"
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
                    . (trim(utf8_encode($calle))). "','"
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
                    . date("Y-m-d H:i:s") . "',')";

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

}

echo $i." Lineas procesadas";
echo "\n". " $cont coincidencias";



?>