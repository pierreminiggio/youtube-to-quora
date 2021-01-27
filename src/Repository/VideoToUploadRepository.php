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
        $this->connection->start();
        $postQueryParams = [
            'account_id' => $quoraAccountId,
            'quora_id' => $quoraId
        ];
        $findPostIdQuery = ['
            SELECT id FROM quora_post
            WHERE account_id = :account_id
            AND quora_id = :quora_id
            ;
        ', $postQueryParams];
        $queriedIds = $this->connection->query(...$findPostIdQuery);
        
        if (! $queriedIds) {
            $this->connection->exec('
                INSERT INTO quora_post (account_id, quora_id)
                VALUES (:account_id, :quora_id)
                ;
            ', $postQueryParams);
            $queriedIds = $this->connection->query(...$findPostIdQuery);
        }

        $postId = (int) $queriedIds[0]['id'];
        
        $pivotQueryParams = [
            'quora_id' => $postId,
            'youtube_id' => $youtubeVideoId
        ];

        $queriedPivotIds = $this->connection->query('
            SELECT id FROM quora_post_youtube_video
            WHERE quora_id = :quora_id
            AND youtube_id = :youtube_id
            ;
        ', $pivotQueryParams);
        
        if (! $queriedPivotIds) {
            $this->connection->exec('
                INSERT INTO quora_post_youtube_video (quora_id, youtube_id)
                VALUES (:quora_id, :youtube_id)
                ;
            ', $pivotQueryParams);
        }

        $this->connection->stop();
    }
}
