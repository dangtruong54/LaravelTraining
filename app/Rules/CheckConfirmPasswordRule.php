<?php

namespace App\Rules;


use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;

class CheckConfirmPasswordRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private $checkConfirmPassword;

    public function __construct(Request $request)
    {
        //
        $password = $request->get('password');
        $confirmedPassword = $request->get('password_confirmation');

        if($password === $confirmedPassword)
        {
            $this->checkConfirmPassword = true;
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if( $this->checkConfirmPassword){
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute do not match password!!!';
    }
}
