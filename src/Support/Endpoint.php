<?php

namespace BlackBits\ApiConsumer\Support;

use BlackBits\ApiConsumer\CollectionCallbacks\_ReflectionCollectionCallback;
use BlackBits\ApiConsumer\Contracts\CollectionCallbackContract;
use BlackBits\ApiConsumer\Support\ShapeResolver;
use Illuminate\Support\Collection;
use Zttp\Zttp;
use Illuminate\Support\Facades\Cache;
use Zttp\ZttpResponse;


abstract class Endpoint
{
    private $basePath;
    private $shapeResolver;
    private $collectionCallbacks = [];

    protected $headers = [];
    protected $options = [];
    protected $path;
    protected $method;

    protected $shouldCache = false;
    protected $cacheDurationInMinutes = 5;

    protected $authorizedWhereTypes = [];

    /**
     * @throws \Exception
     */
    protected function checkHasWhereCapacity(): void
    {
        if (!$this->hasWhereCapacity()) {
            throw new \Exception('Endpoint: "' . get_class($this) . '" has no "where capacity", request cannot be handled');
        }
    }

    /**
     * @param $where
     * @throws \Exception
     */
    protected function checkCapacity($where)
    {
        if (!in_array($where['type'], $this->authorizedWhereTypes)) {
            throw new \Exception('Endpoint: "' . get_class($this) . '" has no "'.$where['type'].'" where capacity", request cannot be handled');
        }
    }

    protected function hasWhereCapacity()
    {
        return $this->hasWhereCapacity;
    }

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
     * @return string
     */
    private function getCacheKey()
    {
        $key = $this->method . "-" . $this->uri();

        if(!empty($this->options)) {
            $value = $this->options;
            if (is_array($value)) {
                $value = http_build_query($value, null, '&', PHP_QUERY_RFC3986);
            }
            if (is_string($value)) {
                $key .= "-" . $value;
            }
        }

        return $key;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function request()
    {

        if (strtolower($this->method) == "get") {

            if ($this->shouldCache) {
                return Cache::remember($this->getCacheKey(), $this->cacheDurationInMinutes, function () {
                    return $this->handledRequest();
                });
            }
            return $this->handledRequest();
        }

        // TODO: other Methods
        return "[]";
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
    final public function get():Collection
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
        $collectionCallback =  "\App\CollectionCallbacks\\" . ucfirst($name) . "CollectionCallback";

        if (!class_exists($collectionCallback)) {
            $collectionCallback =  "\BlackBits\ApiConsumer\CollectionCallbacks\\" . ucfirst($name) . "CollectionCallback";
        }

        if (!class_exists($collectionCallback)) {
            $this->registerCollectionCallback(
                (new _ReflectionCollectionCallback(... $arguments))->setMethod($name)
            );
            return $this;
        }

        $this->registerCollectionCallback(new $collectionCallback(... $arguments));
        return $this;
    }

    /**
     * @param array $headers
     * @return Endpoint
     */
    public function addHeaders(array $headers): Endpoint
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function handledRequest()
    {
        /**
         * @var ZttpResponse $response
         */
        $response = Zttp::withHeaders($this->headers)->get($this->uri(), $this->options);
        if (!$response->isOk()) {
            $errors = '';
            //Todo use a pattern that allows error reporting structure to be specified at API or endpoint level
            if ($body = json_decode($response->body(), true)) {
                if (!empty($body['errors'])) {
                    $errors = is_array($body['errors']) ? implode($body['errors'], ",\n") : $body['errors'];
                } elseif (!empty($body['message'])) {
                    $errors = $body['message'];
                }
            }
            throw new \Exception('Error while fetching data from "'.$this->uri().'": '."\n".$errors);
        }
        return $response->body();
    }
}