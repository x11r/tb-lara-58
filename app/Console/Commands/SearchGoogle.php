<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class SearchGoogle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'searchGoogle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $base_uri = 'https://customsearch.googleapis.com/';

    protected $path_prefix = 'customsearch/v1';

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
//        echo __LINE__.' '.__FILE__."\n";
        $this->search1();
    }

    private function search1()
    {
        $keyword = '激おこ 子猫';
        $params = [
            'key' => config('app.GOOGLE_API_KEY', 'none'),
            'q' => $keyword,
        ];
        $path = $this->path_prefix . '?' . http_build_query($params);
        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
        \Log::debug(__LINE__.' '.__FILE__.' [base_uri] '.$this->base_uri);
        \Log::debug(__LINE__.' '.__FILE__.' [path] '.$path);

        try {
            $client = new Client(['base_uri' => $this->base_uri]);
            $response = $client->request('GET', $path, $options);
            $result = json_decode($response->getBody()->getContents(), true);
            \Log::debug(__LINE__.' '.__FILE__.' '.print_r($result, true));
        } catch (ClientException $e) {
            \Log::debug(__LINE__.' '.__FILE__);
            \Log::debug(__LINE__.' '.print_r($e->getMessage(), true));
        }
    }
}
