<?php

namespace App\Http\Controllers;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PrintReq;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
    public function action(PrintReq $request)
    {
        $data = $request->data;

        $id = uuid_create();

        foreach ($data as $item) {
            $image = str_replace('data:image/png;base64,', '', $item['file']);
            $image = str_replace(' ', '+', $image);
            Storage::disk('public')->put("/doc/$id.png", base64_decode($image));

            try {
                $img = EscposImage::load("/doc/$id.png", true);
            } catch (\Exception $e) {
                return response()->json([
                    'code' => 001,
                    'message' => "File tidak ditemukan"
                ], 500);
            }

            try {
                $connector = new NetworkPrintConnector($item['ip'], 9100);
                $printer = new Printer($connector);
            } catch (\Exception $e) {
                return response()->json([
                    'code' => 002,
                    'message' => "Printer dengan ip " . $item['ip'] . " di " . $item['kitchen'] . " tidak ditemukan"
                ], 500);
            }

            $printer->bitImage($img);
            $printer->feed();
            if ($item['cut'] === true) {
                $printer->cut();
            }
            $printer->close();
        }
        Storage::disk('public')->delete("doc/$id.png");

        return response()->json([
            'code' => 200,
            'message' => 'Print sukses'
        ], 200);
    }
}
