<?php

/**
 * Decripción Corta del archivo
 * 
 * Descripción larga (explicar próposito)...
 * 
 * IMPORTANTE:
 * Este programa es propiedad de Pagadito El Salvador S.A. de C.V. se prohibe su 
 * uso no autorizado, asi como cualquier alteración ó agregado sin previa 
 * autorización.
 * 
 * @author Edgard Rodas <e.rodas@pagadito.com>
 * @copyright Copyright (c) 2018 Pagadito El Salvador S.A. de C.V.
 * 
 */

// get json file
$string = file_get_contents('./posts.json');
$array_data = json_decode($string);

require_once '../Pagination.class.php';

// determine page (based on <_GET>)
$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;

// instantiate; set current page; set number of records
$pagination = (new Pagination());
$pagination->setCurrent($page);
$pagination->setTotal(count($array_data));
$pagination->setCrumbs(10);
$pagination->setKey('page');
$pagination->setRPP(50);

// determine limits of records
$start_limit = ((($page - 1) * 50) + 1);
$finish_limit = (($page * 50) > count($array_data) ? count($array_data) : ($page * 50));

// grab rendered/parsed pagination markup
$markup = $pagination->parse();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP-Pagination Demo</title>
        <link href="../themes/light.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <h1>PHP-Pagination Demo</h1>
        <h2>DATA FROM JSON FILE - POSTS</h2>
        <table border="1px" cellspacing="0" cellpadding="5" width="100%" >
            <thead>
                <tr>
                    <th>postId</th>
                    <th>id</th>
                    <th>name</th>
                    <th>email</th>
                    <th>body</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <div class="pagination"><?php echo $markup; ?></div>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php
                for ($i = $start_limit; $i <= $finish_limit; $i++) {
                    $data = $array_data[$i-1];
                ?>
                <tr>
                    <td><?php echo $data->postId; ?></td>
                    <td><?php echo $data->id; ?></td>
                    <td><?php echo $data->name; ?></td>
                    <td><?php echo $data->email; ?></td>
                    <td><?php echo $data->body; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </body>
</html>