<?php

namespace applications\models\DB\Models;

use applications\models\DB\DB;

class Model extends DB
{
    protected $action;
    protected $table;
    protected $parameters;
    private $command = '';
    private $insert;
    private $columns;
    private $values;
    private $request;

    public function __construct()
    {
        parent::__construct();
    }

    public function customRequest($preparedRequest)
    {
        $this->request = $preparedRequest;
        return $this;
    }

    public function setParams($params)
    {
        $this->parameters = $params;
        return $this;
    }

    public function execute()
    {
        return $this->getResult('rowCount', $this->request);
    }

    public function delete($table)
    {
        $this->table = "FROM {$table} ";
        $this->action = "Delete ";
        return $this;
    }

    public function destroy()
    {
        $command = "{$this->action}{$this->table}{$this->command}";
        return $this->getResult('rowCount', $command);
    }

    public function insert($table, array $items)
    {
        $this->prepareRequest($items);
        $this->insert = "INSERT INTO {$table}({$this->columns}) VALUES {$this->values}";
        return $this;
    }

    private function prepareRequest($items)
    {
        $columnNames = [];
        $parameters = [];
        $numOfValues = 0;
        $valuesToStr = '';
        $columnsToStr = '';

        foreach ($items as $key => $value) {
            $columnsToStr .= $key . ', ';
            $columnNames[] = $key;

            if (count($items[$key]) > $numOfValues) {
                $numOfValues = count($items[$key]);
            }
        }

        for($i = 0; $i <= $numOfValues - 1 ; $i++) {
            for($k = 0; $k <= count($columnNames) - 1; $k++ ) {
                $parameters[] = trim(strip_tags($items[$columnNames[$k]][$i]));

                if (count($columnNames) === 1) {
                    $valuesToStr .= "(?), ";
                } else {
                    switch ($k) {
                        case 0 :
                            $valuesToStr .= "(?, ";
                            break;

                        case count($columnNames) - 1:
                            $valuesToStr .= "?), ";
                            break;

                        default :
                            $valuesToStr .= "?, ";
                            break;
                    }
                }
            }
        }

        $this->parameters = $parameters;
        $this->columns = trim($columnsToStr, ", ");
        $this->values = trim($valuesToStr, ", ");
    }

    public function save()
    {
        $command = "{$this->insert}";
        return $this->getResult('rowCount', $command);
    }

    public function select($table, $items)
    {
        $this->table = "FROM {$table} ";
        $this->action = "SELECT {$items} ";
        return $this;
    }

    public function get()
    {
        $command = "{$this->action}{$this->table}{$this->command}";
        return $this->getResult('fetchAll', $command);
    }

    public function first()
    {
        $command = "{$this->action}{$this->table}{$this->command}";
        return $this->getResult('fetch', $command);
    }

    public function where($column, $operator, $values)
    {
        $placeholders = '';

        foreach ($values as $key => $value) {
            $this->parameters['param' . $key] = trim(strip_tags($value));
            $placeholders .= ':param' . $key . ', ';
        }

        $placeholders = trim($placeholders, ', ');

        $placeholders = ($operator === 'in') ? "(" . $placeholders . ") " : $placeholders . " ";

        $this->command .= "WHERE {$column} {$operator} {$placeholders}";
        return $this;
    }

    public function join($table, $first, $operator, $second)
    {
        $this->command .= "JOIN {$table} ON {$first} {$operator} {$second} ";
        return $this;
    }

    public function orderBy($column, $type)
    {
        $this->command .= "ORDER BY {$column} {$type} ";
        return $this;
    }

    protected function getResult($method, $command)
    {
        /*var_dump($sth);
        exit();*/
        try {
            $sth = $this->PDO()->prepare(trim($command, ', '));
            $sth->execute($this->parameters);

            return $sth->$method();
        } catch (\PDOException $e) {
            $errorCode = $sth->errorInfo()[0];
            $errorMessage = $sth->errorInfo()[2];
            $this->PDO()->rollBack();

            if ($errorCode == '42S22') {
                $throwCode = 404;
            } else {
                $throwCode = 501;
            }

            throw new \Exception($errorMessage, $throwCode);
        }
    }
}