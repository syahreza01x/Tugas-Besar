<?php

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all()
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table}");
    }

    public function find($id)
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id",
            ['id' => $id]
        );
    }

    public function findBy($column, $value)
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$column} = :value",
            ['value' => $value]
        );
    }

    public function where($column, $value)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$column} = :value",
            ['value' => $value]
        );
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->update(
            $this->table,
            $data,
            "{$this->primaryKey} = :where_id",
            ['where_id' => $id]
        );
    }

    public function delete($id)
    {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = :id",
            ['id' => $id]
        );
    }

    public function count($column = '*', $where = null, $params = [])
    {
        $sql = "SELECT COUNT({$column}) as count FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $result = $this->db->fetch($sql, $params);
        return $result->count;
    }

    public function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }

    public function fetch($sql, $params = [])
    {
        return $this->db->fetch($sql, $params);
    }

    public function fetchAll($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }
}
