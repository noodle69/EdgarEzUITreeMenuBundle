<?php

namespace Edgar\EzUITreeMenu\Data;

class Node
{
    /** @var int */
    public $id;

    /** @var string */
    public $text;

    /** @var array */
    public $li_attr;

    /** @var array */
    public $a_attr;

    /** @var array */
    public $state;

    /** @var ?array */
    public $children;

    /** @var string */
    public $type;

    /** @var ?string */
    public $icon = null;
}
