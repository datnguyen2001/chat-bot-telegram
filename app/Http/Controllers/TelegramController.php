<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use App\Models\Question;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function inbound(Request $request)
    {
        $update = $this->telegram->getWebhookUpdates();

        if ($update->isType('message')) {
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $text = $message->getText();

            if ($text === '/start') {
                $this->sendDynamicKeyboard($chatId);
            } else {
                $question = Question::where('question', $text)->first();
                if ($question) {
                    $responseText = $this->cleanHTML($question->answer);
                } else {
                    $responseText = 'Unknown command, please use /start to see the menu.';
                }

                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendDynamicKeyboard($chatId)
    {
        $questions = Question::all();
        $keyboardButtons = [];

        foreach ($questions as $question) {
            $keyboardButtons[] = [
                'text' => $question->question,
            ];
        }

        $keyboard = Keyboard::make([
            'keyboard' => array_chunk($keyboardButtons, 2),
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Please choose button',
            'reply_markup' => $keyboard,
        ]);
    }

    private function cleanHTML($text) {
        // Strip unsupported tags
        $text = strip_tags($text, '<b><i><u><code><strong><em><a>');
        return $text;
    }
}
