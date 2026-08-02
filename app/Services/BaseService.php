<?php

namespace App\Services;

class BaseService
{
    protected $model;

    public function index(){return $this->model->paginate(10);}

    public function archiveIndex(){return $this->model->onlyTrashed()->get();}

    public function store($data){return $this->model->create($data);}

    public function update($id, $data){$record = $this->model->findOrFail($id);$record->update($data);return $record;}

    public function destroy($id){return $this->model->withTrashed()->find($id)->forceDelete();}

    public function archive($id){$record = $this->model->findOrFail($id); return $record->delete();}

    public function restore($id){return $this->model->onlyTrashed()->where('id', $id)->restore();}

    public function deleteMultiple($ids){return $this->model->withTrashed()->whereIn('id', $ids)->forceDelete();}

    public function archiveMultiple($ids){return $this->model->whereIn('id', $ids)->delete();}

    public function restoreMultiple($ids){return $this->model->onlyTrashed()->whereIn('id', $ids)->restore();}




}
