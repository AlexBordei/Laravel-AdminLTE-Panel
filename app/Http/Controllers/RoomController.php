<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Instrument;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::all();

        $index = 0;
        foreach ($rooms as $room) {
            $rooms[$index]['instruments'] = Instrument::whereIn('id', json_decode($room->instrument_ids))->get(['id', 'name']);
            $index++;
        }

        return $this->buildResponse('room.list', $rooms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $instruments = Instrument::all(['id', 'name']);
        return $this->buildResponse('room.create', ['instruments' => $instruments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRoomRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'instrument_ids' => 'required|array',
            'instrument_ids.*' => 'exists:instruments,id',
        ]);

        Room::create([
            'name' => $request->get('name'),
            'instrument_ids' => json_encode($request->get('instrument_ids'))
            ]
        );

        return redirect('/room')->with('success', 'Room has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        $room['instruments'] = Instrument::all(['id', 'name']);
        return $this->buildResponse('room.edit', $room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRoomRequest  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'instrument_ids' => 'required|array',
            'instrument_ids.*' => 'exists:instruments,id',
        ]);

        $room->fill([
            'name' => $request->get('name'),
            'instrument_ids' => json_encode($request->get('instrument_ids'))
        ])->save();

        return back()->with('success', 'Room successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Room $room
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Room $room)
    {

        try {
            $room->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Room deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error deleting the room!']);
        }
    }
}
