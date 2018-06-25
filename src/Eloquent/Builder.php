<?php
/**
 * Created by PhpStorm.
 * User: SRoquebert
 * Date: 21/06/2018
 * Time: 16:05
 */

namespace BlackBits\ApiConsumer\Eloquent;

use BlackBits\ApiConsumer\Contracts\ApiHandlerContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @property Model model
 */

class Builder extends EloquentBuilder
{
    protected $apiData = [];

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @throws \Exception
     */
    public function get($columns = ['*'])
    {
        $builder = $this->applyScopes();
        $wheres = $builder->getQuery()->wheres;

        if (0 === count($wheres)) {
            $this->apiData = $builder->getApiHandler()->all()->toArray();
        } elseif (count($wheres) === 1 && ends_with($wheres[0]['column'], '.id')) {
            $this->apiData = $this->getApiHandler()->findById($wheres[0]['value'])->toArray();
        } else {
            $this->apiData = $this->getApiHandler()->applyWheres($wheres)->get()->toArray();
        }

        $models = $builder->getModels($columns);
        //Todo Handle eager loading
//        if (count($models) > 0) {
//            $models = $builder->eagerLoadRelations($models);
//        }

        return $builder->getModel()->newCollection($models);
    }

    /**
     * @return ApiHandlerContract
     */
    private function getApiHandler()
    {
        $apiConsumerClass = $this->model->getApiConsumer();
        $endpoint = $this->model->getEndpoint();
        return $apiConsumerClass::$endpoint();
    }

    /**
     * Get the hydrated models without eager loading.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model[]
     */
    public function getModels($columns = ['*'])
    {
        return $this->model->hydrate($this->apiData)->all();
    }

}