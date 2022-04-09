<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\Instrument;
use App\Models\Room;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class BandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bands = Band::with('students')->get();

        return $this->buildResponse('band.list', $bands);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $band_subscriptions = Subscription::where(['subscription_type_id' => SubscriptionType::where('name', 'Band')->first('id')->id, 'status' => 'active'])->with('student')->get('student_id');
        $students = [];
        foreach ($band_subscriptions as $band_subscription) {
            $students[] = [
                'value' => $band_subscription->student_id,
                'label' => $band_subscription->student->first_name . ' ' . $band_subscription->student->last_name,
            ];
        }
        return $this->buildResponse('band.create', ['students' => $students]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'students.*' => 'exists:students,id',
            ],
            [
                'name.required' => 'Band name is required'
            ]
        );

        $band = Band::create(
            [
                'name' => $request->get('name'),
            ]
        );
        $band->students()->attach($request->get('students'));

        return redirect('/band')->with('success', 'Band has been added successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Band  $band
     * @return \Illuminate\Http\Response
     */
    public function show(Band $band)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Band  $band
     * @return \Illuminate\Http\Response
     */
    public function edit(Band $band)
    {
        $band_subscriptions = Subscription::where(['subscription_type_id' => SubscriptionType::where('name', 'Band')->first('id')->id, 'status' => 'active'])->with('student')->get('student_id');;
        $selected_data = [];

        foreach ($band->students as $selected_student) {
            $selected_data[] = $selected_student->id;
        }

        $students = [];
        foreach ($band_subscriptions as $band_subscription) {
            $students[] = [
                'value' => $band_subscription->student_id,
                'label' => $band_subscription->student->first_name . ' ' . $band_subscription->student->last_name,
            ];
        }

        return $this->buildResponse('band.edit', [
            'band' => $band,
            'students' => $students,
            'selected_data' => $selected_data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Band  $band
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Band $band)
    {
        $request->validate(
            [
                'name' => 'required',
                'students.*' => 'exists:students,id',
            ],
            [
                'name.required' => 'Band name is required'
            ]
        );

        $band->fill(
            [
            'name' => $request->get('name')
            ]
        )->save();

        $band->students()->sync($request->get('students'));

        return redirect('/band')->with('success', 'Band has been updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Band $band
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function destroy(Band $band)
    {
        try {
            $band->students()->detach();
            $band->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Band deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error deleting the band!']);
        }
    }
}
