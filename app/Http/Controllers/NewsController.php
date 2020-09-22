<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use App\News;
use App\History;
use Carbon\Carbon;
use Storage;

class NewsController extends Controller
{
    private $is_image_s3 = false;

    public function __construct()
    {
        //
        $this->is_image_s3 = env('IS_IMAGE_S3', false);
    }

    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            $posts = News::where('title', $cond_title)->get();
        } else {
            $posts = News::all();
        }

        return view('news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }
}
