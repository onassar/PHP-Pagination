<?php

require_once '../Pagination.class.php';

class PaginationTest extends \PHPUnit_Framework_TestCase
{

    public function testPagination() {
        $pagination = (new Pagination());
        $pagination->setCurrent(10);
        $pagination->setTotal(95);
        $pagination->setRPP(10);
        $this->assertEquals(10, $pagination->getNumberOfPages());
        $this->assertEquals(5, $pagination->countCurrentItems());
        $this->assertEquals(91, $pagination->firstItem());
        $this->assertEquals(95, $pagination->lastItem());

        //change to another page
        $pagination->setCurrent(5);
        $this->assertEquals(10, $pagination->countCurrentItems());
        $this->assertEquals(41, $pagination->firstItem());
        $this->assertEquals(50, $pagination->lastItem());
    }

}