<?php
class Db
{
    private $user = 'root';
    private $password = '';
    private $host = '127.0.0.1';
    private $dataBase = 'Asturfest';
    private $table = '';
    protected $last_error = '';
    protected $code_error = 0;

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setDataBase($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getDataBase()
    {
        return $this->dataBase;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function getTable()
    {
        return $this->table;
    }

    /**
     * Connect to bd
     * @param boolean with_db
     *  
     * @return PDO|False
     */

    private function getConnection($with_db = false)
    {

        $cadena_conexion = 'mysql:';

        if ($with_db) {
            $cadena_conexion .= 'dbname=' . $this->dataBase;
        }

        $cadena_conexion .= ";host" . $this->host;
        try {
            $db = new PDO($cadena_conexion, $this->user, $this->password);
        } catch (PDOException $e) {
            $this->last_error = $e->getMessage();
            $this->code_error = $e->getCode();
            $db = false;
        }

        return $db;
    }

    /**
     * Execute a query
     * 
     * @param string $query 
     *  
     * @return PDOStatement|false
     */

    public function executeS($query, $with_db = true)
    {
        $connexion = $this->getConnection($with_db);
        if (!$connexion) {
            return false;
        }

        try {
            $result = $connexion->query($query);
            return $result;
        } catch (PDOException $e) {
            $this->last_error = $e->getMessage();
            $this->code_error = $e->getCode();
            return false;
        }
        
    }

    /**
     * Get a query
     * 
     * @param $type string
     * @param $params array
     *  
     * @return string|false
     */

    public function getQuery(string $type, array $params)
    {
        switch ($type) {
            case 'DELETE':
                if (!intval($params['value'], 10) == true || $params['value'] < 1) {
                    return false;
                }

                $query = "DELETE FROM " . $this->table . " WHERE id = " . $params['value'] . ";";
                break;

            case 'INSERT':
                $paramsKey = array_keys($params);
                $query = '';
                $query = "INSERT into " . $this->table . "(";
                foreach ($paramsKey as $field) {
                    $query .= $field . ",";
                }
                $query = substr($query, 0, -1) . ") VALUES (";

                foreach ($params as $value) {
                    if (intval($value)) {
                        $query .= $value . ",";
                    } else {
                        $query .= "'" . $value . "',";
                    }
                }
                
                $query = substr($query, 0, -1) . ');';
                break;

            case 'SELECT':
                if (!isset($params['col']) || empty(trim($params['col']))) {
                    return false;
                }

                $query = "SELECT {$params['col']} FROM {$this->table}";
                if (isset($params['where']) && !empty(trim($params['where'],))) {
                    $query .= " WHERE {$params['where']}";
                }

                if (isset($params['orderBy']) && !empty(trim($params['orderBy']))) {
                    $query .= " ORDER BY {$params['orderBy']}";
                    if (isset($params['orderWay']) && ($params['orderWay'] == 'ASC' || $params['orderWay'] == 'DESC')) {
                        $query .= " {$params['orderWay']}";
                    }

                }
                
                if (isset($params['page']) && $params['page'] > 0 && isset($params['limit']) && $params['limit'] > 0) {
                    $offset = ($params['page'] - 1) * $params['limit'];
                    $query .= " LIMIT {$offset} , {$params['limit']}";
                } elseif (isset($params['limit']) && $params['limit'] > 0) {
                    $query .= " LIMIT {$params['limit']}";
                }

                $query .= ";";
                break;

            case 'UPDATE':
                if (!isset($params['set']) || empty($params['set']) ) {
                    return false;
                }

                $sets = [];
                foreach($params['set'] as $field => $value){
                    $sets[] = "{$field} = '{$value}'";
                }

                $query = "UPDATE {$this->table} SET " . implode(',', $sets);
                $where=[];
                if(isset($params['where']) && !empty($params['where'])){
                    foreach($params['where'] as $field => $value){
                        $where[] = "{$field} = '{$value}'";
                    }

                    $query .=" WHERE ". implode(',', $where);
                }

                break;

            case 'CREATE':
                if (!isset($params['element']) || empty(trim($params['element'])) || !isset($params['name']) || empty(trim($params['name']))) {
                    return false;
                }

                $query = "CREATE {$params['element']} {$params['name']}";
                if (!isset($params['attrib']) || empty($params['attrib'])) {
                    return $query;
                }

                $query .= "(";
                foreach ($params['attrib'] as $key => $value) {
                    if (empty(trim($key))||empty(trim($value))) {
                        return false;
                    }
                    $query .= $key . ' ' . $value . ",";
                }

                $query = trim($query, ",");
                $query .= ");";
                break;

            case 'ALTER':
                if (!isset($params['function']) && empty(trim($params['function'])) && !isset($params['name']) && empty(trim($params['name'])) && !isset($params['attr']) && empty(trim($params['attr']))) {
                    return false;
                }

                $query = "ALTER TABLE {$this->table} {$params['function']} {$params['name']} {$params['attr']}";
                break;

            default:
                return false;
                break;

        }

        return $query;
    }

    /**
     * Get Last Error
     * 
     *  
     * @return string
     */

    public function getLastError(): string
    {
        return $this->last_error;
    }

    /**
     * Check if we can connect to DB and if we cant we create it
     * 
     * @return bool
     */

    public function checkDataBase(): bool
    {
        if ($this->getConnection(true) !== false) {
            return true;
        }

        if ($this->code_error != 1049) {
            return false;
        }

        $query = $this->getQuery('CREATE', ["element" => "database", "name" => "{$this->getDataBase()}"]);
        $result = $this->executeS($query, false);
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * Check if database exist and if it isnt we create it
     * @param array attrib
     * 
     * @return bool
     */

    public function checkTable($attrib=[]): bool
    {
        $query = $this->getQuery('SELECT', array('col' => '*'));
        $result = $this->executeS($query);
        if ($result) {
            return true;
        }

        if ($this->code_error == '42S02') {
            $query = $this->getQuery('CREATE', ["element" => "table", "name" => "{$this->getTable()}", "attrib" => $attrib]);
            $result = $this->executeS($query);
            if (!$result) {
                return false;
            }

            return true;
        }

        return false;
    }

}
