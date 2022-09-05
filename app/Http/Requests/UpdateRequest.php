<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'update_id'       => ['required', 'integer'],
            'message.chat.id' => ['required', 'integer'],
            'message.text'    => ['required', 'string'],
        ];
    }
}

/*
array (
    'update_id' => 10000,
    'message' => 
      array (
          'date' => 1441645532,
          'chat' => 
              array (
              'last_name' => 'Test Lastname',
              'type' => 'private',
              'id' => 1111111,
              'first_name' => 'Test Firstname',
              'username' => 'Testusername',
              ),
          'message_id' => 1365,
          'from' => 
              array (
              'last_name' => 'Test Lastname',
              'id' => 1111111,
              'first_name' => 'Test Firstname',
              'username' => 'Testusername',
              ),
          'text' => '/start',
          'reply_to_message' => 
              array (
              'date' => 1441645000,
              'chat' => 
                  array (
                      'last_name' => 'Reply Lastname',
                      'type' => 'private',
                      'id' => 1111112,
                      'first_name' => 'Reply Firstname',
                      'username' => 'Testusername',
                  ),
          'message_id' => 1334,
          'text' => 'Original',
          ),
      ),
  )  
*/
