<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 06/03/2016
 * Time: 19:41
 */

namespace CodeProject\Repositories;


abstract class BaseRepository extends \Prettus\Repository\Eloquent\BaseRepository
{
    /**
     * Find data by id
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->find($id, $columns);
        $this->resetModel();
        return $this->parserResult($model);
    }
}