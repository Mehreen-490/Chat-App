<?php

namespace App\Http\Middleware;

use App\Models\Message;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $messageId = $request->message_id;

        $message = Message::find($messageId)->first();

        if (!$message) {
            return response()->json([
                'message' => 'Invalid Message Id given'
            ]);
        }
        $request->merge([
            'message' => $message
        ]);
        return $next($request);
    }
}
