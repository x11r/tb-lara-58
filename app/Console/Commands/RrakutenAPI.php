<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class rrakutenAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SearchRakuten';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * 楽天API用 アプリケーションID
     *
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    protected $rakuten_app_id = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->rakuten_app_id = config('app.RAKUTEN_APP_ID');
    }

    /**
     * Execute the console command.
     *
     * @param $headers
     * @return mixed
     */
    public function handle()
    {
        //
        $this->getAreas();
//        $this->getHotels();
    }

    public function getAreas()
    {
        $params = [
            'format' => 'json',
            'applicationId' => $this->rakuten_app_id,
        ];
        $base_uri = 'https://app.rakuten.co.jp';
        $path = 'services/api/Travel/GetAreaClass/20131024';

        $path .= '?' . http_build_query($params);

        $options = [
                'headers' => [
                'accept' => 'application/json',
                'cache-control' => 'no-cache',
                'content-type' => 'application/x-www-form-urlencoded; charset=utf-8',
           ]
        ];

        try {
            $client = new Client(['base_uri' => $base_uri]);
            $response = $client->request('GET', $path, $options);
            $result = json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            print_r($e->getMessage());
        }
        \Log::debug(__LINE__.' '.__FILE__.' '.print_r($result, true));
    }

    public function getHotels()
    {
        $base_uri = 'https://app.rakuten.co.jp/';
        $path = 'services/api/Travel/SimpleHotelSearch/20170426';

        $params = [
            'format' => 'json',
            'largeClassCode' => 'japan',
            'middleClassCode' => 'hokkaido',
            'smallClassCode' => 'sapporo',
            'detailClassCode' => 'A',
            'applicationId' => $this->rakuten_app_id,
        ];

        // GETパラメータを追加
        $path .= '?' . http_build_query($params);
        echo $path,"\n";

        $headers = [
            'accept' => 'application/json',
            'cache-control' => 'no-cache',
            'content-type' => 'application/x-www-form-urlencoded; charset=utf-8',
        ];

        $options = [
            'headers' => $headers,
        ];

        try {
            $client = new Client(['base_uri' => $base_uri]);
            $response = $client->request('GET', $path, $options);
            $result = json_decode($response->getBody()->getContents(), true);
        } catch (ClientExceptin $e) {
            print_r($e->getMessage());
        }

        \Log::debug(__LINE__.' '.__FILE__.' '.print_r($result, true));
    }

    public function authorization()
    {
        //
    }
}
