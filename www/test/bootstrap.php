<?php

use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

$phinx = new PhinxApplication();
$phinx->setAutoExit(false);
$phinx->run(new StringInput('migrate -e testing'), new NullOutput());
