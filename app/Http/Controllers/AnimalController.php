<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResource;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Habitat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnimalController extends Controller
{
    public function category(): BaseResource
    {
        $data = Category::all();
        return new BaseResource(true, "List Data Category", $data);
    }

    public function habitat(): BaseResource
    {
        $data = Habitat::all();
        return new BaseResource(true, "List Data Habitat", $data);
    }

    public function index(): BaseResource
    {
        $data = Animal::with('category', 'habitat')->get();
        return new BaseResource(true, "List Data Animal", $data);
    }

    public function show($id): BaseResource
    {
        $data = Animal::with('category', 'habitat')->find($id);
        if (!$data) {
            return new BaseResource(true, "Data Tidak ditemukan", null);
        }
        return new BaseResource(true, "Detail Data Animal", $data);
    }

    public function store(Request $request): JsonResponse|BaseResource
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
            'category_id' => 'required',
            'habitat_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $image = $request->file('image');
        $image->storeAs('public/animal-images', $image->hashName());

        $data = Animal::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'image' => $image->hashName(),
            'category_id' => $request->get('category_id'),
            'habitat_id' => $request->get('habitat_id'),
        ]);

        return new BaseResource(true,  "Data Animal Berhasil Ditambahkan!", $data);
    }

    public function update(Request $request, $id): BaseResource|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
            'category_id' => 'required',
            'habitat_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = Animal::find($id);
        if (!$data) {
            return response()->json(["message" => "Data Tidak Ditemukan"], 404);
        }

        if ($request->hasFile("image")){
            $image = $request->file('image');
            $image->storeAs('public/animal-images', $image->hashName());

            Storage::delete("public/animal-images/" . $data->image);
            $data->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'image' => $image->hashName(),
                'category_id' => $request->get('category_id'),
                'habitat_id' => $request->get('habitat_id'),
            ]);
        } else {
            $data->update([
               'name' => $request->get('name'),
               'description' => $request->get('description'),
               'category_id' => $request->get('category_id'),
               'habitat_id' => $request->get('habitat_id'),
            ]);
        }
        return new BaseResource(true, "Data Animal Berhasil Diupdate!", $data);
    }

    public function destroy($id): BaseResource
    {
        $data = Animal::find($id);
        Storage::delete('public/animal-images/' . $data->image);
        $data->delete();
        return new BaseResource(true, "Data Animal Berhasil Dihapus!", null);
    }
}
