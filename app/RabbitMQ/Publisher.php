<?php

include_once('Parenter.php');

class Publisher extends Parenter
{
    public function __construct()
    {
        parent::__construct('exchange', 'queue_send_sms', 'routeKey');
    }

    public function doProcess($msg,$msgObj)
    {

    }

}

