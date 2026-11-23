<?php

abstract class BaseModel {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function getAll();
}