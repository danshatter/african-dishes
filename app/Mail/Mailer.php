<?php

namespace App\Mail;

class Mailer
{

    // Messenger
    private static $messenger;

    // Mailer
    private static $mailer;

    // Path to our logo;
    private static $logoPath = __DIR__.'/../../public_html/images/logo.png'; 
    
    /**
     * This Mail will be sent when a user successfully signs up
     */
    public static function signUpMail($user)
    {
        $from = ['support@chrystacripsy.com.ng' => 'Chrystacripsy'];
        
        $subject = 'Welcome to Chrystacrispy';
        
        $name = self::format($user->first_name);

        $logo = \Swift_Image::fromPath(self::$logoPath);

        $htmlBody = '<img src="'.self::getMessenger()->embed($logo).'" style="width: 100%; height: auto;" />

                    <p>Dear '.$name.',</p>

                    <p>Thank you for signing with us. We are pleased to inform you that your registration was successful. You can now login with your sign up credentials.</p>

                    <p>You could browse our website to check our menu list to see if a food of ours appeal to you.</p>

                    <p>Thank you.</p>';
        
        $plainBody =<<<BODY
Dear $name,

Thank you for signing with us. We are pleased to inform you that your registration was successful. You can now login with your sign up credentials.

You could browse our website to check our menu list to see if a food of ours appeal to you.

Thank you.
BODY;

        self::getMessenger()->setSubject($subject)
                            ->setTo($user->email)
                            ->setFrom($from)
                            ->setBody($htmlBody, 'text/html')
                            ->addPart($plainBody, 'text/plain');

        self::getMailer('support@chrystacripsy.com.ng')->send(self::getMessenger());
    }

    /**
     * This Mail will be sent when a user makes a password reset request.
     * 
     * It will also be sent when a user makes a resend email request
     */
    public static function resetPasswordMail($user, $token)
    {
        $from = ['support@chrystacripsy.com.ng' => 'Chrystacripsy'];
        
        $subject = 'Password Recovery';

        $link = 'https://'.$_ENV['HOST'].'/recovery/'.$user->id.'/'.$token;
        
        $name = self::format($user->first_name);

        $logo = \Swift_Image::fromPath(self::$logoPath);

        $htmlBody = '<img src="'.self::getMessenger()->embed($logo).'" style="width: 100%; height: auto;" />

                    <p>Dear '.$name.',</p>

                    <p>Thank you for being a loyal customer thus far. Please click on the link below or copy and paste the URL link recover your password</p>

                    <a href="'.$link.'">'.$link.'</a>

                    <p>Please endeavour to remember your password this time.</p>

                    <p>Thank you.</p>';
        
        $plainBody =<<<BODY
Dear $name,

Thank you for being a loyal customer thus far. Please click on the link below or copy and paste the URL link recover your password

$link

Please endeavour to remember your password this time.

Thank you.
BODY;

        self::getMessenger()->setSubject($subject)
                            ->setTo($user->email)
                            ->setFrom($from)
                            ->setBody($htmlBody, 'text/html')
                            ->addPart($plainBody, 'text/plain');

        self::getMailer('support@chrystacripsy.com.ng')->send(self::getMessenger());

    }
    
    /**
     * This Mail will be sent when an order was successful.
     */
    public static function orderSuccessful($email)
    {
        $from = ['payment@chrystacripsy.com.ng' => 'Chrystacripsy'];
        
        $subject = 'Order Successful';
        
        $name = self::format($user->first_name);

        $logo = \Swift_Image::fromPath(self::$logoPath);

        $htmlBody = '<img src="'.self::getMessenger()->embed($logo).'" style="width: 100%; height: auto;" />

                    <p>Dear Customer,</p>

                    <p>Thank you for patronizing us. This email is to let you know that your order was successful</p>

                    <p>Please be assured that your order is being processed and we will ensure to deliver your food as fast as possible</p>

                    <p>Thank you.</p>';
        
        $plainBody =<<<BODY
Dear Customer,

Thank you for patronizing us. This email is to let you know that your order was successful

Please be assured that your order is being processed and we will ensure to deliver your food as fast as possible

Thank you.
BODY;

        self::getMessenger()->setSubject($subject)
                            ->setTo($email)
                            ->setFrom($from)
                            ->setBody($htmlBody, 'text/html')
                            ->addPart($plainBody, 'text/plain');

        self::getMailer('payment@chrystacripsy.com.ng')->send(self::getMessenger());

    }

    /**
     * Mailer Instance
     */
    private static function getMailer($username)
    {
        if (!isset(self::$mailer)) {
            // Mail Transport
            $transport = (new \Swift_SmtpTransport)
                        ->setHost($_ENV['MAIL_HOST'])
                        ->setPort($_ENV['MAIL_PORT'])
                        ->setUsername($username)
                        ->setPassword($_ENV['MAIL_PASSWORD'])
                        ->setEncryption($_ENV['MAIL_ENCRYPTION']);

            // Mailer Initialization
            return self::$mailer = new \Swift_Mailer($transport);
        }

        return self::$mailer;
    }

    /**
     * Message Instance
     */
    private static function getMessenger() {
        if (!isset(self::$messenger)) {
            return self::$messenger = new \Swift_Message;
        }

        return self::$messenger;
    }

    /**
     * This function is to format a value so that only the start letter is uppercase
     */
    private static function format($value)
    {
        $newValue = strtolower($value);

        return ucfirst($newValue);
    }

}