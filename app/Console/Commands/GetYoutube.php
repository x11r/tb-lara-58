<?php

namespace App\Console\Commands;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class GetYoutube extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getYoutube';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $base_uri = 'https://www.googleapis.com';
    private $path_prefix = 'youtube/v3/';
    private $api_key = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
//        $this->api_key = config('api.GOOGLE_API_KEY', 'no');
        $this->initializeAccount();
//        $this->getChannels();
//        $this->getVideoCategories();
    }

    private function getApikey()
    {
        if ($this->api_key === '') {
            $this->api_key = config('app.GOOGLE_API_KEY', 'none');
        }
        return $this->api_key;
    }

    private function getChannels()
    {
        $params = [
            'key' => $this->getApikey(),
        ];
        $base_uri = 'https://www.googleapis.com';
        $path = $this->path_prefix . 'channel?' . http_build_query($params);

        try {
            $client = new Client(['base_uri' => $base_uri]);
            $response = $client->request('GET', $path, []);
            $result = json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
//            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($e->getResponse(), true));
            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($e->getMessage(), true));
        }
    }
    private function getVideoCategories()
    {
        //
        $keyword = '激おこ';
        $params = [
            'key' => $this->getApikey(),
            'part' => $keyword,
        ];

        $options = [
//            'http_errors' => false,
        ];

//        \Log::debug(__LINE__.' '.__FILE__.' '.print_r($params, true));

        $path = $this->path_prefix . 'videoCategories?' . http_build_query($params);


        try {
            $client = new Client(['base_uri' => $this->base_uri]);
            $response = $client->request('GET', $path, $options);
            $result = json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($e->getResponse(), true));
        }

        try {
            $client = new Client(['base_uri' => $this->base_uri]);
            $response = $client->request('GET', $path, $options);
            $result = json_decode($response->getBody()->getContents(), true);
//            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($result, true));
        } catch (ClientException $e) {
//            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($e->getRequest(), true));
            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($e->getResponse(), true));
//            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($e->getMessage(), true));
        }
    }

    private function sample()
    {
        $api_key = config('app.GOOGLE_API_KEY','no');
        \Log::debug(__LINE__.' '.__FILE__.' [apiKey] '.$api_key);
        exit();

        $params = [
            'key' => config('GOOGLE_API_KEY'),
            'part' => 'contentDetail',
            'mine' => 'true',
        ];

        $base_url = 'https://www.googleapis.com';

        $path = 'youtube/v3/channels';
        if (count($params) > 0) {
            $path .= '?' . http_build_query($params);
        }

        \Log::Debug(__LINE__.' '.__FILE__.' [params] '.print_r($params, true));

        $client = new Client([
            'base_uri' => $this->$base_url
        ]);

        $method = 'GET';
        $response = $client->request($method, $path, []);
        $list = json_decode($response->getBody()->getContents(), true);

        \Log::debug(__LINE__.' '.__FILE__.' [list] '.print_r($list, true));
    }

    private function initializeAccount()
    {
        $base_url = 'https://accounts.google.com';
        $path = 'o/oauth2/auth?'
            . 'key=' . env('GOOGLE_API_KEY');

        try {
            $client = new Client(['base_uri' => $base_url]);
            $response = $client->request('GET', $path, []);
            $list = json_decode($response->getBody()->getContents(), true);
            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($list, true));
        } catch (Exception $e) {
            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($e->response()->getBody()->getContents(), true));
        }
        // 特に問題なければ初期設定完了。
    }
}
