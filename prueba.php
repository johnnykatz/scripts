<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        
        <?php
        if (isset($_POST['submit'])) {
//            $arrayCalleNumero = preg_split('/([A-Z|Ñ ][0-9])/',$_POST['calle']);

            $arrayCalleNumero = preg_split('/([A-Z|Ñ ])(?=\d)/',$_POST['calle']);

            print_r($arrayCalleNumero);
        }
        ?>
        <form method="post">
            <input type="text" name="calle">
            <input type="submit" name="submit">
        </form>
        
    </body>
</html>
