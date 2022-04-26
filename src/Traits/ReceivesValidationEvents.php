<?php

namespace Ignite\Traits;

trait ReceivesValidationEvents
{
    protected $validation_listeners = [
        'validation.addError' => 'onValidationAddError',
        'validation.setErrorBag' => 'onValidationSetErrorBag',
        'validation.resetErrorBag' => 'onValidationResetErrorBag',
    ];

    public function onValidationAddError($name, $message)
    {
        $this->addError($name, $message);
    }

    public function onValidationSetErrorBag($bag)
    {
        $this->setErrorBag($bag);
    }

    public function onValidationResetErrorBag($field)
    {
        $this->resetErrorBag($field);
    }
}
