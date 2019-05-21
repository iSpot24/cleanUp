<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Class CityController
 * @package App\Http\Controllers\Api
 */
class CityController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:32', 'unique:cities,name'],
            'zip_code' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json(['Validation Error.' => $validator->errors()]);
        }

        $city = City::create([
            'name' => $request->input('name'),
            'zip_code' => $request->input('zip_code'),
        ]);

        if ($city) {
            return response()->json([
                'success' => true,
                'data' => $city->toArray()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'City could not be added'
        ], 500);
    }

    /**
     * @param Request $request
     * @param City $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, City $city)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'max:32', "unique:cities,name,$city->id,id"],
            'zip_code' => ['sometimes', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json(['Validation Error.' => $validator->errors()]);
        }

        $updated = $city->fill($request->all())->update();

        if ($updated) {
            return response()->json([
                'success' => true,
                'data' => $city->toArray()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'City could not be updated'
        ], 500);
    }

    /**
     * @param City $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(City $city)
    {
        if($city->delete()) {

            $maxId = DB::table('cities')->max('id');

            $maxId = isset($maxId) ? $maxId : 1;

            DB::statement("ALTER TABLE cities AUTO_INCREMENT=$maxId");

            return response()->json([
                'success' => true,
                'message' => "City '$city->name' was deleted successfully",
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'City could not be deleted'
        ]);
    }
}
