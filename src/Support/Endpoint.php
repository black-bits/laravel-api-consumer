<?php

namespace BlackBits\ApiConsumer\Support;

use BlackBits\ApiConsumer\Contracts\CollectionCallbackContract;
use BlackBits\ApiConsumer\Support\ShapeResolver;
use Zttp\Zttp;

abstract class Endpoint
{
    private $basePath;
    private $shapeResolver;
    private $collectionCallbacks = [];

    protected $options = [];
    protected $path;
    protected $method;

    /**
     * Endpoint constructor.
     * @param $basePath
     * @param ShapeResolver $shapeResolver
     */
    public function __construct($basePath, ShapeResolver $shapeResolver)
    {
        $this->basePath      = $basePath;
        $this->shapeResolver = $shapeResolver;
    }

    /**
     * @return string
     */
    private function uri()
    {
        return $this->basePath . "/" . ltrim($this->path, "/");
    }

    /**
     * @return mixed
     */
    private function request()
    {
        switch ($this->method) {
            case "GET":
                return Zttp::get($this->uri(), $this->options)->body();
        }
    }

    /**
     * @param CollectionCallbackContract $collectionCallback
     */
    private function registerCollectionCallback(CollectionCallbackContract $collectionCallback)
    {
        $this->collectionCallbacks[] = $collectionCallback;
    }

    /**
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     * @throws \Exception
     */
    final public function get()
    {
        $this->method = "GET";

        $collection = $this->shapeResolver->resolve($this->request());

        /** @var CollectionCallbackContract $callback */
        foreach ($this->collectionCallbacks as $callback) {
            $collection = $callback->applyTo($collection);
        }

        return $collection;
    }

    /**
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     * @throws \Exception
     */
    final public function first()
    {
        return $this->get()->first();
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $collectionCallback =  "\BlackBits\ApiConsumer\CollectionCallbacks\\" . ucfirst($name) . "CollectionCallback";

        if (!class_exists($collectionCallback)) {
            throw new \Exception("Class $collectionCallback does not exist.");
        }

        $this->registerCollectionCallback(new $collectionCallback(... $arguments));

        return $this;
    }

}