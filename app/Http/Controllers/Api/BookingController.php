<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\City;
use App\Models\Cleaner;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use LVR\Phone\Phone;

/**
 * Class BookingController
 * @package App\Http\Controllers\Api
 */
class BookingController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $bookings = Booking::all()->get('items');
        if (isset($bookings))
            return response()->json([
                'success' => true,
                'data' => Booking::all()->get('items')->simplePaginate(5),
            ]);
        return response()->json([
            'success' => false,
            'message' => 'There are no Bookings in the database'
        ]);
    }

    /**
     * @param Request $request
     * @param Cleaner $cleaner
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->except(['cleaner']), [
            'cleaner_id' => ['required', 'integer', 'exists:cleaners,id'],
            'date' => ['required', 'date'],
            'city_name' => ['required', 'exists:cities,name'],
            'name' => ['required', 'max:32'],
            'email' => ['required', 'email'],
            'phone_number' => ['required', new Phone]
        ]);
        if ($validator->fails()) {
            return response()->json(['Validation Error.' => $validator->errors()]);
        }

        $city = City::where('name', 'LIKE', $request->input('city_name'))->first();

        $booking = Booking::make([
            'date' => $request->input('date'),
        ]);
        $customer = Customer::firstOrNew([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
        ]);

        $cleaner = Cleaner::where('id', '=', $request->input('cleaner_id'))->first();
        if (!$customer)
            $customer->save();
        $saved = $booking->cleaner->save($cleaner);
        $saved = $booking->city->save($city);
        $saved = $booking->customer->save($customer);
        $saved = $booking->save();

        if ($saved) {
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'City could not be added'
        ], 500);
    }
}
