<?php

namespace App\Traits;

trait AfterSaveRedirectToIndex
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
