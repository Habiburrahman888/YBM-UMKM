<?php

namespace App\Mail;

use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnitCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Unit $unit;
    public string $loginEmail;
    public string $defaultPassword;

    public function __construct(Unit $unit, string $loginEmail, string $defaultPassword = '12345678')
    {
        $this->unit            = $unit;
        $this->loginEmail      = $loginEmail;
        $this->defaultPassword = $defaultPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[' . config('app.name') . '] Unit Anda Telah Dibuat – ' . $this->unit->nama_unit,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.unit-created',
            with: [
                'unit'            => $this->unit,
                'loginEmail'      => $this->loginEmail,
                'defaultPassword' => $this->defaultPassword,
            ],
        );
    }
}