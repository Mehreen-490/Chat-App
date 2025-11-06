<?php

namespace App\Http\Middleware;

use App\Models\Message;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMessageSender
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authUser = $request->user();
        $authUserId = data_get($authUser, 'id');

        $messageId = data_get($request, 'message_id');
        $message = Message::find($messageId);

        if ($message->sender_id !== $authUserId) {
            throw new HttpResponseException(response()->json([
                'message' => 'You are not the sender of this message!'
            ], 400));
        }

        return $next($request);
    }
}
