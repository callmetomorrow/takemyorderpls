<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\TelegramBot\TelegramBot;

class TelegramUpdatesController extends Controller
{
    public function __invoke(UpdateRequest $request) {
        $validated = $request->validated();
        
        $telegram = new TelegramBot('SendMessage', $validated['message']['chat']['id'], $validated['message']['text']);
        
        return response()->json($request->all());
    }
}
