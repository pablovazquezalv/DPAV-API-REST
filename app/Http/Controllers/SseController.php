<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\RazaCreada;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\StreamedResponse;
class SseController extends Controller
{
    public function stream()
    {
        $response = new StreamedResponse(function () {
            while (true) {
                // Your server-side logic to get data
                $data = json_encode(['message' => 'This is a message']);

                echo "data: $data\n\n";

                // Flush the output buffer
                ob_flush();
                flush();

                // Delay for 1 second
                sleep(1);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
   }
}