<?php

namespace App\Sources;

use App\Scraper\Contracts\SourceInterface;

class OtherSource implements SourceInterface
{
    public function getUrl(): string
    {
        return 'https://www.imf.org/en/Blogs/topics';
    }

    public function getName(): string
    {
        return 'IMF Blog';
    }

    public function getWrapperSelector(): string
    {
        return '.card';
    }

    public function getTitleSelector(): string
    {
        return '.card-title';
    }

    public function getDescSelector(): string
    {
        return '.card-text';
    }

    public function getDateSelector(): string
    {
        return '.card-date';
    }

    public function getLinkSelector(): string
    {
        return '';
    }

    public function getImageSelector(): string
    {
        return '.card-img-top';
    }
}