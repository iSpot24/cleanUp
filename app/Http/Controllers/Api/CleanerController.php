<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Cleaner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LVR\Phone\Phone;

/**
 * Class CleanerController
 * @package App\Http\Controllers\Api
 */
class CleanerController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->only(['city', 'date']), [
            'city' => ['sometimes', 'string', 'exists:cities,name'],
            'date' => ['sometimes', 'date']
        ]);

        if ($validator->fails()) {
            return response()->json(['Validation Error.' => $validator->errors()]);
        }
        if ($request->hasAny(['name', 'city'])) {
            if ($request->has('city')) {
                $city = City::where('name', 'LIKE', '%' . $request->input('city') . '%')->first();
                $cityCleaners = $cleaners = $city->cleaners;
            }
            if ($request->has('date'))
                $cleanersWithoutBookings = $cleaners->Booking::where('date', '<>', $request->input('date'));
        }
        else
        {
            return Cleaner::all()->get('items');
        }

        return response()->json([
            'success' => true,
            'data' => array_merge(Cleaner::all()->toArray(), [$cityCleaners = null, $cleanersWithoutBookings = null])
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:32'],
            'email' => ['required', 'email', 'unique:cleaners,email'],
            'phone_number' => ['required', new Phone],
            'cities' => ['required', 'array'],
            'cities.*' => ['required', 'min:1']
        ]);

        if ($validator->fails()) {
            return response()->json(['Validation Error.' => $validator->errors()]);
        }

        $cleaner = Cleaner::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
        ]);

        $cleaner->cities()->sync($request->input('cities'));

        if ($cleaner) {
            return response()->json([
                'success' => true,
                'data' => [
                    'cleaner' => $cleaner->toArray(),
                    'cleaner_cities' => $cleaner->cities],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cleaner could not be added'
        ], 500);
    }

    /**
     * @param Request $request
     * @param Cleaner $cleaner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Cleaner $cleaner)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'max:32'],
            'email' => ['sometimes', 'email', 'unique:cleaners,email'],
            'phone_number' => ['sometimes', new Phone],
            'cities' => ['sometimes', 'array'],
            'cities.*' => ['required', 'min:1']
        ]);
        if ($validator->fails()) {
            return response()->json(['Validation Error.' => $validator->errors()]);
        }

        $updated = $cleaner->fill($request->except('cities'))->update();
        $updated = $request->has('cities') ? $cleaner->cities()->sync($request->input('cities')) : $updated;

        if ($updated) {
            return response()->json([
                'success' => true,
                'data' => [
                    'cleaner' => $cleaner->toArray(),
                    'cleaner_cities' => $cleaner->cities],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cleaner could not be updated'
        ], 500);
    }

    /**
     * @param Cleaner $cleaner
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Cleaner $cleaner)
    {
        if ($cleaner->delete()) {

            $maxId = DB::table('cleaners')->max('id');

            $maxId = isset($maxId) ? $maxId : 1;

            DB::statement("ALTER TABLE cleaners AUTO_INCREMENT=$maxId");

            return response()->json([
                'success' => true,
                'message' => "Cleaner '$cleaner->name' was deleted successfully",
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Cleaner could not be deleted'
        ]);
    }
}
