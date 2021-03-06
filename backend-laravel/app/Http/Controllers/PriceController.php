<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Price::all();
    }

    public function pricesFiltered(Request $request){
      $prices = Price::filtered($request->all())
      ->orderBy('day', 'desc')->orderBy('start_date', 'desc')->first();
      return $prices;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), Price::VALIDATOR_OPTIONS);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->messages()], 200);
        }
        $price = new Price();
        $price->fill($request->all());

        if(empty($request->start_date) || empty($request->end_date)){
          $price->start_date = null;
          $price->end_date = null;
        }
        $price->save();
        return $price;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        return $price;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price)
    {
        $validator = Validator::make($request->all(), Price::VALIDATOR_OPTIONS);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->messages()], 200);
        }

        $price->fill($request->all());

        if(empty($request->start_date) || empty($request->end_date)){
          $price->start_date = null;
          $price->end_date = null;
        }

        $price->save();
        return $price;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        $price->delete();
        return ['response' => "price deleted succesfully"];
    }
}
