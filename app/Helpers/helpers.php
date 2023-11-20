<?php

use Carbon\Carbon;
use App\Models\Transaction\Stores;

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('getInvoiceNumber')) {
    function getInvoiceNumber($from)
    {
        $date = date("dmy");
        $latest = Stores::latest()->where('nota', 'like', '%'.$date.'%')->first();
        $prefix = "DC-";
        if ($from == "web") {
            $prefix = "DCW-";
        } else {
            $prefix = "DCC-";
        }
        if (! $latest) {
            return $prefix.$date.'-0001';
        }
        $expNum = explode('-', $latest->kode_nota);

        return $prefix. $date . sprintf('-%04d', $expNum[2]+1);
    }
}
