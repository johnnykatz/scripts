<?php
//configuracion de php
ini_set('upload_max_filesize', '50M');
ini_set("memory_limit", "1000M");



set_time_limit(0);

//conexion
$conn_string = "host=localhost port=5432 dbname=BigD user=postgres password=postgres";
$conn = pg_connect($conn_string);

if (isset($_POST['procesar_padron_ss'])) {
    $id_usuario = 1;
    $fuente_datos_id = 2;
    include("script_padron_ss.php");
}
?>
<html>
    <head>
        <title>Importador</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <form enctype="multipart/form-data"  action="" method="POST">
            <div>
                <h2>Importador de datos</h2>
                <table>
                    <tr>
                    </tr>
                    <tr>
                    <td>
                        <input type="submit" name="procesar_padron_ss" value="Procesar padron_ss" onclick="return confirm('Desea hidratar tabla personas con padron_ss?')"/>
                    </td>
                    <td><br></td>
                    </tr>
                   
                </table>
            </div>
        </form>

    </body>
</html>
