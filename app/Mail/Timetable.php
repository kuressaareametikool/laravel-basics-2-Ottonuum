<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Timetable extends Mailable
{
    use Queueable, SerializesModels;

    public $timetableEvents;
    public $startDate;
    public $endDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($timetableEvents, $startDate, $endDate)
    {
        $this->timetableEvents = $timetableEvents;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.timetable')
                    ->with([
                        'timetableEvents' => $this->timetableEvents,
                        'startDate' => $this->startDate,
                        'endDate' => $this->endDate,
                    ]);
    }
}
