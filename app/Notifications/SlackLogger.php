<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackLogger extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable): SlackMessage
    {
        $status = $notifiable->response_status_code ? $notifiable->response_status_code : $notifiable->acknowledgement_status_code;
        $desc = $notifiable->response_status_desc ? $notifiable->response_status_desc : $notifiable->acknowledgement_status_desc;

        return (new SlackMessage)->content("An error has occurred with STATUS -> {$status} and DESCRIPTION -> {$desc} for covernote number {$notifiable->covernote_number}");
        // ->attachment(function ($attachment) use ($notifiable) {
        //     $attachment->fields($notifiable->customer->toArray());
        // });
    }
}
