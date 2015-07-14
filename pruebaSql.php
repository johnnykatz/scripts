<?php

ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "2000M");
set_time_limit(0);

$conn_string = "host=localhost port=5432 dbname=BigD user=postgres password=postgres";
$conn = pg_connect($conn_string);

$cabecera = array();

echo "Procesando...\n";

//TRAIGO LOS AGRUPADORES
$sql = "select agru.id as agrupador_id,agru.multiple
from  campania_encuesta_agrupador_pregunta agru";

$result = pg_query($conn, $sql);

while ($row = pg_fetch_array($result)) {
    if ($row['multiple'] == 'f') {
//        traigo todas las preguntas del agrupador para armar la cabecera
        $sql = "select pre.texto_pregunta
                    from   campania_encuesta_preguntas pre  
                    where pre.campania_encuesta_agrupador_pregunta_id=" . $row['agrupador_id'];

        $result_pregunta = pg_query($conn, $sql);
        while ($row_pregunta = pg_fetch_array($result_pregunta)) {
            $cabecera[] = $row_pregunta['texto_pregunta'];
        }
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
        $cantidad_agrupador = $cantidad_agrupador[0];

        //traigo las preguntas de este agrupador y las creo la cantidad de $cantidad_agrupador

        $sql = "select pre.texto_pregunta
                    from campania_encuesta_agrupador_pregunta agru 
                    inner join campania_encuesta_preguntas pre  on agru.id=pre.campania_encuesta_agrupador_pregunta_id
                    where agru.id=" . $row['agrupador_id'] . "  
                    order by pre.id";

        $result_pregunta_agrupador = pg_query($conn, $sql);
        $cabecera_agrupador = array();
        while ($row_pregunta_agrupador = pg_fetch_array($result_pregunta_agrupador)) {
            $cabecera_agrupador[] = $row_pregunta_agrupador['texto_pregunta'];
        }

        for ($i = 0; $i < $cantidad_agrupador; $i++) {
            foreach ($cabecera_agrupador as $pregunta) {
                $cabecera[] = $pregunta;
            }
        }
    }
}

var_dump($cabecera);

pg_close($conn);

//pg_query($conn, 'BEGIN work;');
?>
