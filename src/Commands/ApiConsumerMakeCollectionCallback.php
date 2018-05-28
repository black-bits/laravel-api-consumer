<?php

namespace BlackBits\ApiConsumer\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ApiConsumerMakeCollectionCallback extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:api-consumer-collection-callback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api collection callback class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'CollectionCallback';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/api-consumer-collection-callback.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\CollectionCallbacks';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the collection callback already exists.'],
        ];
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return preg_replace('/CollectionCallback$/', '', trim($this->argument('name'))) . "CollectionCallback";
    }

}
