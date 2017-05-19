<?php

class Db
{
    protected $db;

    public function __construct($config)
    {
        $this->db = new PDO("mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}", $config['user'], $config['password']);
    }

    public function query($sql, $params=null)
    {
        if (empty($params)) {
            $params = [];
        }

        $statement = $this->db->prepare($sql);
        if (!$statement->execute($params)) {
            error_log($statement->errorInfo()[2]);
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}