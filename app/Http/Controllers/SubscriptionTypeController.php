<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionTypeRequest;
use App\Http\Requests\UpdateSubscriptionTypeRequest;
use App\Models\Instrument;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class SubscriptionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptionTypes = SubscriptionType::all();

        return $this->buildResponse('subscriptionType.list', $subscriptionTypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->buildResponse('subscriptionType.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubscriptionTypeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSubscriptionTypeRequest $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'sessions_number' => 'required|numeric',
            'duration' => 'required|numeric',
            'instruments_number' => 'required|numeric',
            'students_number' => 'required|numeric',
        ]);

        SubscriptionType::create($request->all());

        return redirect('/subscription_type')->with('success', 'Subscription type has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubscriptionType  $subscriptionType
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriptionType $subscriptionType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubscriptionType  $subscriptionType
     * @return \Illuminate\Http\Response
     */
    public function edit(SubscriptionType $subscriptionType)
    {
        return $this->buildResponse('subscriptionType.edit', $subscriptionType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubscriptionTypeRequest  $request
     * @param  \App\Models\SubscriptionType  $subscriptionType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSubscriptionTypeRequest $request, SubscriptionType $subscriptionType)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'sessions_number' => 'required|numeric',
            'duration' => 'required|numeric',
            'instruments_number' => 'required|numeric',
            'students_number' => 'required|numeric',
        ]);

        $subscriptionType->fill($request->all())->save();

        return redirect()->route('subscription_type.index')->with('success', 'Subscription type successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\SubscriptionType $subscriptionType
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(SubscriptionType $subscriptionType)
    {

        try {
            $subscriptionType->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Subscription type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error deleting the subscription type!']);
        }
    }
}
