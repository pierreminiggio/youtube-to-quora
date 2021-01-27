<?php

namespace PierreMiniggio\YoutubeToQuora\Repository;

use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;

class NonUploadedVideoRepository
{
    public function __construct(private DatabaseFetcher $fetcher)
    {}

    public function findByQuoraAndYoutubeChannelIds(int $quoraAccountId, int $youtubeChannelId): array
    {
        $this->connection->start();

        $postedQuoraPostIds = $this->connection->query('
            SELECT q.id
            FROM quora_post as q
            RIGHT JOIN quora_post_youtube_video as qpyv
            ON q.id = fpyv.quora_id
            WHERE q.account_id = :account_id
        ', ['account_id' => $quoraAccountId]);
        $postedQuoraPostIds = array_map(fn ($entry) => (int) $entry['id'], $postedQuoraPostIds);

        $postsToPost = $this->connection->query('
            SELECT
                y.id,
                y.title,
                y.url
            FROM youtube_video as y
            ' . (
                $postedQuoraPostIds
                    ? 'LEFT JOIN quora_post_youtube_video as qpyv
                    ON y.id = fpyv.youtube_id
                    AND fpyv.quora_id IN (' . implode(', ', $postedQuoraPostIds) . ')'
                    : ''
            ) . '
            LEFT JOIN youtube_video_unpostable_on_quora as yvuoq
            ON yvuoq.youtube_id = y.id
            
            WHERE y.channel_id = :channel_id
            AND yvuoq.id IS NULL
            ' . ($postedQuoraPostIds ? 'AND fpyv.id IS NULL' : '') . '
            ;
        ', [
            'channel_id' => $youtubeChannelId
        ]);
        $this->connection->stop();

        return $postsToPost;
    }
}
