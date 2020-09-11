<?php
namespace Domi\RecaptchaBundle\Constraints;

use Symfony\Component\Validator\Constraint;

class Recaptcha extends Constraint {
    /**
     * Undocumented variable
     *
     * @var string
     */
    public $message = 'Invalid captcha';

    
}
?>