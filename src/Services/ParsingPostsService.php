<?php

namespace App\Services;

use App\Entity\Post;
use App\Scraper\Contracts\SourceInterface;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;


class ParsingPostsService
{


    /**
     * @param SourceInterface $source
     * @param $mangerRegistry
     * @return void
     * @throws \Exception
     */
    public static function handel(SourceInterface $source, $mangerRegistry): void
    {

        $entityManager = $mangerRegistry->getManager();
        $repository = $entityManager->getRepository(Post::class);
        $client = Client::createChromeClient();
        $crawler = $client->request('GET', $source->getUrl());
        $crawler->filter($source->getWrapperSelector())
            ->each(function (Crawler $c) use ($source, $repository, $entityManager) {

                $post = new Post();


                $title = $c->filter($source->getTitleSelector())->text();
                $post->setTitle($title);
                $dateTime = $c->filter($source->getDateSelector())->attr('datetime');
                if (!$dateTime) {
                    $dateTime = $c->filter($source->getDateSelector())->text();
                }
                $dateTime = $this->cleanupDate($dateTime);
                $post->setDateTime($dateTime);
                $image = $c->filter($source->getImageSelector())->attr('src');
                $post->setImage($image);
                $post->setUrl($source->getUrl());
                $desk = ($c->filter($source->getDescSelector())->text());
                $post->setDescription($desk);
                $post->setCreatedAt(Carbon::now());


                if ($oldPost = $repository->findOneBy(['title' => $post->getTitle()])) {
                    $oldPost->setUpdatedAt(Carbon::now());
                    $entityManager->flush($oldPost);
                } else {

                    $entityManager->persist($post);
                    $entityManager->flush($post);
                }

            });
    }

    /**
     * In order to make DateTime work, we need to clean up the input.
     *
     * @throws \Exception
     */
    private function cleanupDate(string $dateTime): \DateTime
    {
        $dateTime = str_replace(['(', ')', 'UTC', 'at', '|'], '', $dateTime);

        return new \DateTime($dateTime);
    }


}