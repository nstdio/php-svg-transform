<?php

namespace nstdio\svg\transform;

/**
 * Class Observable
 *
 * @package nstdio\svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface Observable
{
    public function attach(Observer $observer);
}