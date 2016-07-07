<?php
namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $errors;

    /**
    * As you can see, validate takes three (3) arguments.
    * The last, $message, makes it possibel for you to make your
    * own custom error messages. Just add an array of messages for
    * you validations.
    *
    * @param $request
    * @param $rules
    * @param array $message
    *
    * @return string/s
    */
    public function validate($request, array $rules, array $message = null)
    {
        // Unset previous error messages(necessary?)
        unset($_SESSION['errors']);

        foreach ($rules as $field => $rule) {
            try {
                // $rule->setName(ucfirst($field))->assert($request->getParam($field));
                $rule->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                if(isset($message)){
                    $errors = $e->findMessages($message);
                }
                $this->errors[$field] = $e->getMessages();
            }
        }

        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}