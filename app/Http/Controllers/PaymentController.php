<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index() {
        $payments = Payment::all();

        $index = 0;
        foreach ($payments as $payment) {
            $user = User::where('id', $payment->user_id)->first(['id', 'name']);
            $subscription = Subscription::where('payment_id', $payment->id)->first('id');
            if(!empty($subscription)) {
                $payments[$index]->subscription_id = $subscription->id;
            }

            if(! empty($user)) {
                $payments[$index]->user = $user;
            }
            $index++;
        }

        return $this->buildResponse('payment.list', $payments);
    }

    public function create() {
        $users = User::all('id', 'name');
        $subscriptions_ids = Subscription::whereNull('payment_id')->get('id');

        $data = array(
           'users' => $users,
            'subscriptions' => $subscriptions_ids
        );
        return $this->buildResponse('payment.create', $data);
    }

    public function store(StorePaymentRequest $request) {
        $validated = $request->validate([
            'user_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'payment_method' => ['required', Rule::in('cash', 'bank_transfer', 'card', 'online')],
            'status' => ['required', Rule::in('paid', 'pending', 'canceled', 'postponed')],
            'subscription_id' => 'numeric|exists:App\Models\Subscription,id'
        ]);

        $payment = Payment::create($request->all());

        if(!empty($request->subscription_id)) {
            $subscription = Subscription::where('id', $request->subscription_id)->first();
            if(!empty($subscription)) {
                $subscription->payment_id = $payment->id;
                if($payment->status === 'paid' && isset($request->activate_subscription)) {
                    $subscription->status = 'active';
                }
                $subscription->save();
            }
        }

        return redirect('/payment')->with('success', 'Payment has been added successfully!');
    }

    public function edit(Payment $payment) {
        $users = User::all('id', 'name');
        $subscriptions_ids = Subscription::whereNull('payment_id')->get('id');
        $selected_sub_id = Subscription::where('payment_id', $payment->id)->first('id');

        $payment->users = $users;
        $payment->subscriptions = $subscriptions_ids;
        $payment->selected_sub_id = $selected_sub_id;

        return $this->buildResponse('payment.edit', $payment);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment) {
        $validated = $request->validate([
            'user_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'payment_method' => ['required', Rule::in('cash', 'bank_transfer', 'card', 'online')],
            'status' => ['required', Rule::in('paid', 'pending', 'canceled', 'postponed')],
            'subscription_id' => 'numeric|exists:App\Models\Subscription,id'
        ]);


        $payment->fill($request->all())->save();
        $subscription = Subscription::where('id', $request->subscription_id)->first();
        if(! empty($subscription)) {
            $subscription->payment_id = $payment->id;
            if($payment->status === 'paid' && isset($request->activate_subscription)) {
                $subscription->status = 'active';
            }
            $subscription->save();
        }

        return redirect('/payment')->with('success', 'Payment successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Payment $payment)
    {

        try {
            $payment->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Payment deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error deleting the payment!']);
        }
    }

    public static function createPaymentRequest($amount) {
        $payment = new Payment();
        $payment->amount = $amount;
        $payment->status = 'pending';

        $payment->save();

        return $payment;
    }
}
