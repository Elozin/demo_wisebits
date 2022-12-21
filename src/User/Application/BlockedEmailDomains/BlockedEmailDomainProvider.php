<?php

namespace App\User\Application\BlockedEmailDomains;

interface BlockedEmailDomainProvider
{
    public function getBlockedEmailDomainCollection(): BlockedEmailDomainCollection;
}
