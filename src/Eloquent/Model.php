<?php
/**
 * Created by PhpStorm.
 * User: SRoquebert
 * Date: 21/06/2018
 * Time: 15:52
 */
namespace BlackBits\ApiConsumer\Eloquent;

use BlackBits\ApiConsumer\Eloquent\Database\Connection;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    protected $apiConsumer = null;
    protected $endpoint = null;

    /**
     * @param $query
     * @return Builder
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * @return string
     */
    public function getApiConsumer()
    {
        return $this->apiConsumer;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return  $this->endpoint ?? (new \ReflectionClass(get_class($this)))->getShortName();
    }

    /**
     * @param $value
     * @return string
     */
    public function getIdAttribute($value)
    {
        return (string)$value;
    }
}