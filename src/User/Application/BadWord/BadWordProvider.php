<?php

namespace App\User\Application\BadWord;

interface BadWordProvider
{
    public function getBadWordCollection(): BadWordCollection;
}
