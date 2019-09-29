<?php

namespace Wolfi\Todo\Handler;

class Handler
{
    /** @var \PDO */
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
}