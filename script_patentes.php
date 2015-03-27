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
		$dni=substr(substr(trim($datos[8]),2),0,-1);
		$sql="select id from personas where numero_documento='$dni' limit 1";
		$result=pg_query($conn,$sql);
		if(pg_num_rows($result)==1){
			$cont+=1;
		}
		$i++;	
	}
	

}

echo $i." Lineas procesadas";
echo "\n". " $cont coincidencias";

?>	