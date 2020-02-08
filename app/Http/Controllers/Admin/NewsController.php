<?php
//
namespace App\Http\Controllers\Admin;

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

    //
    public function add()
    {
        return view('admin.news.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {

        $this->validate($request, News::$rules);

        $news = new News;
        $form = $request->all();

        if (isset($form['image'])) {

            // AWS S3
            if ($this->is_image_s3 === true) {
                $path = Storage::disk('s3')->putFile('/', $form['image'], 'public');
                $news->image_path = Storage::disk('s3')->url($path);
            } else {
                $path = $request->file('image')->store('public/image');
                $news->image_path = basename($path);
            }
        } else {
            $news->image->image_path = null;
        }

        unset($form['_token']);
        unset($form['image']);

        $news->fill($form);
        $news->save();

        return redirect('admin/news/create');
    }

    public function index(Request $request)
    {
        \Log::debug(__FILE__);
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            $posts = News::where('title', $cond_title)->get();
        } else {
            $posts = News::all();
        }

        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }

    public function edit(Request $request)
    {
        $news = News::find($request->id);
        if (empty($news)) {
            abort(404);
        }

        return view('admin.news.edit', ['news_form' => $news]);
    }

    public function update(Request $request)
    {

        $this->validate($request, News::$rules);

        $news = News::find($request->id);

        $news_form = $request->all();

        if (isset($news_form['image'])) {
            // 画像を変更する
            if ($this->is_image_s3 === true) {
                // AWS S3
                $path = Storage::disk('s3')->putFile('/', $news_form['image'], 'public');

                $news->image_path = Storage::disk('s3')->url($path);
            } else {
                $path = $request->file('image')->store('public/image');
                $news->image_path = basename($path);
            }
        } elseif (isset($news_form['remove']) && $news_form['remove'] === 'true') {
            // ファイル削除
            $news->image_path = null;
            unset($news_form['remove']);
        }

        unset($news_form['_token']);
        unset($news_form['image']);
        unset($news_form['remove']);

        $news->fill($news_form)->save();

        $history = new History;
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();

        return redirect('admin/news');
    }

    public function delete(Request $request)
    {
        $news = News::find($request->id);

        $news->delete();

        return redirect('admin/news/');
    }
}
