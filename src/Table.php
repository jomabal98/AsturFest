<?php

// use LDAP\Result;

require_once __DIR__ . '/Db.php';
class Table
{
    private $table;
    private static $new_columns;
    private static $translatorFields;

    public function __construct(array $dataTable, string $rol, array $selectors, int $pages, array $new_columns, array $translatorFields)
    {
        self::$new_columns = $new_columns;
        self::$translatorFields = $translatorFields;
        $this->table = $this->get($dataTable, $rol, $selectors, $pages, $translatorFields);
    }

    /**
     * return table
     * 
     * @return string $table
     */

    public function getTable()
    {
        return $this->table;
    }

    /**
     * get full table
     * 
     * @param array $database
     * @param array $selectors
     * @param int $pages
     * 
     * @return string
     */

    private function get(array $dataTable, string $rol, array $selectors, int $pages, array $translatorFields): string
    {
        if (empty($dataTable)) {
            return '';
        }

        $table = '<div class="container table-responsive">';
        $table .= $this->createSelector($selectors);
        $table .= '<table class="table table-striped">';
        $arrayClaves = array_keys(get_object_vars($dataTable[0]));
        $table .= $this->createHeader($arrayClaves, $translatorFields);
        $table .= self::getContent($dataTable, $rol, self::$new_columns);
        $table .= '</table>';
        $table .= self::createPagination($pages);
        $table .= '</div>';
        return $table;
    }

    /**
     * create header
     * 
     * @param array $array
     * 
     * @return string $header
     */

    public function createHeader(array $array, array $translatorFields): string
    {
        if (!empty(self::$translatorFields)) {
            $translatorFields = array_combine(array_keys($translatorFields), array_column($translatorFields, 'translator'));
            return '<thead>' . self::getRow($translatorFields, 'th') . '</thead>';
        }

        if (!empty(self::$new_columns)) {
            $array = array_merge((array)$array, array_keys(self::$new_columns));
        }
        return '<thead>' . self::getRow($array, 'th') . '</thead>';
    }

    /**
     * create row
     * 
     * @param array|object $columns
     * @param string $tag
     * 
     * @return string $tr
     */

    static public  function getRow($columns, string $tag = 'td'): string
    {
        $tr = '<tr>';
        $id = 0;
        $fav = "<path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/>";
        $nofav = "<path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>";
        foreach ($columns as $key => $column) {
            if ($key == "id") {
                $id = $column;
            }

            if ($key == "photo" && $tag == "td" && !empty($column)) {
                $column = '<img src="' . $column . '" width="50" height="50">';
            }

            if ($key == "name" && $tag == "td" && !empty($column)) {
                $tr .= '<td class="name" scope="col">' . $column . '</td>';
                continue;
            }

            if ($key == "description") {
                continue;
            }

            if ($key == "") {
                $tr .= '<td class="acction" scope="col">' . $column . '</td>';
                continue;
            }

            if ($key == "favs") {
                if (!isset($_SESSION['id'])) {
                    $result=false;
                }else{
                    $db = new Db();
                    $db->setTable('favorites');
                    $query = $db->getQuery('SELECT', ["col" => "*", "where" => "idUser={$_SESSION['id']} AND idEvent={$id}"]);
                    $result = $db->executeS($query);
                }

                if (!$result || $result->rowCount()<=0) {
                    $column = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-heart' viewBox='0 0 16 16'>
                    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
                    </svg>";
                } else {
                    $column = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-heart' viewBox='0 0 16 16'>
                    <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/>
                    </svg>";
                }
            }
            $tr .= self::getColumn($column, $tag);
        }

        return $tr . '</tr>';
    }

    /**
     * create column
     * 
     * @param string $value
     * @param string $tag
     * 
     * @return string $td
     */

    static public  function getColumn(string $value, string $tag = 'td'): string
    {
        return  "<{$tag} scope='col'>" . $value . "</{$tag}>";
    }

    /**
     * generates tbody
     * 
     * @param array $dataTable
     * 
     * @return string $tbody
     */

    static public function getContent(array $dataTable, string $rol, array $new_columns = []): string
    {
        if (empty($dataTable)) {
            return '';
        }

        $tbody = "<tbody>";
        foreach ($dataTable as $value) {
            if (!empty($new_columns)) {
                $value = array_merge((array) $value, $new_columns);
            }

            $tbody .= self::getRow($value);
        }

        return $tbody . '</tbody>';
    }

    /**
     * create input to select number of elements
     * 
     * @param array $selectors
     * 
     * @return string $select
     */

    public function createSelector(array $selectors): string
    {
        if (empty($selectors)) {
            return '';
        }

        $select = '<select class="form-select" style="width: 100px;">';
        foreach ($selectors as $selector) {
            $select .= "<option value='{$selector}'>{$selector}</option>";
        }

        $select .= '</select><br>';
        $select .= '<div class="input-search"><input type="date" id="date1">		';
        $select .= '<input type="date" id="date2">		';
        $select .= '<button class="btn btn-outline-primary search">SEARCH</button></div><br><br>';
        return  $select;
    }

    /**
     * create pagination
     * 
     * @param int $num
     * @param int $active
     * 
     * @return string pagination
     */

    static public function createPagination(int $pages, int $active = 1): string
    {
        if ($pages < 1 || $active < 1) {
            return '';
        }

        if ($active > $pages) {
            $active = $pages;
        }

        $pagination = '<nav class=nav_pagination><ul class="pagination">';
        for ($page = 1; $page <= $pages; $page++) {
            $pagination .= "<li class='page-item ";
            if ($active == $page) {
                $pagination .= "active";
            }

            $pagination .= "'><div class='page-link'>{$page}</div></li>";
        }

        return $pagination . '</ul></nav>';
    }
}
