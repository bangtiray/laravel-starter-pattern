<?php

namespace App\Repositories;


class BaseRepository
{
    public function getAll()
    {
        return $this->query()->get();
    }
    
    public function getPaginated($per_page, $active = '', $order_by = 'id', $sort = 'asc')
    {
        if ($active) {
            return $this->query()->where('status', $active)
                ->orderBy($order_by, $sort)
                ->paginate($per_page);
        } else {
            return $this->query()->orderBy($order_by, $sort)
                ->paginate($per_page);
        }
    }

    public function getCount()
    {
        return $this->query()->count();
    }

    public function find($id)
    {
        return $this->query()->find($id);
    }

    public function query()
    {
        return call_user_func(static::MODEL.'::query');
    }
}
