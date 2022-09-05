<?php

namespace App\TelegramBot;

use App\Models\Order;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isJson;

class TelegramBot {

    private $client;
    //Additional POST params
    protected $options = [];
    //Additionsl params for URL query 
    protected $params = [];

    protected $type = 'getUpdates';

    public function __construct($type = 'getUpdates', $chat_id = NULL, $message = NULL) {
        $this->type = $type;
        if(!empty($chat_id) && !empty($message)) $this->setReplyOptions($chat_id, $message);
        //info($type);
        $this->init($this->type, $this->options, $this->params);
    }

    public function init($type, $options, $params) {
        $data = $this->sendRequest($type, $options, $params);
        info($data);
        $response = $data->object()->result;
        //info($response->toArray());
        if($type === 'SendMessage') return;
        if(count($response) > 0) {
            $setReplyOptions = $this->handleUserMessageResponse($response);
            //info($setReplyOptions);
        } 
    }

    private function baseURL($method, $params) {
        $query = (!empty($params)) ? '?' . http_build_query($params) : '';
    
        return implode( config('telegram') ) . '/' . $method . $query;
    }

    private function sendRequest(string $method, ?array $options = [], ?array $params = [])
    {
        $response = Http::connectTimeout(3)
                        ->withHeaders($this->setHeaders())
                        ->post( $this->baseURL($method, $params), $options);

        //info( self::BASE_URI . '/' . $method . $query, $options );
        
        return $this->handleResponse($response);
    }

    private function handleResponse($response) {
        if($response->failed()) {
            return $response->object()->error_code;
        }
    
        return $response;
    }


    private function handleUserMessageResponse(array $data) {
        
        foreach($data as $update) {
            $update_id = $update->update_id ?? ''; 
            $chat_id = $update->message->chat->id ?? '';
            $message_id = $update->message->message_id ?? '';
            //dd($this->setOptions($options));
            $user_message = $update->message->text ?? '';
            $contact = $update->message->contact ?? '';
            // $user_name = $update->message->from->first_name ?? '';
            //info($contact->phone_number);
            $options = $this->setReplyOptions($chat_id, $user_message, $message_id, $contact);

            $response = $this->sendRequest('SendMessage', $options);
            if($response->ok()) {
                //info($response->object()->result);
                $this->params['offset'] = $update_id + 1;
                $response = $this->sendRequest('getUpdates',[], $this->params);
                return $response->object()->result;
            }
            
        }
    }


    private function getAllHeaders($response) {
        // Get all headers
        foreach ($response->getHeaders() as $name => $value) {
            echo $name . ': ' . $response->getHeaderLine($name) . "\n";
        }
    }

    private function setHeaders() {
        return [
            'Accept' => 'application/json',
        ];
    }

    private function setOptions($chat_id, $message_id) {
        
        $options = [
            'chat_id'    => $chat_id,
            'parse_mode' => 'HTML',
        ];
        if(!empty($message_id)) {
            $options['reply_to_message_id'] = $message_id;
        }

        return $options;
    }

    private function setReplyOptions($chat_id, string $message, $message_id = '', $is_contact = NULL):array {
        $message = strtolower($message);
        $reply  = $this->setOptions($chat_id, $message_id);
        switch ($message) {
            case '/start':
                $reply['text'] = 'Для підписки вкажіть свій номер телефона';
                $reply['reply_markup'] = json_encode($this->getKeyboard());
                break;
            case '/today':
                $today_orders = Order::whereDate('created_at', today())
                                        ->select('id', 'phone', 'lead', 'created_at')
                                        ->get();
                $reply['text'] = $this->arrayToText($today_orders->toArray());
                break;
            case '/yesterday':
                $yesterday_orders = Order::whereDate('created_at', now()->subDays(1))
                                        ->select('id', 'phone', 'lead', 'created_at')
                                        ->get();
                $reply['text'] = $this->arrayToText($yesterday_orders->toArray());
                break;
            case '/7days':
                $week_orders = Order::whereDate('created_at', now()->subDays(7))
                                    ->select('id', 'phone', 'lead', 'created_at')
                                    ->get();
                $reply['text'] = $this->arrayToText($week_orders->toArray());
                break;
            case '/thismonth':
                $this_month_orders = Order::whereYear('created_at', now()->year)
                                    ->whereMonth('created_at', now()->month)
                                    ->select('id', 'phone', 'lead', 'created_at')
                                    ->get();
                $reply['text'] = $this->arrayToText($this_month_orders->toArray());
                break;
            default:
                if(!preg_match('/\/id\d+/', $message)) {
                    $reply['text'] = $message;
                    info($message);
                    break;    
                }
                
                $user = User::where('chat_id', $chat_id)
                            ->first();
                if(is_null($user)) {
                    $reply['text'] = 'Спочатку потрібно підписатись /subscribe';
                    break;
                }
                $id = intval(str_replace('/id', '', $message));
                $order = Order::where('id', $id)->first();
                $order->lead = !boolval($order->lead);
                $order->save();
                $reply['text'] = 'Статус замовлення ' . $message. ' змінено!';
                break;
        }
        if(!empty($is_contact)) { 
            $user = User::where('phone', phoneFormatUnified($is_contact->phone_number))
                            ->first();
            $user->role = 'subscriber';
            $user->chat_id = $chat_id;
            $user->save();
        
            $reply['text'] = "Номер $is_contact->phone_number підписано на отримання оновлень!";
        }
        $this->options = $reply;
        return $reply;
    }

    private function setParams($params) {

    }

    private function arrayToText(array $array) {
        if(!is_array($array) || count($array) < 1) return 'Нічого цікавого за цей період';
        $text = '';
        foreach($array as $key => $value) {
            $lead = ($value['lead'] == 0) ? ' ⚠️' : ' ✅';
            $date = date('d-m-y H:i', strtotime($value['created_at']));
            $text .= "/id". $value['id'] . ' ' . $date . ' : +380' . $value['phone'] . $lead . PHP_EOL; 
        }
        $text .= 'Коли хтось записався на курс, натисни "id123" поруч з номером';
        return $text;
    }

    private function getKeyboard():array {
        return [ "keyboard" => [
                                    [
                                        [
                                            'text' =>  'Мій номер',
                                            'request_contact' => true,
                                        ]
                                    ],
                                    ["Cancel"]
                                ],
                                "resize_keyboard"   => true,
                                "one_time_keyboard" => true,
                ];
    }

}


// +"0": array:2 [▼
        //     "update_id" => 55224673
        //     "message" => array:5 [▶]
        // ]
    //     0 => array:2 [▼
    //     "update_id" => 55224673
    //     "message" => array:5 [▼
            //       "message_id" => 33
            //       "from" => array:5 [▼
                //         "id" => 5264505227
                //         "is_bot" => false
                //         "first_name" => "Anton"
                //         "username" => "anton_lesyk"
                //         "language_code" => "uk"
            //       ]             
            //       "chat" => array:4 [▼
                //         "id" => 5264505227
                //         "first_name" => "Anton"
                //         "username" => "anton_lesyk"
                //         "type" => "private"
            //       ]
            //       "date" => 1661430606
            //       "text" => "Hello"
            //     ]
    //   ]


// 3 => {#292 ▼
//     +"update_id": 55224676
//     +"message": {#323 ▼
//       +"message_id": 36
//       +"from": {#289 ▼
//         +"id": 5264505227
//         +"is_bot": false
//         +"first_name": "Anton"
//         +"username": "anton_lesyk"
//         +"language_code": "uk"
//       }
//       +"chat": {#290 ▼
//         +"id": 5264505227
//         +"first_name": "Anton"
//         +"username": "anton_lesyk"
//         +"type": "private"
//       }
//       +"date": 1661436994
//       +"reply_to_message": {#303 ▼
//         +"message_id": 31
//         +"from": {#302 ▼
//           +"id": 5438264491
//           +"is_bot": true
//           +"first_name": "TakeMyOrderPls"
//           +"username": "takemyorderpls_bot"
//         }
//         +"chat": {#301 ▼
//           +"id": 5264505227
//           +"first_name": "Anton"
//           +"username": "anton_lesyk"
//           +"type": "private"
//         }
//         +"date": 1657790451
//         +"text": "MENU"
//       }
//       +"contact": {#299 ▼
//         +"phone_number": "380982772434"
//         +"first_name": "Anton"
//         +"user_id": 5264505227
//       }
//     }
//   }