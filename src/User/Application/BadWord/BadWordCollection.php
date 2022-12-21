<?php

namespace App\User\Application\BadWord;

class BadWordCollection
{
    private array $badWords;

    public function __construct(array $badWords = [])
    {
        $this->badWords = $badWords;
    }

    public function toArray(): array
    {
        return $this->badWords;
    }
}
