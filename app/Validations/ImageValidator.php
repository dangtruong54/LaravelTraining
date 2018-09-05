<?php
/**
 * Created by PhpStorm.
 * User: truon
 * Date: 8/27/2018
 * Time: 11:22 AM
 */

namespace App\Validations;


class ImageValidator
{
    public function addImage($fileName)
    {
        $res['rules'] = [
            $fileName => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'title'    => 'required',
            'content'  => 'required'
        ];
        $res['messages'] = [];
        $res['attributes'] = [];

        return $res;
    }
    public function checkValidator($data, $validators)
    {
        $rules = isset($validators['rules']) ? $validators['rules'] : [];
        $messages = isset($validators['messages']) ? $validators['messages'] : [];
        $attributes = isset($validators['attributes']) ? $validators['attributes'] : [];

        $validators = validator($data, $rules, $messages, $attributes);
        return $validators;
    }
}