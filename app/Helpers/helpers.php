<?php

use App\Models\Transaksi;

if (!function_exists('invoice')) {
    function invoice()
    {
        $lastInvoice = Transaksi::latest('id')->first();
        $invoiceNumber = !$lastInvoice ? 'INV-0001' : 'INV-' . str_pad((int)substr($lastInvoice->nomor_transaksi, 4) + 1, 4, '0', STR_PAD_LEFT);
        return $invoiceNumber;
    }
}
