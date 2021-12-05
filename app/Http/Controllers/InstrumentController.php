<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstrumentRequest;
use App\Http\Requests\UpdateInstrumentRequest;
use App\Models\Instrument;

class InstrumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instruments = Instrument::all();
        return $this->buildResponse('instrument.list', $instruments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->buildResponse('instrument.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInstrumentRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreInstrumentRequest $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email|max:255',
            'birth_date' => 'required|date',
        ]);

        $date = strtotime($request->get('birth_date'));

        Instrument::create(
            [
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'birth_date' => date('Y-m-d', $date),
            ]
        );

        return redirect('/instrument')->with('success', 'Instrument has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instrument  $instrument
     * @return \Illuminate\Http\Response
     */
    public function show(Instrument $instrument)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instrument  $instrument
     * @return \Illuminate\Http\Response
     */
    public function edit(Instrument $instrument)
    {
        $date = strtotime($instrument->birth_date);

        $instrument->birth_date = Date('d-m-Y', $date);
        return $this->buildResponse('instrument.edit', $instrument);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInstrumentRequest  $request
     * @param  \App\Models\Instrument  $instrument
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateInstrumentRequest $request, Instrument $instrument)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email|max:255',
            'birth_date' => 'required|date',
        ]);
        $date = strtotime($request->get('birth_date'));

        $instrument->fill([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'birth_date' => date('Y-m-d', $date),
        ])->save();

        return back()->with('success', 'Instrument successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Instrument $instrument
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Instrument $instrument)
    {

        try {
            $instrument->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Instrument deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error deleting the instrument!']);
        }
    }
}
