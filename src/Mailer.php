<?php

namespace DabelloWdg;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($to)
    {
        $code = rand(1500, 9999);
        $body = "Hello {$to}\n,
Thank you for registering on DabelloWdg app. With DabelloWdg you can take photos and keep them safe on remote web space.\n
In order to start to use the app you only need one more step. Please use the following code on the splash screen:\n
\n
        {$code}
\n
Enjoy,
DabelloWdg
";

        $message = \Swift_Message::newInstance()
            ->setSubject('[DabelloWdg] Welcome to DabelloWdg')
            ->setFrom(array('matrimonio@sidalab.solutions'))
            ->setTo(array($to, 'dymissy86@gmail.com', 'andrea.cerra@me.com'))
            ->setBody($body);

        return $this->mailer->send($message);
    }
}
