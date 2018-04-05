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

require_once '../Pagination.class.php';

// determine page (based on <_GET>)
$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;

// instantiate; set current page; set number of records
$pagination = (new Pagination());
$pagination->setCurrent($page);
$pagination->setTotal(300);
$pagination->setCrumbs(10);
$pagination->setKey('page');
$pagination->setRPP(50);

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
        <div class="pagination"><?php echo $markup; ?></div>
    </body>
</html>