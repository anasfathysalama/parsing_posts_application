<?php

namespace App\Sources;

use App\Scraper\Contracts\SourceInterface;

class Coindesk implements SourceInterface
{

    public function getUrl(): string
    {
        return 'https://www.coindesk.com/newsletters/crypto-for-advisors/';
    }

    public function getName(): string
    {
        return 'Coindesk';
    }

    public function getWrapperSelector(): string
    {
        return '.article-card';
    }

    public function getTitleSelector(): string
    {
        return '.card-title';
    }

    public function getDescSelector(): string
    {
        return '.content-text';
    }

    public function getDateSelector(): string
    {
        return 'span.typography__StyledTypography-owin6q-0';
    }

    public function getLinkSelector(): string
    {
        return 'div.text-content a:nth-child(2)';
    }

    public function getImageSelector(): string
    {
        return '.img-block  img';
    }
}