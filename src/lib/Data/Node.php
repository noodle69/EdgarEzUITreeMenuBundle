<?php

namespace Edgar\EzUITreeMenu\Data;

class Node
{
    /** @var string */
    public $text;

    /** @var string */
    public $href;

    /** @var array */
    public $nodes;

    /** @var int */
    public $locationId;

    /** @var string */
    public $pathString;

    /** @var string */
    public $action;

    /** @var array */
    public $tags;
}
