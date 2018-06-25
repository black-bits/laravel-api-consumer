<?php
/**
 * Created by PhpStorm.
 * User: SRoquebert
 * Date: 25/06/2018
 * Time: 17:34
 */
namespace BlackBits\ApiConsumer\Support\Traits;

use BlackBits\ApiConsumer\Contracts\ApiHandlerContract;
use Illuminate\Support\Collection;

/**
 * Trait HasRestCapacity
 * @mixin \BlackBits\ApiConsumer\Support\Endpoint
 */
trait HasRestCapacity
{
    protected $hasWhereCapacity = true;

    /**
     * @throws \Exception
     */
    public function all():Collection {
        return $this->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     * @throws \Exception
     */
    public function findById($id):Collection {
        $this->path.='/'.$id;
        return $this->get();
    }

    /**
     * @param $wheres
     * @return ApiHandlerContract
     * @throws \Exception
     */
    public function applyWheres($wheres): ApiHandlerContract
    {
        $this->checkHasWhereCapacity();
        foreach ($wheres as $where) {
            $this->checkCapacity($where);
            $this->options[$where['column']] = $where['value'];
        }
        return $this;
    }


}