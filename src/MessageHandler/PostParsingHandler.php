<?php

namespace App\MessageHandler;

use App\Services\ParsingPostsService;
use App\Sources\Coindesk;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PostParsingHandler implements MessageHandlerInterface
{
    public function __invoke(ManagerRegistry $manager)
    {
        ParsingPostsService::handel(new Coindesk(), $manager);
        echo "Parsing Post Now.......";
    }
}