<?php
require_once __DIR__ . '/Table.php';
require_once __DIR__ . '/User.php';

class PaginationTable
{
    private $db;
    private $select = '*';
    private $page = 0;
    private $limit = 0;
    private $selectors = [];
    private $lastError = '';
    private $paramsQuery = [];
    private $orderBy = "";
    private $orderWay = "";
    private $where = "";
    private $new_columns = [];

    public function __construct(Db $db, string $table,int $page, int $limit, array $new_columns,string $orderBy = "id", string $orderWay = "ASC",string $where="", string $select = '*', array $selectors = [5, 10, 20, 30])
    {
        $this->db = $db;
        $this->page = $page;
        $this->limit = $limit;
        $this->orderBy = $orderBy;
        $this->orderWay = $orderWay;
        $this->select = $select;
        $this->selectors = $selectors;
        $this->where = $where;
        $this->new_columns = $new_columns;
        $this->db->setTable($table);
    }

    /**
     * Get table
     * 
     * @param bool 
     * 
     * @return $ajax true = Return array tbody and paginator[]
     * @return bool|string
     */

    public function get($ajax = false)
    {
        $this->paramsQuery = ["col" => $this->select, "page" => $this->page, "limit" => $this->limit, "orderBy" => $this->orderBy, "orderWay" => $this->orderWay, "where"=>$this->where];
        $query = $this->db->getQuery('SELECT', $this->paramsQuery);
        if (!$query) {
            $this->lastError = $this->db->getLastError();
            return false;
        }
        
        // Get data 
        $data = $this->db->executeS($query);
        if (!$data) {
            $this->lastError = $this->db->getLastError();
            return false;
        }

        // Get total pages
        $pages = $this->getTotalPages();

        // Get data table
        $dataTable = $data->fetchAll(PDO::FETCH_CLASS, "User");

        if ($ajax) {
            // var_dump($query);
            if ($this->page > $pages) {
                $this->page = $pages;
                return $this->get($ajax);
            }
            $tbody = Table::getContent($dataTable, $this->new_columns);
            $paginator = Table::createPagination($pages, $this->page);
            return ['tbody' => $tbody, 'paginator' => $paginator];
        }

        // Get table
        $table = new Table($dataTable, $this->selectors, $pages, $this->new_columns);
        return $table->getTable();
    }

    /**
     * Get total pages
     * 
     * @return int $countTotal
     */

    public function getTotalPages(): int
    {
        if ($this->limit < 1) {
            return 0;
        }

        $params = ['col' => isset($this->paramsQuery['col']) ? $this->paramsQuery['col'] : '*'];
        $query = $this->db->getQuery('SELECT', $params);
        if (!$query) {
            $this->lastError = $this->db->getLastError();
            return 0;
        }

        $data = $this->db->executeS($query);
        if (!$data) {
            $this->lastError = $this->db->getLastError();
            return 0;
        }

        $countTotal = $data->rowCount();
        $countTotal = $countTotal / $this->limit;
        if (gettype($countTotal) == "double") {
            $countTotal = floor($countTotal);
            $countTotal++;
            $countTotal = intval($countTotal);
        }
        // echo $countTotal;

        return $countTotal;
    }

    /**
     * Get last error
     * 
     * @return string
     */

    public function getLastError(): string
    {
        return $this->lastError;
    }
}

