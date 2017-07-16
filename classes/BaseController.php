<?php

abstract class BaseController
{
   	protected $ci;

    public function __construct(Interop\Container\ContainerInterface $container) {
        $this->ci = $container;
    }
}
