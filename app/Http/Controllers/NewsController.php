<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Models\NewsCategory;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    use ResponseAPI;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $newsCategory = News::get();
            return $this->success('success get list of news', NewsResource::collection($newsCategory), 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'content' => 'required',
                'news_category_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if( $validator->fails() ) return $this->error($validator->errors(), 500);

            $user = Auth::user();
            $newsCategory = NewsCategory::find($request->news_category_id);

            if( !$newsCategory ) return $this->error('News category not found', 500);

            $news = $newsCategory->news()->create([
                'user_id' => $user->id,
                'content' => $request->content,
            ]);

            return $this->success('success store news', New NewsResource($news), 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $newsCategory = News::find($id);
            if( !$newsCategory ) return $this->error('News not found', 500);
            return $this->success('success get list of category', new NewsResource($newsCategory), 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if( $validator->fails() ) return $this->error($validator->errors(), 500);

            $news = News::find($request->id);

            if( !$news ) return $this->error('News not found', 500);

            $newsCategory       = !$request->news_category_id ? $news->news_category_id : $request->news_category_id;
            $newsCategoryCheck  = NewsCategory::find($newsCategory);

            if( !$newsCategoryCheck ) return $this->error('News category not found', 500);

            $news->content          = $request->content;
            $news->news_category_id = $newsCategory;
            $news->save();

            return $this->success('Success update news', new NewsResource($news), 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $news = News::find($id);
            if( !$news ) return $this->error('News not found', 500);

            $news->delete();

            return $this->success('Success destroy news', null, 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
