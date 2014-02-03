<?php

require '../vendor/autoload.php';

use \markdunphy\MailChimp;

class MailChimpTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {

        parent::setUp();

        $this->data_center = 'us3';
        $this->key         = 'totallyrandomapikeythisisdefinitelynotarealthing-' . $this->data_center;

    } // setUp

    public function testConstructor()
    {

        $mc  = new MailChimp( $this->key );

        $this->assertEquals( $this->key, $mc->api_key );
        $this->assertEquals( $this->data_center, $mc->data_center );

    } // testConstructor


    public function testGetLists() {

        $mc = new MailChimp( $this->key );

        $lists = $mc->getLists();

    } // testGetLists

} // MailChimpTest
