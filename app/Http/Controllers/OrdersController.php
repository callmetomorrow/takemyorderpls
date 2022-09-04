<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\TelegramBot\TelegramBot;


use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramNotificationJob;
use Illuminate\Support\ValidatedInput;
use Illuminate\Support\Facades\Validator;
//use App\Http\Requests\OrderRequest;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = json_decode(Order::all());
        //var_dump($orders);
        echo '<table>';
        foreach($orders as $key => $value) {
            foreach($value as $attr => $name) {
                if($attr == 'userinfo') {
                    $userinfoArr = json_decode($name);
                    echo '<tr><td><b>User Info</b></td><td><table>';
                        foreach($userinfoArr as $uiName => $uiValue) {
                            echo "<tr><td>$uiName</td><td>$uiValue</td></tr>";
                        }
                    echo '</table></td></tr>';
                } else {
                    echo "<tr><td><b>$attr</b></td><td>$name</td></tr>";
                }
            }
        }
        echo '</table>';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        
        $order = Order::create($request->all());
        //$order_id = Order::insertGetId($request->all());
        
        $user = User::where('role', 'subscriber')->first();
        
        if(is_null($user)) {
            return $order;
        }
        
        SendTelegramNotificationJob::dispatch($to = $user->chat_id, $what = $request->input('phone'), $order->id);
        //$telegram = new TelegramBot('SendMessage', $user->chat_id, 'Хтось цікавиться курсами! Зателефонуй якомога скоріше: +380' . phoneFormatUnified($request->input('phone')));
        
        $order->sent = true;    
        $order->save();
        
        return $order;
        //return response()->json('OK', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
