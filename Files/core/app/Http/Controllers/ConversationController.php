<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Events\Conversation as EventsConversation;
use App\Models\Conversation;
use App\Models\Order;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller {
    public function sendMessage(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'message'    => 'required',
            'attachment' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->all()]);
        }

        $user = auth()->user();

        if ($user) {
            $column      = "user_id";
            $columnValue = $user->id;
            $pusherEvent = "provider-message";
        } else {
            $column      = "provider_id";
            $columnValue = auth('provider')->id();
            $pusherEvent = "user-message";
        }

        $order = Order::where($column, $columnValue)->where('id', $id)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => "The order is not found"]);
        }
        $message              = new Conversation();
        $message->order_id    = $order->id;
        $message->user_id     = $order->user_id;
        $message->provider_id = $order->provider_id;
        $message->is_user     = $user ? Status::YES : Status::NO;
        $message->message     = $request->message;

        if ($request->hasFile('attachment')) {
            try {
                $message->attachment = fileUploader($request->attachment, getFilePath('conversation'));
                $attachment          = getImage(getFilePath('conversation') . '/' . $message->attachment);
            } catch (\Exception $exp) {
                return response()->json(['success' => false, 'message' => "Couldn\'t send your image"]);
            }
        } else {
            $attachment = null;
        }

        $message->save();
        $message->attachment_url = $attachment;

        try {
            initializePusher();
            event(new EventsConversation($message, $pusherEvent));
        } catch (\Exception $exp) {
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data'    => $message,
        ]);
    }
}
