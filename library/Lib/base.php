<?php
abstract class Model_Base
{
    protected $host = '127.0.0.1';

    protected $user = 'btc';

    protected $pass = 'money668';

    protected $dbname = 'btc';

    protected $port = '3306';

    protected $db = null;

    protected $table = '';

    public function __construct($database, $debug = false)
    {
        $this->db = new medoo([
            'database_type' => 'mysql',
	    'database_name' => $this->dbname,
	    'server' => $this->host,
	    'username' => $this->user,
	    'password' => $this->pass,
	    'port' => $this->port,
	    'charset' => 'utf8',
            /*'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	    ]*/
        ]);

        Conf_Database::getConf();
        
        if ($debug) {
            $this->db = $this->db->debug();
        }
    }

    public function insert($dataset)
    {
        return $this->db->insert($this->table, $dataset);
    }

    public function select($fields, $where)
    {
        return $this->db->select($this->table, $fields, $where);
    }

    public function update($dataset, $where)
    {
        return $this->db->update($this->table, $dataset, $where);
    }

}
