<?php

include_once('Parenter.php');

class Publisher extends Parenter
{
    public function __construct()
    {
        parent::__construct('exchange', 'bet_order_list_queue', 'routeKey');
    }

    public function doProcess($msg,$msgObj)
    {

    }

}

