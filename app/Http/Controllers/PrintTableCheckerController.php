<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrintTableCheckerReq;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class PrintTableCheckerController extends Controller
{
    public function action(PrintTableCheckerReq $request)
    {
        [
            'ip' => $ip,
            'code' => $code,
            'customer' => $customer,
            'created_at' => $created_at,
            'products' => $products,
            'packets' => $packets,
            'tables' => $tables,
        ] = $request;

        try {
            $connector = new NetworkPrintConnector($ip, 9100);
            $printer = new Printer($connector);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Printer dengan ip " . $ip . " tidak ditemukan"
            ], 500);
        }

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("TABLE CHECKER\n");
        $printer->text("\n\n\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->text("No Transaksi  : " . $code . "\n");
        $printer->text("Waktu         : " . $created_at . "\n");
        $printer->text("Nama Pemesan  : " . $customer . "\n");
        if (count($tables ?? []) === 0) {
            $printer->text("Nomor Meja    : " . " Take Away " . "\n");
        } else {
            $printer->text("Nomor Meja    : " . implode(',', $tables) . "\n");
        }

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("============================================\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        foreach ($products as $product) {
            $printer->text($product['quantity'] . "x " . $product['name'] . "\n");
            $printer->text("=> Note : " . $product['note'] ?? '');
            $printer->text("\n");
        }

        foreach ($packets as $packet) {
            $printer->text($packet['quantity'] . "x " . $packet['name'] . "\n");
            $printer->text("=> Note : " . $packet['note'] ?? '');
            $printer->text("\n");
        }

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("============================================\n");

        $printer->text($created_at . "\n");
        $printer->text("= Waroeng Aceh Garuda =\n");

        $printer->feed(2);

        $printer->cut();
        $printer->close();

        return response()->json();
    }

}
