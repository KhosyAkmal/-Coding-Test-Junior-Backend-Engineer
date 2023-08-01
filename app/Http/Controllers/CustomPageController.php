<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomPageResource;
use App\Models\CustomPage;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomPageController extends Controller
{
    use ResponseAPI;

    public function index()
    {
        try {
            $customPage = CustomPage::get();
            return $this->success('success get list of custom pages', CustomPageResource::collection($customPage), 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'custom_url' => 'required',
                'page_content' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if( $validator->fails() ) return $this->error($validator->errors(), 500);

            $customPage = CustomPage::create([
                'custom_url' => $request->custom_url,
                'page_content' => $request->page_content,
            ]);

            return $this->success('success store custom page', New CustomPageResource($customPage), 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {

            $customPage = CustomPage::find($id);
            if( !$customPage ) return $this->error('Custom page not found', 500);
            return $this->success('success get detail custom page', new CustomPageResource($customPage), 200);

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

            $customPage = CustomPage::find($request->id);

            if( !$customPage ) return $this->error('Custom page not found', 500);

            $customPage->custom_url     = $request->custom_url ? $request->custom_url : $customPage->custom_url ;
            $customPage->page_content   = $request->page_content ? $request->page_content : $customPage->page_content;
            $customPage->save();

            return $this->success('Success update custom page', new CustomPageResource($customPage), 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $customPage = CustomPage::find($id);
            if( !$customPage ) return $this->error('Custom page not found', 500);

            $customPage->delete();

            return $this->success('Success destroy custom page', null, 200);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
