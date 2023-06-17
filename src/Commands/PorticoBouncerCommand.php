<?php

namespace Weward\PorticoBouncer\Commands;

use Illuminate\Console\Command;

class PorticoBouncerCommand extends Command
{
    public $signature = 'porticobouncer';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
