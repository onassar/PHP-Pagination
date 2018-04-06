<?php

/**
 * sample 2 - basic sample using setCrumb(), setKey() and setRPP()
 * 
 * basic usage of the Pagination Class
 * 
 * @author Edgard Rodas <rg.edgard@gmail.com>
 * @link https://github.com/nanacudo/PHP-Pagination
 * 
 */

require_once '../Pagination.class.php';

// get json data file
$data_string = file_get_contents('./posts-comments.json');
$data_array = json_decode($data_string);
$total = count($data_array);

// determine page (based on <_GET>)
$page = isset($_GET['x']) ? ((int) $_GET['x']) : 1;

// instantiate; set current page; set number of records
$pagination = (new Pagination());
$pagination->setCurrent($page);
$pagination->setTotal($total);
$pagination->setCrumbs(20);
$pagination->setKey('x');
$pagination->setRPP(15);

// grab rendered/parsed pagination markup
$markup = $pagination->parse();

// determine limits of records
$initial_limit = $pagination->getInitialLimit();
$final_limit = $pagination->getFinalLimit();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP-Pagination Demo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../themes/light.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <h1>PHP-Pagination Demo</h1>
        <h2>Sample 2</h2>
        <table border="1px" cellspacing="0" cellpadding="5" width="100%" >
            <thead>
                <tr>
                    <th colspan="5">Posts Comments</th>
                </tr>
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
                for ($i = $initial_limit; $i <= $final_limit; $i++) {
                    $data = $data_array[$i-1];
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
        <p>
            <a href="../index.html">Back to sample list</a>
        </p>
    </body>
</html>