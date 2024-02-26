<?php

namespace DCore\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;

#[Command]
class TestCommand extends HyperfCommand
{
    /**
     * @var string|null
     */
    protected $name = 'test';


    public function handle(): void
    {
        $this->line('Hello World.');
    }
}
