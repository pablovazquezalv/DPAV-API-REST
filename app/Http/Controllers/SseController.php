<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\RazaCreada;
use Illuminate\Support\Facades\Redis;

class SseController extends Controller
{
    public function stream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('Access-Control-Allow-Origin: http://localhost:4200');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        while (true) {
            if (connection_aborted()) {
                break;
            }

            // Lee eventos desde Redis
            $raza = Redis::lpop('RazaCreada');

            if ($raza) {
                echo "data: " . $raza . "\n\n";
                ob_flush();
                flush();
            }

            sleep(1); // Ajusta el tiempo de espera según tus necesidades
        }
    }
}