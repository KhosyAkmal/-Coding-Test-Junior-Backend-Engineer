<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsCategoryResource;
use App\Models\NewsCategory;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsCategoryController extends Controller
{
    use ResponseAPI;

    public function index()
    {
        try {
            $newsCategory = NewsCategory::get();
            return $this->success('success get list of category', NewsCategoryResource::collection($newsCategory), 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {

            $newsCategory = NewsCategory::find($id);
            if( !$newsCategory ) return $this->error('News category not found', 500);
            return $this->success('success get list of category', new NewsCategoryResource($newsCategory), 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if( $validator->fails() ) return $this->error($validator->errors(), 500);

            $newsCategory = NewsCategory::create([
                'name' => $request->name,
            ]);

            return $this->success('success get list of category', New NewsCategoryResource($newsCategory), 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'id' => 'required',
                'name' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if( $validator->fails() ) return $this->error($validator->errors(), 500);

            $newsCategory = NewsCategory::find($request->id);

            if( !$newsCategory ) return $this->error('News category not found', 500);

            $newsCategory->name = $request->name;
            $newsCategory->save();

            return $this->success('Success update list of categories', new NewsCategoryResource($newsCategory), 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $newsCategory = NewsCategory::find($id);
            if( !$newsCategory ) return $this->error('News category not found', 500);

            $newsCategory->delete();
            return $this->success('Success destroy news category', null, 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
