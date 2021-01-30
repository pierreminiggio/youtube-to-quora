<?php

namespace PierreMiniggio\YoutubeToQuora\Repository;

use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;

class NonUploadableVideoRepository
{
    public function __construct(private DatabaseFetcher $fetcher)
    {}

    public function markAsNonUploadableIfNeeded(int $youtubeVideoId): void
    {
        $videoQueryParams = ['video_id' => $youtubeVideoId];
        $queriedIds = $this->fetcher->query(
            $this->fetcher
                ->createQuery('youtube_video_unpostable_on_quora')
                ->select('id')
                ->where('youtube_id = :video_id')
            ,
            $videoQueryParams
        );
        
        if (! $queriedIds) {
            $this->fetcher->exec(
                $this->fetcher
                    ->createQuery('youtube_video_unpostable_on_quora')
                    ->insertInto('youtube_id', ':video_id')
                ,
                $videoQueryParams
            );
        }
    }
}