<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Services\TelegramBot;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    protected $telegramBot;

    public function __construct(TelegramBot $telegramBot)
    {
        $this->telegramBot = $telegramBot;
    }

    public function inbound(Request $request){
        \Log::info($request->all());

        // get telegram chat_id and reply to
        $chat_id            = $request->message['from']['id'];
        $reply_to_message   = $request->message['message_id'];
        $message_text       = $request->message['text'] ?? '';

        \Log::info("chat_id: {$chat_id}");
        \Log::info("reply_to_message: {$reply_to_message}");

        // If first time -> send first time message
        if(!cache()->has("chat_id_{$chat_id}")){

            $text = "Welcome to ImageDetectTextBOT 🤖 \r\n";
            $text.= "Please upload a IMAGE and enjoy the magic 🪄";

            cache()->put("chat_id_{$chat_id}",true,now()->addMinute(60));
        } else if ($question = Question::where('question', strtolower($message_text))->first()) {
            $text = $this->cleanHTML($question->answer);
        } else if(isset($request->message['photo'])){ // If chat is photo -> Extract text from photo
            // Get image_url...
            $image_url = app('telegram_bot')->getImageUrl($request->message['photo']);

            // Extract text from image
            $text = app('image_detect_text')->getTextFromImage($image_url);

        }else{        // Else -> Send default message

            $text = "ImageDetectTextBOT 🤖\r\nPlease upload an IMAGE!";
        }

        // telegram service -> sendMessage($text, $chat_id, $reply_to_message, 'HTML')
        $result = $this->telegramBot->sendMessage($text, $chat_id, $reply_to_message, 'HTML');

        return response()->json($result,200);
    }

    private function cleanHTML($text) {
        // Strip unsupported tags
        $text = strip_tags($text, '<b><i><u><code><strong><em><a>');
        return $text;
    }
}
