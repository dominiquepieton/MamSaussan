<?php
namespace Domi\RecaptchaBundle\Constraints;

use ReCaptcha\ReCaptcha;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintValidator;

class RecaptchaValidator extends ConstraintValidator {
    
    
    /**
     * @var Recaptcha
     */
    public $reCaptcha;
    
    /**
     * @var RequestStack
     */
    private $requestStack;
   
    public function __construct(RequestStack $requestStack,ReCaptcha $reCaptcha)
    {
        $this->requestStack = $requestStack;
        $this->reCaptcha = $reCaptcha;
    }
    
    
    public function validate($value, Constraint $constraint)
    {
        $request = $this->requestStack->getCurrentRequest();
        
        $recaptchaResponse = $request->request->get('g-recaptcha-reponse');
        if(empty($recaptchaResponse)) {
            $this->addViolation($constraint);
            return;
        }

        $response = $this->reCaptcha
            ->setExpectedHostname($request->getHost())
            ->verify($recaptchaResponse, $request->getClientIp());

        if(!$response->isSuccess()) {
            $response->getErrorCodes();
            $this->addViolation($constraint);
        }
    }

    public function addViolation(Constraint $constraint) {
        return $this->context->buildViolation($constraint->message)->addViolation();
    }

}
?>