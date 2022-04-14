<?php

namespace Ignite\Traits;

trait ReceivesValidationEvents
{
    protected function getListeners()
    {
        $listeners = [
            'validation.addError' => 'onValidationAddError',
            'validation.setErrorBag' => 'onValidationSetErrorBag',
            'validation.resetErrorBag' => 'onValidationResetErrorBag',
        ];

        return array_merge($this->listeners, $listeners);
    }

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
