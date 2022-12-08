<?php
class Table
{
    private $table;
    private static $new_columns;
    private static $translatorFields;

    public function __construct(array $dataTable, array $selectors, int $pages, array $new_columns, array $translatorFields)
    {
        self::$new_columns = $new_columns;
        self::$translatorFields = $translatorFields;
        $this->table = $this->get($dataTable, $selectors, $pages, $translatorFields);
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

    private function get(array $dataTable, array $selectors, int $pages, array $translatorFields): string
    {
        if (empty($dataTable)) {
            return '';
        }

        $table = '<div class="container">';
        $table .= $this->createSelector($selectors);
        $table .= '<table class="table table-striped">';
        $arrayClaves = array_keys(get_object_vars($dataTable[0]));
        $table .= $this->createHeader($arrayClaves, $translatorFields);
        $table .= self::getContent($dataTable, self::$new_columns);
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
        foreach ($columns as $key => $column) {
            if ($key == "photo" && $tag == "td" && !empty($column)) {
                $column = '<img src="' . $column . '" width="50" height="50">';
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
        if ($value === '0') {
            return  "<{$tag} scope='col'></{$tag}>";
        }
        return  "<{$tag} scope='col'>" . $value . "</{$tag}>";
    }

    /**
     * generates tbody
     * 
     * @param array $dataTable
     * 
     * @return string $tbody
     */

    static public function getContent(array $dataTable, array $new_columns = []): string
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
        $select .= '<input type="date" id="date1">		';
        $select .= '<input type="date" id="date2">		';
        $select .= '<button class="btn btn-outline-primary search">SEARCH</button><br><br>';
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

        $pagination = '<nav class="nav_pagination"><ul class="pagination">';
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
