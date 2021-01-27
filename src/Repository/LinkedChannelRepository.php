<?php

namespace PierreMiniggio\YoutubeToQuora\Repository;

use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;

class LinkedChannelRepository
{
    public function __construct(private DatabaseFetcher $fetcher)
    {}

    public function findAll(): array
    {
        return $this->fetcher->query(
            $this->fetcher
                ->createQuery('quora_account_youtube_channel as qayc')
                ->leftJoin(
                    'quora_account as q',
                    'q.id = qayc.quora_id'
                )
                ->select('
                    qayc.youtube_id as y_id,
                    q.id as q_id,
                    q.api_url,
                    q.api_token
                ')
        );
    }
}
