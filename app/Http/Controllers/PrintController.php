<?php

namespace App\Http\Controllers;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use App\Http\Requests\PrintReq;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
    public function action(PrintReq $request)
    {
        foreach ($request->data as $key => $item) {
            try {
                $connector = new NetworkPrintConnector($item['ip'], 9100);
                $printer = new Printer($connector);
            } catch (\Exception $e) {
                $ips = [];
                $kitchens = [];
                for ($i = $key; $i < count($request->data); $i++) {
                    $ips[] = $request->data[$i]['ip'];
                    $kitchens[] = $request->data[$i]['name'];
                }
                return response()->json([
                    'message' => "Printer dengan ip [" . implode(',', $ips) . "] di [" . implode(',', $kitchens) . "] tidak ditemukan"
                ], 500);
            }

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->text("CHECKER\n");
            $printer->text($item['name'] . "\n\n\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->text("No Transaksi  : " . $item['code'] . "\n");
            $printer->text("Waktu         : " . $item['date'] . "\n");
            $printer->text("Nama Pemesan  : " . $item['customer'] . "\n");
            if(count($item['tables'] ?? [])===0){
                $printer->text("Nomor Meja    : "." Take Away ". "\n");
            }else{
                $printer->text("Nomor Meja    : " . implode(',', $item['tables']) . "\n");
            }

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("============================================\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            foreach ($item['products'] as $product) {
                $printer->text($product['quantity'] . "x " . $product['name'] . "\n");
                $printer->text("=> Note : " . $product['note'] ?? '');
                $printer->text("\n");
            }

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("============================================\n");

            $printer->text($item['date'] . "\n");
            $printer->text("= Waroeng Aceh Garuda =\n");

            $printer->feed(2);

            if ($item['cut']) {
                $printer->cut();
            }
            $printer->close();
        }

        return response()->json();
    }

    // public function oldaction(PrintReq $request)
    // {
    //     $data = $request->data;
    //     $id = uuid_create();

    //     foreach ($data as $item) {
    //         Storage::disk('public')->put("/doc/$id.png", base64_decode());

    //         try {
    //             $img = EscposImage::load("/doc/$id.png", true);
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'code' => 001,
    //                 'message' => "File tidak ditemukan"
    //             ], 500);
    //         }

    //         try {
    //             $connector = new NetworkPrintConnector($item['ip'], 9100);
    //             $printer = new Printer($connector);
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'code' => 002,
    //                 'message' => "Printer dengan ip " . $item['ip'] . " di " . $item['name'] . " tidak ditemukan"
    //             ], 500);
    //         }

    //         $printer->bitImage($img);
    //         $printer->feed();
    //         if ($item['cut'] === true) {
    //             $printer->cut();
    //         }
    //         $printer->close();
    //     }
    //     Storage::disk('public')->delete("doc/$id.png");

    //     return response()->json([
    //         'code' => 200,
    //         'message' => 'Print sukses'
    //     ], 200);
    // }
}
