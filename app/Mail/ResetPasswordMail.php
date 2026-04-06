<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $userName;
    public $email;
    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $userName)
    {
        $this->token = $token;
        // Extraemos los datos del objeto/array que pasaste (según tu JSON anterior)
        $this->userName = is_object($userName) ? $userName->nombre : $userName['nombre'];
        $this->email = is_object($userName) ? $userName->email : $userName['email'];

        // Generamos la URL
        $this->url = url('/password/reset/' . $token);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@delmor.com.ni', 'Notificación'),
            subject: 'Recuperación de Contraseña - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset_password', // Nombre de la vista Blade
            with: [
                'token' => $this->token,
                'userName' => $this->userName,
                'email' => $this->email,
                'url' => $this->url,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
