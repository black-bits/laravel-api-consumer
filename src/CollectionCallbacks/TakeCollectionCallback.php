<?php

namespace BlackBits\ApiConsumer\CollectionCallbacks;

use BlackBits\ApiConsumer\Support\BaseCollectionCallback;
use Illuminate\Support\Collection;

class TakeCollectionCallback extends BaseCollectionCallback
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    function applyTo(Collection $collection) : Collection
    {
        return $collection->take($this->value);
    }
}
