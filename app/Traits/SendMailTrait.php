<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait SendMailTrait
{
    /**
     * Envía un correo electrónico usando una vista Blade como template.
     *
     * @param string      $to          Dirección de correo del destinatario
     * @param string      $subject     Asunto del correo
     * @param string      $template    Nombre de la vista Blade (ej. 'emails.reset-password')
     * @param array       $data        Variables que se inyectan en el template
     * @param string[]|null $bcc       Array de direcciones para copia oculta (opcional)
     * @param array[]|null  $attachments Array de adjuntos, cada uno con 'path' y opcionalmente 'options' (opcional)
     *                                   Ejemplo: [['path' => '/ruta/archivo.pdf', 'options' => ['as' => 'doc.pdf']]]
     */
    protected function sendMail(
        string $to,
        string $subject,
        string $template,
        array $data,
        ?array $bcc = null,
        ?array $attachments = null,
    ): void {
        Mail::send(
            $template,
            $data,
            function ($message) use ($to, $subject, $bcc, $attachments) {
                $message->to($to)->subject($subject);

                if ($bcc) {
                    foreach ($bcc as $address) {
                        $message->bcc($address);
                    }
                }

                if ($attachments) {
                    foreach ($attachments as $attachment) {
                        $options = $attachment['options'] ?? [];
                        $message->attach($attachment['path'], $options);
                    }
                }
            }
        );
    }
}
