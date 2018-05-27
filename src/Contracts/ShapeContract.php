<?php

namespace BlackBits\ApiConsumer\Contracts;

interface ShapeContract
{
    static function create($data);

    /*
    function isReturnShapeDataOnly(): bool;
    function isRequireShapeStructure(): bool;
    function getFields(): array;
    function getTransformations(): array;
    function set($key, $value);
    function validateStructure();
    */
}