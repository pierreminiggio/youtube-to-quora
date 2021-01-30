<?php

namespace PierreMiniggio\YoutubeToQuora\Repository;

use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;

class VideoToUploadRepository
{
    public function __construct(private DatabaseFetcher $fetcher)
    {}

    public function insertVideoIfNeeded(
        string $quoraId,
        int $quoraAccountId,
        int $youtubeVideoId
    ): void
    {
        $postQueryParams = [
            'account_id' => $quoraAccountId,
            'quora_id' => $quoraId
        ];
        $findPostIdQuery = [
            $this->fetcher
                ->createQuery('quora_post')
                ->select('id')
                ->where('account_id = :account_id AND quora_id = :quora_id')
            ,
            $postQueryParams
        ];
        $queriedIds = $this->fetcher->query(...$findPostIdQuery);
        
        if (! $queriedIds) {
            $this->fetcher->exec(
                $this->fetcher
                    ->createQuery('quora_post')
                    ->insertInto('account_id, quora_id', ':account_id, :quora_id')
                ,
                $postQueryParams
            );
            $queriedIds = $this->fetcher->query(...$findPostIdQuery);
        }

        $postId = (int) $queriedIds[0]['id'];
        
        $pivotQueryParams = [
            'quora_id' => $postId,
            'youtube_id' => $youtubeVideoId
        ];

        $queriedPivotIds = $this->fetcher->query(
            $this->fetcher
                ->createQuery('quora_post_youtube_video')
                ->select('id')
                ->where('quora_id = :quora_id AND youtube_id = :youtube_id')
            ,
            $pivotQueryParams
        );
        
        if (! $queriedPivotIds) {
            $this->fetcher->exec(
                $this->fetcher
                    ->createQuery('quora_post_youtube_video')
                    ->insertInto('quora_id, youtube_id', ':quora_id, :youtube_id')
                ,
                $pivotQueryParams
            );
        }
    }
}
