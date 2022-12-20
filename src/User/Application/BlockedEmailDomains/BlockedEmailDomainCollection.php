<?php

namespace App\User\Application\BlockedEmailDomains;

class BlockedEmailDomainCollection
{
    private array $blockedEmailDomains;

    public function __construct(array $blockedEmailDomains = [])
    {
        $this->blockedEmailDomains = $blockedEmailDomains;
    }

    public function toArray(): array
    {
        return $this->blockedEmailDomains;
    }
}