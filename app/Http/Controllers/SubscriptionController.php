<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index() {
        $subcriptions = Subscription::with([
            'student',
            'subscription_type'
        ])->get();

        return $this->buildResponse('subscription.list', $subcriptions);
    }

    public function create() {
        $students = Student::all(['id', 'first_name', 'last_name']);
        $subscription_types = SubscriptionType::all(['id', 'name', 'sessions_number']);
        $payments_ids = Payment::all(['id']);
        $statuses = [
            'active',
            'canceled',
            'expired',
            'pending'
        ];

        return $this->buildResponse('subscription.create', [
            'students' => $students,
            'subscription_types' => $subscription_types,
            'payments_ids' => $payments_ids,
            'statuses' => $statuses,
        ]);
    }

     public function store(StoreEventRequest $request)
     {
         // TODO: create a payment request
         $validated = $request->validate([
             'student_id' => 'required|exists:students,id',
             'subscription_type_id' => 'required|exists:subscription_types,id',
             'status' => 'required|in:active,canceled,expired,pending',
             'starting' => 'required|max:255',
         ]);

         $subscription_type = SubscriptionType::where('id', $request->subscription_type_id)->first();

         $payment_request = PaymentController::createPaymentRequest($subscription_type->price);
         $starting_date = \DateTime::createFromFormat('d/m/Y', $request->starting);

         // TODO: this should be auto-calculated
         $ending_date = Carbon::createFromFormat('d/m/Y',  $request->starting);
         $ending_date->addWeeks((int)$subscription_type->sessions_number -1);
         Subscription::create(
             [
                 'student_id' => $request->get('student_id'),
                 'subscription_type_id' => $request->get('subscription_type_id'),
                 'payment_id' => $payment_request->id,
                 'status' => $request->get('status'),
                 'starting' => $starting_date,
                 'ending' => $ending_date,
             ]
         );

         return redirect('/subscription')->with('success', 'Subscription has been added successfully!');
     }

    public function destroy(Subscription $subscription)
    {

        try {
            $subscription->status = 'canceled';
            $subscription->save();
            return redirect()
                ->back()
                ->with('success', 'Subscription canceled successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error canceling the subscription!']);
        }
    }

    public function edit(Subscription $subscription) {
        $students = Student::all(['id', 'first_name', 'last_name']);
        $subscription_types = SubscriptionType::all(['id', 'name', 'sessions_number']);
        $payments_ids = Payment::all(['id']);
        $statuses = [
            'active',
            'canceled',
            'expired',
            'pending'
        ];

        $subscription->students = $students;
        $subscription->subscription_types = $subscription_types;
        $subscription->payments_ids = $payments_ids;
        $subscription->statuses = $statuses;

        return $this->buildResponse('subscription.edit', $subscription);
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription) {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subscription_type_id' => 'required|exists:subscription_types,id',
            'status' => 'required|in:active,canceled,expired,pending',
            'starting' => 'required|max:255',
            'payment_id' => 'required|exists:payments,id',
        ]);
        $starting_date = Carbon::createFromFormat('d/m/Y', $request->starting);

        $subscription_type = SubscriptionType::where('id', $request->subscription_type_id)->first();
        $ending_date = Carbon::createFromFormat('d/m/Y',  $request->starting);
        $ending_date->addWeeks((int)$subscription_type->sessions_number -1);

        $request->ending_date = $ending_date;
        $request->starting_date = $starting_date;

        // TODO: we finished here

        $subscription->fill($request->all())->save();

        return back()->with('success', 'Subscription successfully updated!');
    }

    }
