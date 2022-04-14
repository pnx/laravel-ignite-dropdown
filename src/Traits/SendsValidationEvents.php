<?php

namespace Ignite\Traits;

trait SendsValidationEvents
{
    public function addError($name, $message)
    {
        $bag = parent::addError($name, $message);
        $this->emit('validation.addError', $name, $message);
        return $bag;
    }

    public function setErrorBag($bag)
    {
        parent::setErrorBag($bag);
        $this->emit('validation.setErrorBag', $bag);
    }

    public function resetErrorBag($field = null)
    {
        parent::resetErrorBag($field);
        $this->emit('validation.resetErrorBag', $field);
    }
}
