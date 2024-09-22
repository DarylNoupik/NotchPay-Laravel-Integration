<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NotchPay\NotchPay;
use NotchPay\Payment;


class PaymentController extends Controller
{
    /**
     * Handle the incoming request.
     */


    public function __invoke(Request $request)
    {
        $apiKey = env('NOTCHPAY_PUBLIC_KEY');

        NotchPay::setApiKey($apiKey);

        try {
            $payload = Payment::initialize([
                'amount' => "1000" ,
                'email' => "Gildas@yopmail.com",
                'name' => "Gonzomor",
                'currency' => 'XAF',
                'reference' => "CM" . '-' . uniqid(),
                'callback' => route('notchpay-callback'),
            ]);
            return redirect($payload->authorization_url);

        } catch(\NotchPay\Exceptions\ApiException $e){

            session()->flash('error', __('Impossible de procéder au paiement, veuillez recommencer plus tard. Merci'));
 
            return back();
        }

    }

    public function callback(Request $request): RedirectResponse
    {
        NotchPay::setApiKey($apiKey);
        $verifyTransaction = Payment::verify($request->get('reference'));

        if($verifyTransaction->transaction->status === 'complete'){
            session()->flash('status',__('Votre Paiement a été effectué avec succès'));
        } else {
            session()->flash('error',__('Votre paiement a été annulé'));
        }

        return redirect(route('welcome'));
    }
}
