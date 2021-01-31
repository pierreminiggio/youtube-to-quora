<?php

namespace PierreMiniggio\YoutubeToQuora\Repository;

use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;

class NonUploadedVideoRepository
{
    public function __construct(private DatabaseFetcher $fetcher)
    {}

    public function findByQuoraAndYoutubeChannelIds(int $quoraAccountId, int $youtubeChannelId): array
    {
        $postedQuoraPostIds = $this->fetcher->query(
            $this->fetcher
                ->createQuery('quora_post_youtube_video as qpyv')
                ->leftJoin('quora_post as q', 'q.id = qpyv.quora_id')
                ->select('q.id')
                ->where('q.account_id = :account_id')
            ,
            ['account_id' => $quoraAccountId]
        );
        $postedQuoraPostIds = array_map(fn ($entry) => (int) $entry['id'], $postedQuoraPostIds);

        $query = $this->fetcher
            ->createQuery('youtube_video as y')
            ->select('y.id, y.title, y.url')
            ->leftJoin('youtube_video_unpostable_on_quora as yvuoq', 'yvuoq.youtube_id = y.id')
            ->where('y.channel_id = :channel_id AND yvuoq.id IS NULL' . (
                $postedQuoraPostIds ? ' AND qpyv.id IS NULL' : ''
            ))
            ->limit(1)
        ;

        if ($postedQuoraPostIds) {
            $query->leftJoin(
                'quora_post_youtube_video as qpyv',
                'y.id = qpyv.youtube_id AND qpyv.quora_id IN (' . implode(', ', $postedQuoraPostIds) . ')'
            );
        }
        $postsToPost = $this->fetcher->query($query, ['channel_id' => $youtubeChannelId]);
        
        return $postsToPost;
    }
}
