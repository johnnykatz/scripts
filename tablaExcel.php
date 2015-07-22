<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=is_estadistica_por_grupo_etario_xls.xls");
//$datos= traerDatos();

?>
<table border="1">
    <tr>
        <?php
        foreach ($cabecera as $columna) {
            echo "<td>" . $columna . "</td>";
        }
        ?>
    </tr>
    <?php
    foreach ($tabla as $fila) {
        echo"<tr>";
        foreach ($fila as $columna) {
            echo "<td>" . $columna . "</td>";
        }
        echo"</tr>";
//        break;
    }
    ?>
</table>
