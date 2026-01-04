<?php

class Status extends Model
{
    protected $table = 'statuses';

    public function getAllStatuses()
    {
        return $this->all();
    }

    public function getStatusByName($name)
    {
        return $this->findBy('name', $name);
    }
}
