<?php

namespace Mobcash\Helthlife;



class ReddyBotService extends AbstractReddyService
{

    public $reportErrorTo = [];

    public function __construct($token, $reportErrorTo = '')
    {
        $this->token = $token;
        $this->reportErrorTo = explode(',', $reportErrorTo);
        parent::__construct();
    }

}
