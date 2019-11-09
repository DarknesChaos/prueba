<?php

namespace App\Http\Controllers\API;

use App\Deposits;
use App\Http\Resources\DepositCollection;
use App\Http\Resources\DepositResource;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepositsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return DepositCollection
     */
    public function index()
    {
        $deposits = Deposits::all();
        return new DepositCollection($deposits);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return DepositResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'total' => 'required|numeric',
            'date' => 'required|date'
        ]);

        $data = $request->all();
        $data['date'] = Carbon::parse($data['date'])->format('Y-m-d');
        $data['total'] = str_replace(",", "", $data['total']);
        $deposit = Deposits::create($data);

        return new DepositResource($deposit);
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
     * @return DepositResource
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'description' => 'required',
            'total' => 'required|numeric',
            'date' => 'required|date'
        ]);

        $deposit = Deposits::findOrFail($id);
        $deposit->fill($request->all());
        $deposit->save();

        return new DepositResource($deposit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return DepositResource
     */
    public function destroy($id)
    {
        $deposit = Deposits::findOrFail($id);
        $deposit->delete();

        return new DepositResource($deposit);
    }
}
