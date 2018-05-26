<?php

namespace BlackBits\ApiConsumers\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ApiConsumerShapeMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:api-consumer-shape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api consumer shape class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Shape';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->option('consumer'))) {
            $this->error("'consumer' option is required (-c)");
            return;
        }

        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }

        $this->callSilent('make:api-consumer-endpoint', [
            'name' => str_replace('Shape', 'Endpoint', $this->getNameInput()),
            '-c' => $this->options('consumer')['consumer']
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/api-consumer-shape.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\ApiConsumers\\' . $this->option('consumer') . '\Shapes';
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the shape already exists.'],

            ['consumer', 'c', InputOption::VALUE_REQUIRED, 'Create the shape for this ApiConsumer endpoint'],

        ];
    }

}
