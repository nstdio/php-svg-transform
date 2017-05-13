<?php

namespace nstdio\svg\transform;

/**
 * Interface Observer
 *
 * @package nstdio\svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface Observer
{
    public function remove(SVGTransform $item);
}