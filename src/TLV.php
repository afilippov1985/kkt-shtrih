<?php
namespace Elplat\KktShtrih;

abstract class TLV implements \Stringable
{
    public function __construct(public int $tag, public string $value)
    {
    }

    public function __toString(): string
    {
        return pack('vva*', $this->tag, strlen($this->value), $this->value);
    }
}
