<?php


namespace App\Services;


use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class ExpireDateReminderService
{
    public function __invoke()
    {
        // $this->expiredThreeDaysAgo();
         $this->expireToday();
        // $this->expiresInThreeDays();
        // $this->expiresInSevenDays();
        // $this->expiresInAMonth();
    }

    public function expiredThreeDaysAgo()
    {
        $transactions = Transaction::expiredThreeDaysAgo()->get()->toArray();

        foreach ($transactions as $transaction) {
            $this->smsNotifier("{$transaction['customer']['full_name']}, Your insurance expired 3 days ago", $transaction['customer']['phone_number']);
        }
    }

    public function expireToday()
    {
        $transactions = Transaction::expiresToday()->get()->toArray();

        foreach ($transactions as $transaction) {
            $this->smsNotifier("{$transaction['customer']['full_name']}, Your insurance expires today", $transaction['customer']['phone_number']);
        }
    }

    public function expiresInThreeDays()
    {
        $transactions = Transaction::expiresInThreeDays()->get()->toArray();

        foreach ($transactions as $transaction) {
            $this->smsNotifier("{$transaction['customer']['full_name']}, Your insurance expires in 3 days", $transaction['customer']['phone_number']);
        }
    }

    public function expiresInSevenDays()
    {
        $transactions = Transaction::expiresInSevenDays()->get()->toArray();

        foreach ($transactions as $transaction) {
            $this->smsNotifier("{$transaction['customer']['full_name']}, Your insurance expires in 7 days", $transaction['customer']['phone_number']);
        }
    }

    public function expiresInAMonth()
    {
        $transactions = Transaction::expiresInAMonth()->get()->toArray();

        foreach ($transactions as $transaction) {
            $this->smsNotifier("{$transaction['customer']['full_name']}, Your insurance expires in a month", $transaction['customer']['phone_number']);
        }
    }

    public function smsNotifier($message, $recipient)
    {
        Http::withOptions(['verify' => false])->post(env('SMS_URL'), [
            'channel' => [
                'channel' => env('SMS_CHANNEL'),
                'password' => env('SMS_PASSWORD')
            ],
            'messages' => [
                [
                    'text' => $message,
                    'msisdn' => $recipient,
                    'source' => env('SMS_SOURCE')
                ]
            ]
        ]);
    }

}