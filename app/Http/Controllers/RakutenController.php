<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;

class RakutenController extends Controller
{
    //

    /**
     * 楽天API用アプリケーションID
     *
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    protected $rakuten_app_id = '';

    /**
     * 楽天APIの基底URI
     * @var string
     */
    protected $base_uri = 'https://app.rakuten.co.jp';

    protected $request;

    public function __construct(
        Request $request
    )
    {
        //
        $this->request = $request;
        $this->rakuten_app_id = config('app.RAKUTEN_APP_ID');
    }

    /**
     * indexのページを表示する
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        \Log::debug(__LINE__.' '.__FILE__.' '.__METHOD__);
        $areas = $this->getAreas();

        $params = [
            'areas' => $areas,
        ];
        return view('rakuten.index', $params);
    }

    /**
     * エリア一覧をJSONで返す
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function areaJson(Request $request)
    {
        return response()->json($this->getAreas());
    }

    /**
     * 楽天トラベルで検索する画面を表示する
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getAreas()
    {
        // キャッシュのキー
        $cache_key = __METHOD__;

        $minutes = 1;

        // $iminutesを寿命にキャッシュする
        $result = Cache::remember($cache_key, $minutes, function() {

            $params = [
                'fromat' => 'json',
                'applicationId' => $this->rakuten_app_id,
            ];

            $path = 'services/api/Travel/GetAreaClass/20131024'
                . '?' . http_build_query($params);

            $options = [
                'headers' => [
                    'accept' => 'application/json',
                    'cache-control' => 'no-cache',
                    'content-type' => 'application/x-www-form-urlencoded; charset=utf-8',
                ]
            ];

            $result = [];
            try {
                $client = new Client(['base_uri' => $this->base_uri]);
                $response = $client->request('GET', $path, $options);

                $result = json_decode($response->getBody()->getContents());

                // 取得エラーになったら空の配列
            } catch (ClientException $e) {

                print_r($e->getMessage());
            }

            return $result;
        });

        return $result;
    }

    /**
     * @param $large_class
     * @param $middle_class
     * @param $small_class
     * @param null $detail_class
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function hotelSearch(
        $large_class,
        $middle_class,
        $small_class,
        $detail_class = null

    )
    {
        $form = $this->request->all();

        // 検索のパラメータ
        $search_params = [
            'large_class' => $large_class,
            'middle_class' => $middle_class,
            'small_class' => $small_class,
            'detail_class' => $detail_class,
            // ページ数(100以下)
            'page' => isset($form['page']) && $form['page'] <= 100 ? $form['page'] : 1,
            // 1ページでの表示件数(30件以内)
            'hits' => isset($form['hits']) && $form['hits'] <= 30 ? $form['page'] : 30,
        ];

        // キャッシュのキー
        $cache_key = __METHOD__;
        $cache_key .= $large_class ? '-' . $large_class : '';
        $cache_key .= $middle_class ? '-' . $middle_class : '';
        $cache_key .= $small_class ? '-' . $small_class : '';
        $cache_key .= $detail_class ? '-' . $detail_class : '';
        $cache_key .= '-page-' . $search_params['page'];
        $cache_key .= '-hits-' . $search_params['hits'];

        \Log::debug(__LINE__.' '.__FILE__.' [cache_key] '.$cache_key);
        if (Cache::has($cache_key)) {
            \Log::debug(__LINE__.' '.__FILE__.' [Cache] exist');
        } else {
            \Log::debug(__LINE__.' '.__FILE__.' [Cache] not exist');
        }

        // キャッシュ時間(分)
        $minutes = 10;

        // $iminutesを寿命にキャッシュする
        $result = Cache::remember($cache_key, $minutes, function() use ($search_params, $cache_key) {

            $params = [
                'format' => 'json',
                'applicationId' => $this->rakuten_app_id,
            ];

            if ($search_params['large_class'] !== null) {
                $params['largeClassCode'] = $search_params['large_class'];
            }

            if ($search_params['middle_class'] !== null) {
                $params['middleClassCode'] = $search_params['middle_class'];
            }

            if ($search_params['small_class'] !== null) {
                $params['smallClassCode'] = $search_params['small_class'];
            }

            if ($search_params['detail_class'] !== null) {
                $params['detailClassCode'] = $search_params['detail_class'];
            }

            $params['page'] = $search_params['page'];
            $params['hits'] = $search_params['hits'];

            $path = 'services/api/Travel/SimpleHotelSearch/20170426'
                . '?' . http_build_query($params);

            $options = [
                'headers' => [
                    'accept' => 'application/json',
                    'cache-control' => 'no-cache',
                    'content-type' => 'application/x-www-form-urlencoded; charset=utf-8',
                ],
                'connect_timeout' => 2,
                'timeout' => 5,
            ];

            $result = [];
            try {
                $client = new Client(['base_uri' => $this->base_uri]);
                $response = $client->request('GET', $path, $options);
                $result = json_decode($response->getBody()->getContents());

                Cache::put($cache_key, $result);

                // 取得エラーになったら空の配列
            } catch (ClientException $e) {
                print_r($e->getMessage());
            }

            return $result;
        });

        $paginate = null;
        if (isset($result->pagingInfo)) {

            $pagingInfo = $result->pagingInfo;

            // ペジネーションを作る
            $paginate = (Object)[
                'total' => $pagingInfo->recordCount,
                'current_page' => $pagingInfo->page,
                'per_page' => $pagingInfo->last - $pagingInfo->first + 1,
                'last_page' => floor($pagingInfo->last / ($pagingInfo->last - $pagingInfo->first + 1)),
                'from' => 1,
                'to' => $pagingInfo->pageCount,
                'data' => null,
            ];

            $paginate->data = $result->hotels;
        }
//        echo __LINE__.' '.__FILE__.'<br>';
//        \Log::debug(__LINE__.' '.__FILE__.' '.print_r($paginate, true));

        return view('rakuten.hotelSearch', ['result' => $result, 'paginate' => $paginate]);
    }

    public function hotelDetail($hotel_id)
    {

        // キャッシュのキー
        $cache_key = __METHOD__ . '-' . $hotel_id;

        $minutes = 10;

        // $iminutesを寿命にキャッシュする
        $result = Cache::remember($cache_key, $minutes, function() use ($hotel_id) {

            $params = [
                'format' => 'json',
                'applicationId' => $this->rakuten_app_id,
                'hotelNo' => $hotel_id,
            ];


            $path = 'services/api/Travel/HotelDetailSearch/20170426'
                . '?' . http_build_query($params);

            $options = [
                'headers' => [
                    'accept' => 'application/json',
                    'cache-control' => 'no-cache',
                    'content-type' => 'application/x-www-form-urlencoded; charset=utf-8',
                ]
            ];

            $result = [];
            try {
                $client = new Client(['base_uri' => $this->base_uri]);
                $response = $client->request('GET', $path, $options);
                $result = json_decode($response->getBody()->getContents());


                // 取得エラーになったら空の配列
            } catch (ClientException $e) {
                print_r($e->getMessage());
            }

            return $result;
        });

        $basic_info = $result->hotels[0]->hotel[0]->hotelBasicInfo ?: '';

        return view('rakuten.hotelDetail', [
            'result' => $result,
            'basic_info' => $basic_info,
        ]);
    }
}
