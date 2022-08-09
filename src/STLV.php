<?php
namespace Elplat\KktShtrih;

class STLV extends TLV implements \Countable
{
    private array $tlvs;

    /**
     * @param int $tag
     * @param TLV[] $tlvs
     */
    public function __construct(int $tag, array $tlvs = [])
    {
        $this->tlvs = $tlvs;
        parent::__construct($tag, implode('', $this->tlvs));
    }

    public function add(TLV $tlv)
    {
        $this->tlvs[] = $tlv;
        $this->value = implode('', $this->tlvs);
    }

    public function count(): int
    {
        return count($this->tlvs);
    }
}
