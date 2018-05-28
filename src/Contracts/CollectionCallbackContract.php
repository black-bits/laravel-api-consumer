<?php

namespace BlackBits\ApiConsumer\Contracts;

use Illuminate\Support\Collection;

interface CollectionCallbackContract
{
    function applyTo(Collection &$collection) : Collection;
}