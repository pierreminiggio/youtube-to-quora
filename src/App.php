<?php

namespace PierreMiniggio\YoutubeToQuora;

use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;
use PierreMiniggio\YoutubeToQuora\Connection\DatabaseConnectionFactory;
use PierreMiniggio\YoutubeToQuora\Repository\LinkedChannelRepository;
use PierreMiniggio\YoutubeToQuora\Repository\NonUploadedVideoRepository;
use PierreMiniggio\YoutubeToQuora\Repository\VideoToUploadRepository;

class App
{
    public function run(): int
    {
        set_time_limit(1200);

        $code = 0;

        $config = require(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php');

        if (empty($config['db'])) {
            echo 'No DB config';

            return $code;
        }

        $databaseFetcher = new DatabaseFetcher((new DatabaseConnectionFactory())->makeFromConfig($config['db']));
        $channelRepository = new LinkedChannelRepository($databaseFetcher);
        $nonUploadedVideoRepository = new NonUploadedVideoRepository($databaseFetcher);
        $videoToUploadRepository = new VideoToUploadRepository($databaseFetcher);

        $linkedChannels = $channelRepository->findAll();

        if (! $linkedChannels) {
            echo 'No linked channels';

            return $code;
        }

        foreach ($linkedChannels as $linkedChannel) {
            echo PHP_EOL . PHP_EOL . 'Checking account ' . $linkedChannel['q_id'] . '...';

            $postsToPost = $nonUploadedVideoRepository->findByQuoraAndYoutubeChannelIds($linkedChannel['q_id'], $linkedChannel['y_id']);
            echo PHP_EOL . count($postsToPost) . ' post(s) to post :' . PHP_EOL;
            
            foreach ($postsToPost as $postToPost) {
                echo PHP_EOL . 'Posting ' . $postToPost['title'] . ' ...';

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $linkedChannel['api_url'],
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $postToPost['title'] . ' ' . $postToPost['url'],
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $linkedChannel['api_token']
                    ]
                ]);
                $res = curl_exec($curl);

                $jsonResponse = json_decode($res, true);

                if (! empty($jsonResponse['post_id'])) {
                    $videoToUploadRepository->insertVideoIfNeeded(
                        $jsonResponse['post_id'],
                        $linkedChannel['q_id'],
                        $postToPost['id']
                    );
                    echo PHP_EOL . $postToPost['title'] . ' posted !';
                } else {
                    echo PHP_EOL . 'Error while posting ' . $postToPost['title'] . ' : ' . $res;
                }
            }

            echo PHP_EOL . PHP_EOL . 'Done for account ' . $linkedChannel['q_id'] . ' !';
        }

        return $code;
    }
}
