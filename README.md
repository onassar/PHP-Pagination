PHP Pagination
===

PHP-Pagination contains an instantiable class, along with a *view* which renders
the pagination markup.

The purpose of this library is to provide a simple API to render pagination
markup, without having to worry about including common files and set too many
settings. With this class, you simply pass in your parameters and make a call to
the instance&#039;s *&lt;parse&gt; method.

### Pagination Instantiation and Rendering

``` php
<?php

    // source inclusion
    require_once APP . '/vendors/PHP-Pagination/Pagination.class.php';

    // determine page (based on <_GET>)
    $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;

    // instantiate; set current page; set number of records
    $pagination = (new Pagination());
    $pagination->setCurrent($page);
    $pagination->setTotal(200);

    // grab rendered/parsed pagination markup
    $markup = $pagination->parse();

```
