<?php

namespace App\Services;

class BaseService
{
    protected $model;
      
    public function index(){return $this->model->paginate(10);}

    public function store($data){return $this->model->create($data);}

    public function update($id, $data){$record = $this->model->findOrFail($id);$record->update($data);return $record;}

    public function destroy($id){return $this->model->destroy($id);}

    public function archive($id){$record = $this->model->findOrFail($id); return $record->delete();}

    public function deleteMultiple($ids){return $this->model->whereIn('id', $ids)->destroy();}

    public function archiveMultiple($ids){return $this->model->whereIn('id', $ids)->delete();}      
}
