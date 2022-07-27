<?php
namespace Elplat\KktShtrih;

class STLV extends TLV
{
    /**
     * @param int $tag
     * @param TLV[] $tlvs
     */
    public function __construct(int $tag, array $tlvs)
    {
        parent::__construct($tag, implode('', $tlvs));
    }
}
