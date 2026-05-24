<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = App\Models\Pesanan::with('customer')->orderBy('created_at', 'desc')->limit(5)->get();
foreach ($orders as $order) {
    $order->detailPesanan->sum(function($d) {
        return $d->subtotal;
    });
}
echo "OK\n";
