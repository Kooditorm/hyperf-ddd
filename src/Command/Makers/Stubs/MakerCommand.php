<?php

namespace DCore\Command\Makers\Stubs;

use Hyperf\Command\Command as HyperfCommand;

class MakerCommand extends HyperfCommand
{


    protected $signature = 'make:ant {table} {path} {--d|del}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'make file for ant project';

    public function __construct()
    {
        $this->signature = 'make:'.env('APP_NAME').' {table} {path}  {--d|del}';
        parent::__construct();
    }

    public function handle()
    {

    }
}
