<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Instrument;
use App\Models\Room;
use App\Models\Teacher;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::all();

        $index = 0;
        foreach ($teachers as $teacher) {
            $teachers[$index]['instruments'] = Instrument::whereIn('id', json_decode($teacher->instrument_ids))->get(['id', 'name']);
            $teachers[$index]['room'] = Room::where('id', $teacher->room_id)->first(['id', 'name']);
            $index++;
        }

        return $this->buildResponse('teacher.list', $teachers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $instruments = Instrument::all(['id', 'name']);
        $rooms = Room::all(['id', 'name']);
        return $this->buildResponse('teacher.create', ['instruments' => $instruments, 'rooms' => $rooms]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTeacherRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTeacherRequest $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email|max:255',
            'birth_date' => 'required|date',
            'instrument_ids' => 'required|array',
            'instrument_ids.*' => 'exists:instruments,id',
            'room_id' => 'exists:rooms,id',
        ]);

        $date = strtotime($request->get('birth_date'));

        Teacher::create(
            [
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'birth_date' => date('Y-m-d', $date),
                'google_calendar_id' => $request->get('google_calendar_id'),
                'instrument_ids' => json_encode($request->get('instrument_ids')),
                'room_id' => $request->get('room_id')
            ]
        );

        return redirect('/teacher')->with('success', 'Teacher has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {

        $teacher['instruments'] = Instrument::all(['id', 'name']);
        $teacher['rooms'] = Room::all(['id', 'name']);
        $date = strtotime($teacher->birth_date);

        $teacher->birth_date = Date('d-m-Y', $date);
        return $this->buildResponse('teacher.edit', $teacher);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTeacherRequest  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        //TODO: fix format date bug
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email|max:255',
            'birth_date' => 'required|date',
            'instrument_ids' => 'required|array',
            'instrument_ids.*' => 'exists:instruments,id',
            'room_id' => 'exists:rooms,id',
        ]);

        $date = strtotime($request->get('birth_date'));

        $teacher->fill([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'birth_date' => date('Y-m-d', $date),
            'google_calendar_id' => $request->get('google_calendar_id'),
            'instrument_ids' => json_encode($request->get('instrument_ids')),
            'room_id' => $request->get('room_id')
        ])->save();

        return back()->with('success', 'Teacher successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Teacher $teacher
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Teacher $teacher)
    {

        try {
            $teacher->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Teacher deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error deleting the teacher!']);
        }
    }
}
