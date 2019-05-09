<?php

namespace Husky\Commands;

use Composer\Command\BaseCommand as CBaseCommand;

abstract class BaseCommand extends CBaseCommand
{
    /** @var string */
    protected $package = 'husky';

    /** @var string  */
    protected $binFile = 'husky-php';

    /** @var string */
    protected $separator = ':';

    /** @var string */
    protected $vendor = 'vendor/';

    /** @var string */
    protected $huskyPath = '';


    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->huskyPath = $this->vendor . 'bin/' . $this->binFile;
    }

    /**
     * @param string $name
     *
     * @return CBaseCommand
     */
    public function setName($name)
    {
        $name = $this->package . $this->separator . $name;

        return parent::setName($name);
    }
}