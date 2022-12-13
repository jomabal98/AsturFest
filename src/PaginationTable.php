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
    private $fieldsTranslated = [];
    private $rol = "";

    public function __construct(Db $db, string $rol, string $table, int $page, int $limit, array $new_columns, array $fieldsTranslated, string $orderBy = "id", string $orderWay = "ASC", string $where = "", string $select = '*', array $selectors = [5, 10, 20, 30])
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
        $this->fieldsTranslated = $fieldsTranslated;
        $this->db->setTable($table);
        $this->rol = $rol;
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
        $this->paramsQuery = ["col" => $this->select, "page" => $this->page, "limit" => $this->limit, "orderBy" => $this->orderBy, "orderWay" => $this->orderWay, "where" => $this->where];
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
            if ($this->page > $pages) {
                $this->page = $pages;
                return $this->get($ajax);
            }

            $tbody = Table::getContent($dataTable, $this->rol, $this->new_columns);
            $paginator = Table::createPagination($pages, $this->page);
            return ['tbody' => $tbody, 'paginator' => $paginator];
        }

        // Get table
        $table = new Table($dataTable, $this->rol, $this->selectors, $pages, $this->new_columns, $this->fieldsTranslated);
        $resul = $this->getModal($this->rol);
        $resul .= $table->getTable();
        return $resul;
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

        $col = ['col' => isset($this->paramsQuery['col']) ? $this->paramsQuery['col'] : '*'];
        if (isset($this->paramsQuery['col'])) {
            $col = $this->paramsQuery['col'];
        } else {
            $col = '*';
        }

        if (isset($this->paramsQuery['where'])) {
            $where = $this->paramsQuery['where'];
            $params = ["col" => $col, "where" => $where];
        } else {
            $params = ["col" => $col];
        }

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

    public function getModal($rol)
    {
        if ($rol != "admin") {
            return;
        }

        $insert = '<div class="container "><button type="button" class="insert-button btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">INSERT</button></div>';
        $insert .= '<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Nuevo registro</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form>';
        foreach ($this->fieldsTranslated as $field) {
            if (!isset($field['type'])) {
                continue;
            }
            $insert .= "<div class='mb-3'><label for='{$field['translator']}' class='col-form-label'>{$field['translator']}:</label>";
            $insert .= "<input type='{$field['type']}' class='form-control {$field['translator']}'></div>";
        }
        $insert .= '</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary send">Insertar</button></div></div></div></div></div>';
        return $insert;
    }
}
