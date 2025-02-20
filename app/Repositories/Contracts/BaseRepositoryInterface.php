<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function all();

    public function find($id);

    public function findWhere($column, $value);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function forceDelete($id);

    public function restore($id);

    public function allWithTrashed();

    public function onlyTrashed();

    public function findWithTrashed($id);

    public function findOnlyTrashed($id);

    public function paginateWithTrashed($perPage = 10);

    public function paginateOnlyTrashed($perPage = 10);
}
