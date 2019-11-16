<?php

namespace App\Http\Controllers\API;

use App\Deposits;
use App\Http\Resources\DepositCollection;
use App\Http\Resources\QuoteCollection;
use App\Http\Resources\QuoteResource;
use App\Quote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class QuotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return QuoteCollection
     */
    public function index()
    {
        $quotes = Quote::with('deposits')->get();

        return new QuoteCollection($quotes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return QuoteResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_name' => 'required',
            'customer_email' => 'required|email|unique:quotes',
            'customer_address' => 'required',
            'car_brand' => 'required',
            'car_model' => 'required',
            'car_year' => 'required',
            'car_price' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $data = $request->all();
        $data['car_price'] = str_replace(",", "", $data['car_price']);
        $data['date'] = Carbon::parse($data['date'])->format('Y-m-d');
        $data['isPaid'] = 0;
        $quote = Quote::create($request->all());

        return new QuoteResource($quote);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getDeposits($id)
    {
        $deposits = Deposits::whereNotIn('id', function ($query){
            $query->from('payments')->select('deposit_id');
        })->get();

        return new DepositCollection($deposits);
    }

    /**
     * @param Request $request
     * @param $id
     * @return QuoteResource
     */
    public function addDeposits(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);

        $quote->deposits()->attach($request->depositsSelected);

        $amount = 0;
        foreach ($quote->deposits as $deposit){
            $amount += $deposit['total'];
        }

        if($quote->car_price == $amount){
            $quote->update(['isPaid' => 1]);
        }

        return new QuoteResource($quote);
    }
}
