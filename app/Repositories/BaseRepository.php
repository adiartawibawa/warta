<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Get record by id.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get record by column.
     *
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update record by id.
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        $record = $this->model->find($id);
        return $record->update($data);
    }

    /**
     * Delete record by id.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $record = $this->find($id);
        if ($record) {
            return $record->delete(); // Soft Delete
        }
        return false;
    }

    /**
     * Force delete record by id.
     */
    public function forceDelete($id)
    {
        $record = $this->find($id);
        if ($record) {
            return $record->forceDelete(); // Hard Delete
        }
        return false;
    }

    /**
     * Restore record by id.
     */
    public function restore($id)
    {
        $record = $this->find($id);
        if ($record) {
            return $record->restore();
        }
        return false;
    }

    /**
     * Get all records with trashed.
     */
    public function allWithTrashed()
    {
        return $this->model->withTrashed()->get();
    }

    /**
     * Get all trashed records.
     */
    public function onlyTrashed()
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Find record with trashed by id.
     */
    public function findWithTrashed($id)
    {
        return $this->model->withTrashed()->find($id);
    }

    /**
     * Find only trashed record by id.
     */
    public function findOnlyTrashed($id)
    {
        return $this->model->onlyTrashed()->find($id);
    }

    /**
     * Paginate all records with trashed.
     */
    public function paginateWithTrashed($perPage = 10)
    {
        return $this->model->withTrashed()->paginate($perPage);
    }

    /**
     * Paginate only trashed records.
     */
    public function paginateOnlyTrashed($perPage = 10)
    {
        return $this->model->onlyTrashed()->paginate($perPage);
    }
}
