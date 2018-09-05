<?php

namespace App\Validations;


class UploadValidator
{
    public function addImage()
    {
        $res['rules'] = [
            'filename.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
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