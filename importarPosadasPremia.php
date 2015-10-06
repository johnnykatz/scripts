<?php

ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "2000M");
ini_set("max_execution_time",0);
//set_time_limit(0);

$conn_string = "host=localhost port=5432 dbname=BigD user=postgres password=postgres";
$conn = pg_connect($conn_string);

$sqlDropPreguntaResultadoRespuesta="campania_encuesta_pregunta_resultado_respuesta";
$sqlDropResultadoRespuesta="campania_encuesta_resultado_respuesta";
$sqlDropResultadoCabecera="campania_encuesta_resultado_cabecera";

pg_query($conn, "TRUNCATE TABLE public.".$sqlDropPreguntaResultadoRespuesta." RESTART IDENTITY CASCADE;");
pg_query($conn, "ALTER SEQUENCE public.".$sqlDropPreguntaResultadoRespuesta."_id_seq restart with 1;");
pg_query($conn, "TRUNCATE TABLE public.".$sqlDropResultadoRespuesta." RESTART IDENTITY CASCADE;");
pg_query($conn, "ALTER SEQUENCE public.".$sqlDropResultadoRespuesta."_id_seq restart with 1;");
pg_query($conn, "TRUNCATE TABLE public.".$sqlDropResultadoCabecera." RESTART IDENTITY CASCADE;");
pg_query($conn, "ALTER SEQUENCE public.".$sqlDropResultadoCabecera."_id_seq restart with 1;");

// relaciono con  estructura de BD

$arrayPreguntas = array(
    0 => 1, //"Nro de cuestionario"
    1 => 2, //"Nro de Brigadista"
    2 => 3, //"Nro de Partida"
    3 => 4, //"Dom de la Entrevista"
    4 => 5, //"Manzana"
    5 => 6, //"Chacra"
    6 => 7, //"Barrio"
    7 => 8, //"Categoría o Sección"
    8 => 11, //"¿Vive en este domicilio?"
    9 => 10, //"Régimen de tenencia de vivienda"
    10 => 12, //"Género"
    11 => 13, //"¿En qué fecha nació?"
    12 => 14, //"Número de Dni"
    13 => 9, //"¿Cuáles son, según su opinión, los tres principales problemas que debería atender prioritariamente el gobierno municipal?"
    14 => 20, //"¿Dónde realiza mayoritariamente sus compras diarias?"
    15 => 19, //"¿Dónde realiza las compras excepcionales? (indumentaria / calzado / regalos)"
    16 => 22, //"¿cuántos TV hay en el hogar?"
    17 => 25, //"¿Tiene radio?"
    18 => 23, //"¿Cuántos dispositivos electrónicos inteligentes hay en su hogar? (computadoras de escritorio / tabletas / notebooks / netbooks)"
    19 => 24, //"cuántos teléfonos móviles"
    20 => 26, //"¿De qué modo se informa prioritariamente de lo que ocurre en Posadas?"
    21 => 27, //"¿Usa Facebook?"
    22 => 21, //"usa instagram"
    23 => 28, //"¿Usa Twitter?"
    24 => 29, //"¿Tiene acceso a internet en el hogar?"
    25 => 30, //"¿Cuánto hace que adquirió su último dispositivo electrónico?"
    26 => 32, //"¿Ha escuchado hablar del Programa Posadas Premia?"
    27 => 33, //"¿Qué le parece esta iniciativa de la Municipalidad de Posadas?"
    28 => 34, //"¿Dónde le interesaría obtener los beneficios de descuentos de Posadas Premia?"
    29 => 36, //"Por favor, dejenos esa dirección / nro para comunicarnos con ud"
    30 => 31, //"Observaciones y comentarios del entrevistado."
    31 => 37, //"Modalidad de la aplicación"
    32 => 40, //"Tipo de vivienda"
    33 => 38, //"Material de la vivienda"
    34 => 39, //"Calidad de la vivienda"
    35 => 43, //"Nivel de aceptación para la administración de la entrevista"
    36 => 41, //"Nivel de entendimiento de la entrevista"
    37 => 42, //"Nivel de satisfacción final"
    38 => 44, //"Observaciones y comentarios del entrevistador"
);

$arrayPreguntas2 = array(
    0 => 1, //"Nro de cuestionario"
    1 => 2, //"Nro de Brigadista"
    2 => 3, //"Nro de Partida"
    3 => 4, //"Dom de la Entrevista"
    4 => 5, //"Manzana"
    5 => 6, //"Chacra"
    6 => 7, //"Barrio"
    7 => 8, //"Categoría o Sección"
    8 => 11, //"¿Vive en este domicilio?"
    9 => 10, //"Régimen de tenencia de vivienda"
    10 => 12, //"Género"
    11 => 13, //"¿En qué fecha nació?"
    12 => 14, //"Número de Dni"
    13 => 9, //"¿Cuáles son, según su opinión, los tres principales problemas que debería atender prioritariamente el gobierno municipal?"
    16 => 20, //"¿Dónde realiza mayoritariamente sus compras diarias?"
    17 => 19, //"¿Dónde realiza las compras excepcionales? (indumentaria / calzado / regalos)"
    18 => 22, //"¿cuántos TV hay en el hogar?"
    19 => 25, //"¿Tiene radio?"
    20 => 23, //"¿Cuántos dispositivos electrónicos inteligentes hay en su hogar? (computadoras de escritorio / tabletas / notebooks / netbooks)"
    21 => 24, //"cuántos teléfonos móviles"
    22 => 26, //"¿De qué modo se informa prioritariamente de lo que ocurre en Posadas?"
    23 => 27, //"¿Usa Facebook?"
    24 => 21, //"usa instagram"
    25 => 28, //"¿Usa Twitter?"
    26 => 29, //"¿Tiene acceso a internet en el hogar?"
    27 => 30, //"¿Cuánto hace que adquirió su último dispositivo electrónico?"
    28 => 32, //"¿Ha escuchado hablar del Programa Posadas Premia?"
    29 => 33, //"¿Qué le parece esta iniciativa de la Municipalidad de Posadas?"
    30 => 34, //"¿Dónde le interesaría obtener los beneficios de descuentos de Posadas Premia?"
    31 => 35, //"¿Por qué medio le interesaría seguir en contacto con PP?"
    32 => 36, //"Por favor, dejenos esa dirección / nro para comunicarnos con ud"
    33 => 31, //"Observaciones y comentarios del entrevistado."
    34 => 37, //"Modalidad de la aplicación"
    35 => 40, //"Tipo de vivienda"
    36 => 38, //"Material de la vivienda"
    37 => 39, //"Calidad de la vivienda"
    38 => 43, //"Nivel de aceptación para la administración de la entrevista"
    39 => 41, //"Nivel de entendimiento de la entrevista"
    40 => 42, //"Nivel de satisfacción final"
    41 => 44, //"Observaciones y comentarios del entrevistador"
);

$arrayOpcionesViveEnDomicilio = array(
    'si' => 2,
    'no' => 1,
);

$arrayTenenciaVivienda = array(
    "propietario de la vivienda y del terreno" => 5,
    "propietario de la vivienda solamente" => 7,
    "inquilino" => 6,
    "ocupante por pago de gastos" => 8,
    "ocupante gratuito" => 9,
    "ocupante de hecho" => 10,
    "en sucesión" => 12,
    "otra situación" => 11,
);

$arrayGrupoFamiliar = array(
    "pareja con hijos menores de 6 años (0 a 6 años)" => 13,
    "hogares mono parentales (no están en pareja o con hijos a cargo)" => 14,
    "pareja o adulto con hijos ya fuera del hogar" => 15,
    "pareja con hijos mayores de 14 años" => 16,
    "jóvenes 18 a 25 años, solteros sin hijos" => 17,
    "adultos independientes sin hijos" => 18,
    "pareja sin hijos" => 19,
    "pareja con hijos en edad intermedia (6 a 14 años)" => 20,
);


$arraySexo = array(
    "varón" => 3,
    "mujer" => 4,
);

$arrayTransporte = array(
    "auto" => 21,
    "moto" => 22,
    "bicicleta" => 23,
    "colectivo" => 24,
);

$arrayFrecuencia = array(
    "diariamente" => 26,
    "entre 4 y 6 veces x semana" => 27,
    "1 a 3 veces x semana" => 28,
    "menos de una vez x semana" => 25,
);

$arrayLugarCompras = array(
    "en comercios especializados por rubro de su barrio (verdulería, panadería, etc)" => 32,
    "en comercios especializados por rubro de otros barrios" => 33,
    "en despensas o almacenes del barrio" => 34,
    "en despensas o almacenes de otros barrios" => 35,
    "en autoservicios y supermercados" => 29,
    "en grandes supermercados o hipermercados" => 30,
    "en supermercados mayoristas" => 31,
);

$arrayComprasExepcionales = array(
    "en comercios de su barrio" => 36,
    "en comercios de otros barrios" => 37,
    "en comercios del centro de posadas" => 38,
    "en shoppings o centros comerciales" => 39,
);

$arrayRadio = array(
    "si" => 40,
    "no" => 41,
);

$arrayInforma = array(
    "tv" => 44,
    "radio am" => 43,
    "radio fm" => 45,
    "periodicos en papel" => 46,
    "periodicos digitales" => 48,
    "portales de noticias" => 47,
    "redes sociales" => 49,
    "boca en boca" => 42,
);

$arrayFacebook = array(
    "siempre" => 50,
    "algunas veces" => 51,
    "nunca" => 52,
);

$arrayInstagram = array(
    "siempre" => 58,
    "algunas veces" => 57,
    "nunca" => 56,
);

$arrayTwiter = array(
    "siempre" => 53,
    "algunas veces" => 55,
    "nunca" => 54,
);


$arrayInternet = array(
    "si" => 59,
    "no" => 60,
);

$arrayAdquirioDispositivo = array(
    "menos de 3 meses" => 63,
    "entre 3 y 6 meses" => 64,
    "entre 6 meses y 1 año" => 61,
    "hace más de un año" => 62,
);


$arrayEscuchoPremia = array(
    "si" => 65,
    "no" => 66,
);


$arrayQueLeParece = array(
    "muy buena" => 68,
    "buena" => 69,
    "regular" => 70,
    "mala" => 71,
    "muy mala" => 67,
);

$arrayDondeBeneficios = array(
    "gastronomía" => 73,
    "salud y belleza" => 74,
    "entretenimiento" => 75,
    "turismo" => 76,
    "compras" => 77,
    "servicios" => 72,
);

$arrayPorqueMedio = array(
    "mail" => 78,
    "sms" => 79,
    "facebook" => 80,
    "teléfono fijo" => 81,
    "personalmente" => 83,
    "whatsapp" => 82,
);

$arrayModalidad = array(
    "personal completa" => 86,
    "personal y telefónica" => 85,
    "telefónica" => 84,
);

$arrayTipoVivienda = array(
    "casilla" => 90,
    "monoblock" => 91,
    "departamento" => 92,
    "duplex" => 89,
    "ph" => 88,
    "casa" => 87,
);

$ArrayMaterial = array(
    "material" => 95,
    "madera" => 94,
    "chapa" => 93,
);

$arrayCalidadVivienda = array(
    "muy buena" => 98,
    "buena" => 100,
    "regular" => 99,
    "mala" => 97,
    "muy mala" => 96,
);

$ArrayAceptacion = array(
    "muy buena" => 101,
    "buena" => 102,
    "regular" => 103,
    "mala" => 104,
    "muy mala" => 105,
);

$ArrayEntendimiento = array(
    "muy buena" => 106,
    "buena" => 107,
    "regular" => 108,
    "mala" => 109,
    "muy mala" => 110,
);

$ArraySatisfaccion = array(
    "muy buena" => 115,
    "buena" => 114,
    "regular" => 113,
    "mala" => 112,
    "muy mala" => 111,
);


$arrayParentesco = array(
    "jefe" => 116,
    "jefa" => 117,
    "pareja" => 118,
    "hijo" => 119,
    "hija" => 120,
    "yerno" => 121,
    "nuera" => 122,
    "nieto" => 123,
    "nieta" => 124,
    "suegro" => 125,
    "suegra" => 126,
    "madre" => 127,
    "padre" => 128,
    "hermano" => 129,
    "hermana" => 130,
    "otros familiares" => 131,
    "no familiares" => 132,
);

$arrayNivelEducativo = array(
    "ninguno" => 133,
    "1rio incompleto" => 134,
    "1rio completo" => 135,
    "2rio incompleto" => 136,
    "2rio completo" => 137,
    "3rio incompleto" => 138,
    "3rio completo" => 139,
    "univ incompeto" => 140,
    "univ completo" => 141,
    "post grado" => 142,
);

$arraySexoMiembro = array(
    "varón" => 143,
    "mujer" => 144,
);

$arrayActividad = array(
    "ocupado" => 145,
    "desocupado" => 146,
    "inactivo" => 147,
    "menor de 10 años" => 148,
);


$arrayCategoriaOcupacional = array(
    "empleado sector público" => 149,
    "empleado sector privado" => 150,
    "trabajador por cuenta propia" => 151,
    "profesional independiente" => 152,
    "patrón" => 153,
    "changarín" => 154,
    "empl. serv. doméstico" => 155,
);

$arrayCategoriaInactividad = array(
    "jubilado" => 156,
    "pensionado" => 157,
    "rentista" => 158,
    "estudiante" => 159,
    "ama de casa" => 160,
    "menor de 6 años" => 161,
    "discapacitado" => 162,
    "unicamente beneficiario plan social" => 163,
    "Únicamente beneficiario plan social" => 163,
    "únicamente beneficiario plan social" => 163,
);

$arrayCobertura = array(
    "obra social (incluye pami)" => 164,
    "mutual, prepaga, servicio de emergencia" => 165,
    "planes y seguros públicos" => 166,
);

pg_query($conn, 'BEGIN work;');
echo "Procesando...\n";

//abro el directorio y luego recorro archivo por archivo
$id_usuario = 1;
$conteo = 0;
$directorio = scandir("xml_a_exportar");
$cant = count($directorio);
for ($m = 2; $m < $cant; $m++) {
    $xml_file = "xml_a_exportar/" . $directorio[$m];
//$xml_file = "xml_a_exportar/tablet8.xml";
if (strpos($xml_file, '.xml')!==false){
    if (file_exists($xml_file)) {
        $xml = simplexml_load_file($xml_file);
    } else {
        exit('Error al intentar abrir el fichero ' . $xml_file);
    }
    $count = 0;
    $id_tablet = $xml->attributes();

    foreach ($xml->encuesta as $encuesta) {
        //recorro cada encuesta

        foreach ($encuesta->cuestionario as $cuestionarios) {
            //recorro el cuestionario
            $cuestionario = new SimpleXMLElement($cuestionarios);
            $id_cuestionario = $cuestionario->attributes();

            $result = pg_query($conn, "select nextval('campania_encuesta_resultado_cabecera_id_seq')");
            $id_resultado_cabecera = pg_fetch_row($result);
            $id_resultado_cabecera = $id_resultado_cabecera[0];

            $sql = "INSERT INTO campania_encuesta_resultado_cabecera(
        id, id_externa, fecha,info_externa, nro_cuestionario, creado_por, actualizado_por, creado, actualizado)
        VALUES(
        $id_resultado_cabecera,
        $id_tablet,
        '$encuesta->fecha',
        1,
        $id_cuestionario,
        $id_usuario,
        $id_usuario,
        '" . date("Y-m-d H:i:s") . "',
        '" . date("Y-m-d H:i:s") . "'
        ) ";

            $result = pg_query($conn, $sql);

            if (!$result) {
                pg_query($conn, 'Rollback');
                pg_close($conn);
                echo $sql;
                echo $cont_preguntas;
                echo $xml_file;
                exit();
            }
            $cont_preguntas = 0;
//        $as = count($cuestionario->item);
            if (count($cuestionario->item) == 42) {
                $segundaEstructura = true;
                $respuesta = "";
            } else if (count($cuestionario->item) == 39) {
                $segundaEstructura = false;
            } else {
                echo "cantidad de item " . count($cuestionario->item);
                exit();
            }
            $conteoProblema = 1;
            foreach ($cuestionario->item as $item) {
                if ($segundaEstructura) {
                    //si tiene una estructura de 42 item
                    if ($cont_preguntas == 13) {
                        $id_pregunta = $arrayPreguntas2[$cont_preguntas];
                        $respuesta.="Problema " . $conteoProblema . ":" . $item->respuesta . " | ";
                        $conteoProblema++;
                    } elseif ($cont_preguntas == 14) {
                        $respuesta.="Problema " . $conteoProblema . ":" . $item->respuesta . " | ";
                        $conteoProblema++;
                    } elseif ($cont_preguntas == 15) {
                        $respuesta.="Problema " . $conteoProblema . ":" . $item->respuesta . " ";
                    } else {
                        $id_pregunta = $arrayPreguntas2[$cont_preguntas];
                    }

                    switch ($cont_preguntas) {
                        case 8:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayOpcionesViveEnDomicilio[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 10:
                            if ($item->respuesta != "") {
                                $id_opcion = $arraySexo[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 9:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayTenenciaVivienda[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 16:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayLugarCompras[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 17:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayComprasExepcionales[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 19:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayRadio[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 22:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayInforma[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 23:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayFacebook[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 24:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayInstagram[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 25:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayTwiter[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 26:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayInternet[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 27:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayAdquirioDispositivo[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 28:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayEscuchoPremia[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 29:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayQueLeParece[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 30:
                            $aux = $arrayDondeBeneficios[strtolower("$item->respuesta")];
                            if ($aux == null) {
                                $id_opcion = 72; //seteo como servicios
                            } else {
                                $id_opcion = $aux;
                            }
                            break;

                        case 31:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayPorqueMedio[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }
                            break;

                        case 34:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayModalidad[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 35:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayTipoVivienda[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 36:
                            if ($item->respuesta != "") {
                                $id_opcion = $ArrayMaterial[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 37:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayCalidadVivienda[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 38:
                            if ($item->respuesta != "") {
                                $id_opcion = $ArrayAceptacion[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 39:
                            if ($item->respuesta != "") {
                                $id_opcion = $ArrayEntendimiento[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 40:
                            if ($item->respuesta != "") {
                                $id_opcion = $ArraySatisfaccion[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        default :
                            $id_opcion = "zzz";
                            break;
                    }
                } else {
                    //si tiene una estructura de 39 item
                    $id_pregunta = $arrayPreguntas[$cont_preguntas];
                    switch ($cont_preguntas) {
                        case 8:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayOpcionesViveEnDomicilio[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 10:
                            if ($item->respuesta != "") {
                                $id_opcion = $arraySexo[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 9:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayTenenciaVivienda[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 14:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayLugarCompras[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 15:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayComprasExepcionales[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 17:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayRadio[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 20:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayInforma[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 21:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayFacebook[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 22:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayInstagram[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 23:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayTwiter[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 24:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayInternet[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 25:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayAdquirioDispositivo[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 26:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayEscuchoPremia[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

                        case 27:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayQueLeParece[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;

//                    case 28:
//                        $aux = $arrayDondeBeneficios[strtolower("$item->respuesta")];
//                        if ($aux == null) {
//                            $id_opcion = 72; //seteo como servicios
//                        } else {
//                            $id_opcion = $aux;
//                        }
//                        break;
                        case 31:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayModalidad[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 32:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayTipoVivienda[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 33:
                            if ($item->respuesta != "") {
                                $id_opcion = $ArrayMaterial[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 34:
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayCalidadVivienda[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 35:
                            if ($item->respuesta != "") {
                                $id_opcion = $ArrayAceptacion[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 36:
                            if ($item->respuesta != "") {
                                $id_opcion = $ArrayEntendimiento[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        case 37:
                            if ($item->respuesta != "") {
                                $id_opcion = $ArraySatisfaccion[strtolower("$item->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }

                            break;
                        default :
                            $id_opcion = "zzz";
                            break;
                    }
                }
                if ($segundaEstructura) {
                    //si tiene estructura de 40
                    if ($cont_preguntas != 13 and $cont_preguntas != 14) {
                        if ($cont_preguntas == 15) {
                            $textorespuesta = $respuesta;
                        } else {
                            if ($item->respuesta != "") {
                                $textorespuesta = $item->respuesta;
                            } else {
                                $textorespuesta = "";
                            }
                        }
                        $result = pg_query($conn, "select nextval('campania_encuesta_resultado_respuesta_id_seq')");
                        $id_resultado_respuesta = pg_fetch_row($result);
                        $id_resultado_respuesta = $id_resultado_respuesta[0];

                        if ($id_opcion == "zzz") {
                            $sql = "INSERT INTO campania_encuesta_resultado_respuesta(
                                        id, campania_encuesta_resultado_cabecera_id,
                                        texto_respuesta, creado_por, actualizado_por, creado, actualizado)
                                        VALUES(
                                        $id_resultado_respuesta,
                                        $id_resultado_cabecera,
                                      '". pg_escape_string($textorespuesta)."',
                                        $id_usuario,
                                        $id_usuario,
                                        '" . date("Y-m-d H:i:s") . "',
                                        '" . date("Y-m-d H:i:s") . "'
                                        ) ";
                        } else {
                            $sql = "INSERT INTO campania_encuesta_resultado_respuesta(
                                    id, campania_encuesta_opcion_respuesta_id,
                                    campania_encuesta_resultado_cabecera_id, texto_respuesta, creado_por, actualizado_por, creado, actualizado)
                                    VALUES($id_resultado_respuesta, $id_opcion, $id_resultado_cabecera, '". pg_escape_string($textorespuesta)."', $id_usuario, $id_usuario, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "' ) ";
                        }

                        $result = pg_query($conn, $sql);

                        if (!$result) {
                            pg_query($conn, 'Rollback');
                            pg_close($conn);
                            echo $sql . "\n";
//                        echo $item->respuesta . "\n";
                            echo "PREGUNTA " . $cont_preguntas . "\n";
                            echo "encuesta" . $count . "\n " . $item->pregunta;
                            echo $xml_file;
                            echo gettype($item->respuesta);
                            exit();
                        }

                        ///*** INSERTO EN PREGUNTA_RESULTADO_RESPUESTA*****////////
                        $result = pg_query($conn, "select nextval('campania_encuesta_pregunta_resultado_respuesta_id_seq')");
                        $id_pregunta_resultado_respuesta = pg_fetch_row($result);
                        $id_pregunta_resultado_respuesta = $id_pregunta_resultado_respuesta[0];

                        $sql = "INSERT INTO campania_encuesta_pregunta_resultado_respuesta(
                                        id, campania_encuesta_pregunta_id, campania_encuesta_resultado_respuesta_id,
                                        creado_por, actualizado_por, creado, actualizado)
                                        VALUES(
                                        $id_pregunta_resultado_respuesta,
                                        $id_pregunta,
                                        $id_resultado_respuesta,
                                        $id_usuario,
                                        $id_usuario,
                                        '" . date("Y-m-d H:i:s") . "',
                                        '" . date("Y-m-d H:i:s") . "'
                                        ) ";
                        $result = pg_query($conn, $sql);
                        if (!$result) {
                            pg_query($conn, 'Rollback');
                            pg_close($conn);
                            echo $sql . "\n";
//                        echo $item->respuesta . "\n";
                            echo "PREGUNTA " . $cont_preguntas . "\n";
                            echo "encuesta" . $count . "\n " . $item->pregunta;
                            echo $xml_file;
                            echo gettype($item->respuesta);
                            exit();
                        }
                    }
                } else {
                    //si tiene estructura de 39
                    if ($item->respuesta != "") {
                        $textorespuesta = trim($item->respuesta);
                    } else {
                        $textorespuesta = "";
                    }

                    $result = pg_query($conn, "select nextval('campania_encuesta_resultado_respuesta_id_seq')");
                    $id_resultado_respuesta = pg_fetch_row($result);
                    $id_resultado_respuesta = $id_resultado_respuesta[0];

                    if ($id_opcion == "zzz") {
                        $sql = "INSERT INTO campania_encuesta_resultado_respuesta(
                                        id, campania_encuesta_resultado_cabecera_id,
                                        texto_respuesta, creado_por, actualizado_por, creado, actualizado)
                                        VALUES(
                                        $id_resultado_respuesta,
                                        $id_resultado_cabecera,
                                        '" . trim($textorespuesta) . "',
                                        $id_usuario,
                                        $id_usuario,
                                        '" . date("Y-m-d H:i:s") . "',
                                        '" . date("Y-m-d H:i:s") . "'
                                        ) ";
                    } else {
                        $sql = "INSERT INTO campania_encuesta_resultado_respuesta(
                                    id, campania_encuesta_opcion_respuesta_id,
                                    campania_encuesta_resultado_cabecera_id, texto_respuesta, creado_por, actualizado_por, creado, actualizado)
                                    VALUES($id_resultado_respuesta, $id_opcion, $id_resultado_cabecera, '$textorespuesta', $id_usuario, $id_usuario, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "' ) ";
                    }

                    $result = pg_query($conn, $sql);

                    if (!$result) {
                        pg_query($conn, 'Rollback');
                        pg_close($conn);
                        echo $sql . "\n";
//                        echo $item->respuesta . "\n";
                        echo "PREGUNTA " . $cont_preguntas . "\n";
                        echo "encuesta" . $count . "\n " . $item->pregunta;
                        echo $xml_file;
                        echo gettype($item->respuesta);
                        exit();
                    }

                    ///*** INSERTO EN PREGUNTA_RESULTADO_RESPUESTA*****////////
                    $result = pg_query($conn, "select nextval('campania_encuesta_pregunta_resultado_respuesta_id_seq')");
                    $id_pregunta_resultado_respuesta = pg_fetch_row($result);
                    $id_pregunta_resultado_respuesta = $id_pregunta_resultado_respuesta[0];

                    $sql = "INSERT INTO campania_encuesta_pregunta_resultado_respuesta(
                                        id, campania_encuesta_pregunta_id, campania_encuesta_resultado_respuesta_id,
                                        creado_por, actualizado_por, creado, actualizado)
                                        VALUES(
                                        $id_pregunta_resultado_respuesta,
                                        $id_pregunta,
                                        $id_resultado_respuesta,
                                        $id_usuario,
                                        $id_usuario,
                                        '" . date("Y-m-d H:i:s") . "',
                                        '" . date("Y-m-d H:i:s") . "'
                                        ) ";
                    $result = pg_query($conn, $sql);
                    if (!$result) {
                        pg_query($conn, 'Rollback');
                        pg_close($conn);
                        echo $sql . "\n";
//                        echo $item->respuesta . "\n";
                        echo "PREGUNTA " . $cont_preguntas . "\n";
                        echo "encuesta" . $count . "\n " . $item->pregunta;
                        echo $xml_file;
                        echo gettype($item->respuesta);
                        exit();
                    }
                }
                $cont_preguntas++;
            }

            //saco el medio de transporte
//            $medios_transporte=new SimpleXMLElement($cuestionario->medios_transporte);
            foreach ($cuestionario->medios_transporte->children() as $medio) {
                $cont_item_medio = 0;
                foreach ($medio->item as $item_medio) {
                    switch ($cont_item_medio) {
                        case 0:
                            $id_pregunta_medio_transporte = 16; //tipo medio
                            if ($item_medio->respuesta != "") {
                                $id_opcion = $arrayTransporte[strtolower("$item_medio->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }
                            break;
                        case 1:
                            $id_pregunta_medio_transporte = 17; //cantidad en hogar
                            $id_opcion = "zzz";
                            break;
                        case 2:
                            $id_pregunta_medio_transporte = 18; //frecuencia de uso
                            if ($item_medio->respuesta != "") {
                                $id_opcion = $arrayFrecuencia[strtolower("$item_medio->respuesta")];
                            } else {
                                $id_opcion = "zzz";
                            }
                            break;
                        default :
                            break;
                    }

                    $result = pg_query($conn, "select nextval('campania_encuesta_resultado_respuesta_id_seq')");
                    $id_resultado_respuesta = pg_fetch_row($result);
                    $id_resultado_respuesta = $id_resultado_respuesta[0];
                    if ($id_opcion == "zzz") {
                        $sql = "INSERT INTO campania_encuesta_resultado_respuesta(
                            id, campania_encuesta_resultado_cabecera_id, texto_respuesta, creado_por, actualizado_por, creado, actualizado)
                            VALUES($id_resultado_respuesta, $id_resultado_cabecera, '$item_medio->respuesta', $id_usuario, $id_usuario, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "' ) ";
                    } else {
                        $sql = "INSERT INTO campania_encuesta_resultado_respuesta(
                            id,campania_encuesta_opcion_respuesta_id,
                            campania_encuesta_resultado_cabecera_id, texto_respuesta, creado_por, actualizado_por, creado, actualizado)
                            VALUES($id_resultado_respuesta, $id_opcion, $id_resultado_cabecera, '$item_medio->respuesta', $id_usuario, $id_usuario, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "' ) ";
                    }

                    $result = pg_query($conn, $sql);

                    if (!$result) {
                        pg_query($conn, 'Rollback');
                        pg_close($conn);
                        echo $sql . "\n";
                        echo "PREGUNTA " . $cont_preguntas . "\n";
                        echo "encuesta" . $count . "\n " . $cuestionario->medios_transporte->medio->item->pregunta;
                        echo $xml_file;
                        echo gettype($item->respuesta);
                        exit();
                    }

                    ///*** INSERTO EN PREGUNTA_RESULTADO_RESPUESTA*****////////
                    $result = pg_query($conn, "select nextval('campania_encuesta_pregunta_resultado_respuesta_id_seq')");
                    $id_pregunta_resultado_respuesta = pg_fetch_row($result);
                    $id_pregunta_resultado_respuesta = $id_pregunta_resultado_respuesta[0];

                    $sql = "INSERT INTO campania_encuesta_pregunta_resultado_respuesta(
                                        id, campania_encuesta_pregunta_id, campania_encuesta_resultado_respuesta_id,
                                        creado_por, actualizado_por, creado, actualizado)
                                        VALUES(
                                        $id_pregunta_resultado_respuesta,
                                        $id_pregunta_medio_transporte,
                                        $id_resultado_respuesta,
                                        $id_usuario,
                                        $id_usuario,
                                        '" . date("Y-m-d H:i:s") . "',
                                        '" . date("Y-m-d H:i:s") . "'
                                        ) ";
                    $result = pg_query($conn, $sql);
                    if (!$result) {
                        pg_query($conn, 'Rollback');
                        pg_close($conn);
                        echo $sql . "\n";
//                        echo $item->respuesta . "\n";
                        echo "PREGUNTA " . $cont_preguntas . "\n";
                        echo "encuesta" . $count . "\n " . $item->pregunta;
                        echo $xml_file;
                        echo gettype($item->respuesta);
                        exit();
                    }

                    $cont_item_medio++;
                }
            }

//        leo los miembros del hogar
//        $miembros = new SimpleXMLElement($cuestionario->miembros_hogar);
            foreach ($cuestionario->miembros_hogar->miembro as $miembro) {
                $cont_item_miembro = 0;
                foreach ($miembro->item as $item) {
                    switch ($cont_item_miembro) {
                        case 0:
                            $id_pregunta_miembro = 15; //como se compone el grupo familiar
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayGrupoFamiliar[strtolower("$item->respuesta")];
                                $textorespuesta = $item->respuesta;
                            } else {
                                $id_opcion = "zzz";
                                $textorespuesta = "";
                            }
                            break;
                        case 1:
                            $id_pregunta_miembro = 45; //nombre
                            if ($item->respuesta != "") {
                                $textorespuesta = $item->respuesta;
                            } else {
                                $textorespuesta = "";
                            }
                            $id_opcion = "zzz";
                            break;
                        case 2:
                            $id_pregunta_miembro = 46; //relacion de parentesco
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayParentesco[strtolower("$item->respuesta")];
                                $textorespuesta = $item->respuesta;
                            } else {
                                $id_opcion = "zzz";
                                $textorespuesta = "";
                            }
                            break;
                        case 3:
                            $id_pregunta_miembro = 47; //sexo
                            if ($item->respuesta != "") {
                                $id_opcion = $arraySexoMiembro[strtolower("$item->respuesta")];
                                $textorespuesta = $item->respuesta;
                            } else {
                                $id_opcion = "zzz";
                                $textorespuesta = "";
                            }
                            break;
                        case 4:
                            $id_pregunta_miembro = 48; //fecha nac
                            if ($item->respuesta != "") {
                                $textorespuesta = $item->respuesta;
                            } else {
                                $textorespuesta = "";
                            }
                            $id_opcion = "zzz";
                            break;
                        case 5:
                            $id_pregunta_miembro = 49; //nivel educativo
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayNivelEducativo[strtolower("$item->respuesta")];
                                $textorespuesta = $item->respuesta;
                            } else {
                                $id_opcion = "zzz";
                                $textorespuesta = "";
                            }
                            break;
                        case 6:
                            $id_pregunta_miembro = 50; //"Condición de la actividad"
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayActividad[strtolower("$item->respuesta")];
                                $textorespuesta = $item->respuesta;
                            } else {
                                $id_opcion = "zzz";
                                $textorespuesta = "";
                            }
                            break;
                        case 7:
                            $id_pregunta_miembro = 51; //"Categoría ocupacional"
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayCategoriaOcupacional[strtolower("$item->respuesta")];
                                $textorespuesta = $item->respuesta;
                            } else {
                                $id_opcion = "zzz";
                                $textorespuesta = "";
                            }
                            break;

                        case 8:
                            if ($count == 16) {
                                $EDR = 12548;
                            }
                            $id_pregunta_miembro = 52; //"Categoría inactividad"
                            if ($item->respuesta != "") {
                                $prueba = trim(strtolower("$item->respuesta"));
                                $id_opcion = $arrayCategoriaInactividad[trim(strtolower("$item->respuesta"))];
                                $textorespuesta = $item->respuesta;
                            } else {
                                $id_opcion = "zzz";
                                $textorespuesta = "";
                            }
                            break;
                        case 9:
                            $id_pregunta_miembro = 53; //"¿Tiene algún tipo de cobertura médica?"
                            if ($item->respuesta != "") {
                                $id_opcion = $arrayCobertura[strtolower("$item->respuesta")];
                                $textorespuesta = $item->respuesta;
                            } else {
                                $id_opcion = "zzz";
                                $textorespuesta = "";
                            }
                            break;
                        default :
                            break;
                    }


                    $result = pg_query($conn, "select nextval('campania_encuesta_resultado_respuesta_id_seq')");
                    $id_resultado_respuesta = pg_fetch_row($result);
                    $id_resultado_respuesta = $id_resultado_respuesta[0];
                    if ($id_opcion == "zzz") {
                        $sql = "INSERT INTO campania_encuesta_resultado_respuesta(
                            id, campania_encuesta_resultado_cabecera_id, texto_respuesta, creado_por, actualizado_por, creado, actualizado)
                            VALUES($id_resultado_respuesta, $id_resultado_cabecera,'". pg_escape_string($textorespuesta)."', $id_usuario, $id_usuario, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "' ) ";
                    } else {
                        $sql = "INSERT INTO campania_encuesta_resultado_respuesta(
                            id,campania_encuesta_opcion_respuesta_id,
                            campania_encuesta_resultado_cabecera_id, texto_respuesta, creado_por, actualizado_por, creado, actualizado)
                            VALUES($id_resultado_respuesta, $id_opcion, $id_resultado_cabecera, '". pg_escape_string($textorespuesta)."', $id_usuario, $id_usuario, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "' ) ";
                    }
                    $result = pg_query($conn, $sql);

                    if (!$result) {
                        pg_query($conn, 'Rollback');
                        pg_close($conn);
                        echo $sql . "\n";
                        echo "en archivo " . $directorio[$m];
                        echo "PREGUNTA " . $cont_preguntas . "\n";
                        echo "encuesta" . $count . "\n " . $cuestionario->medios_transporte->medio->item->pregunta;
                        echo $xml_file;
                        echo gettype($item->respuesta);
                        exit();
                    }

                    ///*** INSERTO EN PREGUNTA_RESULTADO_RESPUESTA*****////////
                    $result = pg_query($conn, "select nextval('campania_encuesta_pregunta_resultado_respuesta_id_seq')");
                    $id_pregunta_resultado_respuesta = pg_fetch_row($result);
                    $id_pregunta_resultado_respuesta = $id_pregunta_resultado_respuesta[0];

                    $sql = "INSERT INTO campania_encuesta_pregunta_resultado_respuesta(
                                        id, campania_encuesta_pregunta_id, campania_encuesta_resultado_respuesta_id,
                                        creado_por, actualizado_por, creado, actualizado)
                                        VALUES(
                                        $id_pregunta_resultado_respuesta,
                                        $id_pregunta_miembro,
                                        $id_resultado_respuesta,
                                        $id_usuario,
                                        $id_usuario,
                                        '" . date("Y-m-d H:i:s") . "',
                                        '" . date("Y-m-d H:i:s") . "'
                                        ) ";
                    $result = pg_query($conn, $sql);
                    if (!$result) {
                        pg_query($conn, 'Rollback');
                        pg_close($conn);
                        echo $sql . "\n";
//                        echo $item->respuesta . "\n";
                        echo "PREGUNTA " . $cont_preguntas . "\n";
                        echo "encuesta" . $count . "\n " . $item->pregunta;
                        echo $xml_file;
                        echo gettype($item->respuesta);
                        exit();
                    }

                    $cont_item_miembro++;
                }
            }
        }

        $count++;
    }
    $conteo+=$count;
    echo "archivo " . $directorio[$m] . " procesado";
  }
}
pg_query($conn, 'COMMIT');
pg_close($conn);
echo "\n El proceso termino con exito. " . $conteo . " lineas procesadas.";
?>
