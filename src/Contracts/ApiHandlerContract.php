<?php
/**
 * Created by PhpStorm.
 * User: SRoquebert
 * Date: 25/06/2018
 * Time: 15:27
 */

namespace BlackBits\ApiConsumer\Contracts;


use Illuminate\Support\Collection;

interface ApiHandlerContract
{
    public function get():Collection;

    public function findById($id):Collection;

    public function all():Collection;

    public function applyWheres($wheres): ApiHandlerContract;
}