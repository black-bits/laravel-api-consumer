<?php

namespace BlackBits\ApiConsumer\CollectionCallbacks;

use BlackBits\ApiConsumer\Support\BaseCollectionCallback;
use Illuminate\Support\Collection;

class WhereCollectionCallback extends BaseCollectionCallback
{
    private $field;
    private $value;

    /**
     * WhereCollectionCallback constructor.
     * @param $field
     * @param $value
     */
    public function __construct($field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    function applyTo(Collection $collection) : Collection
    {
        return $collection->where($this->field, $this->value);
    }
}
