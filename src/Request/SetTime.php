<?php
namespace Elplat\KktShtrih\Request;

class SetTime extends AbstractRequest
{
    public int $hours;

    public int $minutes;

    public int $seconds = 0;

    public function __construct()
    {
        $this->command = 0x21;
    }

    public function __toString(): string
    {
        return pack(
            'CVCCC',
            $this->command,
            $this->password,
            $this->hours,
            $this->minutes,
            $this->seconds,
        );
    }
}
