<?php

ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "2000M");
set_time_limit(0);

$conn_string = "host=localhost port=5432 dbname=BigD user=postgres password=postgres";
$conn = pg_connect($conn_string);

$cabecera = array();
$orden = 0;
$array_orden = array();
$cantidad_por_agrupador = array();
$tabla = array();

//TRAIGO LOS AGRUPADORES
$sql = "select agru.id as agrupador_id,agru.multiple
from  campania_encuesta_agrupador_pregunta agru order by agru.id asc";

$result = pg_query($conn, $sql);

while ($row = pg_fetch_array($result)) {
    if ($row['multiple'] == 'f') {
//        traigo todas las preguntas del agrupador para armar la cabecera
        $sql = "select pre.texto_pregunta,campania_encuesta_agrupador_pregunta_id,pre.id as pregunta_id
                    from   campania_encuesta_preguntas pre  
                    where pre.campania_encuesta_agrupador_pregunta_id=" . $row['agrupador_id'];

        $result_pregunta = pg_query($conn, $sql);
        while ($row_pregunta = pg_fetch_array($result_pregunta)) {
            $cabecera[] = utf8_decode($row_pregunta['texto_pregunta']);
            $array_orden[$row_pregunta['pregunta_id']] = $orden;
            $orden++;
        }

        $cantidad_por_agrupador[] = array("id" => $row['agrupador_id'], "cantidad" => 1);
    } else {
        //busco la encuesta con mayor cantidad de campos para este agrupador
        $sql = "select max(conteo) 
                from(select pre.id pregunta,cab.id cabecera,count(med.id) as conteo
                    from  campania_encuesta enc
                    inner join campania_encuesta_agrupador_pregunta agru on enc.id=agru.campania_encuesta_id
                    inner join campania_encuesta_preguntas pre  on agru.id=pre.campania_encuesta_agrupador_pregunta_id
                    inner join campania_encuesta_pregunta_resultado_respuesta med on pre.id=med.campania_encuesta_pregunta_id 
                    inner join campania_encuesta_resultado_respuesta res on res.id=med.campania_encuesta_resultado_respuesta_id
                    inner join campania_encuesta_resultado_cabecera cab on cab.id=res.campania_encuesta_resultado_cabecera_id
                    where agru.id=" . $row['agrupador_id'] . " 
                    group by cab.id,pre.id) as conteo";

        $result_cantidad_agrupador = pg_query($conn, $sql);
        $cantidad_agrupador = pg_fetch_row($result_cantidad_agrupador);
        $cantidad_agrupador = $cantidad_agrupador[0]; //maxima cantidad de este agrupador que puede tener una encuesta
        //traigo las preguntas de este agrupador y las creo la cantidad de $cantidad_agrupador

        $sql = "select pre.texto_pregunta,pre.id as pregunta_id
                    from campania_encuesta_agrupador_pregunta agru 
                    inner join campania_encuesta_preguntas pre  on agru.id=pre.campania_encuesta_agrupador_pregunta_id
                    where agru.id=" . $row['agrupador_id'] . "  
                    order by pre.id";

        $result_pregunta_agrupador = pg_query($conn, $sql);
        $cabecera_agrupador = array();
        while ($row_pregunta_agrupador = pg_fetch_array($result_pregunta_agrupador)) {
            $cabecera_agrupador[] = $row_pregunta_agrupador['texto_pregunta'];
            $array_orden[$row_pregunta_agrupador['pregunta_id']] = $orden;
            $orden++;
        }
        $contador_preguntas_reales = 1;

        for ($i = 0; $i < $cantidad_agrupador; $i++) {
            foreach ($cabecera_agrupador as $pregunta) {
                $cabecera[] = utf8_decode($pregunta);
                $contador_preguntas_reales++;
                if ($contador_preguntas_reales >= $cantidad_agrupador) {
                    $orden++;
                }
            }
        }
        $cantidad_por_agrupador[] = array("id" => $row['agrupador_id'], "cantidad" => $cantidad_agrupador);
    }
}

$fila1 = ksort($array_orden);

//traigo todas las encuestas
$sql = "select id from campania_encuesta_resultado_cabecera";
$result_encuestas = pg_query($conn, $sql);

while ($row_encuesta = pg_fetch_array($result_encuestas)) {

    //consulto cuantas preguntas tiene la encuesta para saber si es la encuesta con error de 52 o la de 53
    $sql = "select count(distinct(pre.id))
                from campania_encuesta_preguntas pre
                inner join campania_encuesta_pregunta_resultado_respuesta med
                on med.campania_encuesta_pregunta_id=pre.id

                inner join campania_encuesta_resultado_respuesta res
                on res.id=med.campania_encuesta_resultado_respuesta_id

                inner join campania_encuesta_resultado_cabecera cab 
                on res.campania_encuesta_resultado_cabecera_id=cab.id
                where cab.id=" . $row_encuesta['id'];
    $result_cantidad_preguntas = pg_query($conn, $sql);
    $cantidad_preguntas_encuesta = pg_fetch_row($result_cantidad_preguntas);
    $cantidad_preguntas_encuesta = $cantidad_preguntas_encuesta[0];


//    traigo los datos de una encuesta
    $fila = array();
    foreach ($cantidad_por_agrupador as $agrupador) {
        //consulto cantidad de preguntas que tiene el agrupador
        $sql = "select count(pre.id)as cantidad
                from campania_encuesta_preguntas pre
                where pre.campania_encuesta_agrupador_pregunta_id=" . $agrupador['id'];
        $result_preguntas_agrupador = pg_query($conn, $sql);

        $cantidad_preguntas_agrupador = pg_fetch_row($result_preguntas_agrupador);
        $cantidad_preguntas_agrupador = $cantidad_preguntas_agrupador[0];

        //consulto si el agrupador es multiple
        $sql = "select agru.multiple
            
                from campania_encuesta_agrupador_pregunta agru
                inner join campania_encuesta_preguntas pre on agru.id=pre.campania_encuesta_agrupador_pregunta_id
                inner join campania_encuesta_pregunta_resultado_respuesta med
                on med.campania_encuesta_pregunta_id=pre.id

                inner join campania_encuesta_resultado_respuesta res
                on res.id=med.campania_encuesta_resultado_respuesta_id

                inner join campania_encuesta_resultado_cabecera cab 
                on res.campania_encuesta_resultado_cabecera_id=cab.id
                where cab.id='" . $row_encuesta['id'] . "' and agru.id='" . $agrupador['id'] . "' 
                order by res.id limit 1";
        $result_respuestas_agrupador_multiple = pg_query($conn, $sql);
        $multiple = pg_fetch_row($result_respuestas_agrupador_multiple);
        $multiple = $multiple[0];




        $sql = "select agru.id,pre.texto_pregunta,res.textorespuesta,agru.multiple,pre.id as pregunta_id
            
                from campania_encuesta_agrupador_pregunta agru
                inner join campania_encuesta_preguntas pre on agru.id=pre.campania_encuesta_agrupador_pregunta_id
                inner join campania_encuesta_pregunta_resultado_respuesta med
                on med.campania_encuesta_pregunta_id=pre.id

                inner join campania_encuesta_resultado_respuesta res
                on res.id=med.campania_encuesta_resultado_respuesta_id

                inner join campania_encuesta_resultado_cabecera cab 
                on res.campania_encuesta_resultado_cabecera_id=cab.id
                where cab.id='" . $row_encuesta['id'] . "' and agru.id='" . $agrupador['id'] . "' 
                order by res.id";
        $result_respuestas_agrupador = pg_query($conn, $sql);




        if ($multiple == "t") {
            $contador_preguntas_agrupador = 0; //cuanto para saber hasta donde puedo saber el orden por el 
            //id de la pregunta, ya que los multiples se guardan solo una vez en el array orden y los siguientes lo saco
            //sumando a esas posiciones
            while ($row_respuestas_agrupador = pg_fetch_array($result_respuestas_agrupador)) {
                if ($contador_preguntas_agrupador < $cantidad_preguntas_agrupador) {
                    //mientras sea el primer modulo de este agrupador puedo sacar als posiciones por id
                    $orden_correcto = $array_orden[$row_respuestas_agrupador['pregunta_id']];
                    $fila[$orden_correcto] = utf8_decode($row_respuestas_agrupador['textorespuesta']);
                    $contador_preguntas_agrupador++;
                    $orden_correcto_multiple = $orden_correcto;
                    //guardo la ultima posicion de orden_correcto en orden_correcto_multiple para despues
                    //sacar las posiciones sumando
                } else {
                    //cuando ya no puedo sacar el orden por array_orden lo saco sumando posiciones
                    $orden_correcto_multiple++;
                    $fila[$orden_correcto_multiple] = utf8_decode($row_respuestas_agrupador['textorespuesta']);
                    $contador_preguntas_agrupador++;
                }
            }
            $faltante = ($cantidad_preguntas_agrupador * $agrupador['cantidad']) - $contador_preguntas_agrupador;

            if ($faltante > 0) {
                //cuando el hay lugar para mas modulos de este agrupador y la encuesta no tiene datos, los
                //completo con  ""
                for ($m = 0; $m < $faltante; $m++) {
                    $orden_correcto_multiple++;
                    $fila[$orden_correcto_multiple] = "";
                }
            }
        } else {
            while ($row_respuestas_agrupador = pg_fetch_array($result_respuestas_agrupador)) {
                $orden_correcto = $array_orden[$row_respuestas_agrupador['pregunta_id']];
                if ($cantidad_preguntas_encuesta == 52 && $row_respuestas_agrupador['pregunta_id'] == 34) {
                    //si es la encuesta de 52 preguntas y es el id de pregunta 34, osea un id menos del id que falta, lo
                    //relleno con blanco 
                    $fila[$orden_correcto] = utf8_decode($row_respuestas_agrupador['textorespuesta']);
                    $orden_correcto++;
                    $fila[$orden_correcto] = "";
                } else {

                    $fila[$orden_correcto] = utf8_decode($row_respuestas_agrupador['textorespuesta']);
                }
            }
        }
    }
    $fila1 = ksort($fila);
    $tabla[] = $fila;
}



pg_close($conn);

include 'tablaExcel.php';
?>