<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use App\TelegramBot\TelegramBot;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendTelegramNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const MESSAGE = 'Хтось цікавиться курсами! Зателефонуй якомога скоріше: +380';
    
    protected $to;

    protected $what;

    protected $order_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $what, $order_id)
    {
        $this->to = $to;
        $this->what = $what;
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $telegram = new TelegramBot('SendMessage', $this->to, 
                                    self::MESSAGE . phoneFormatUnified($this->what));
        // $order = (new Order)::find($this->order_id);
        // info($order);
        // $order->sent = true;    
        // $order->save();
    }
}
