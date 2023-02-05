<?php
namespace Simcify\Models;
use Simcify\Database;

class CheckoutModel
{
    static public function AdditionalPayment($user, $amount,$transaction,$card){
        BalanceModel::addHoldingBalance($user, $amount);
        $invoice_id=InvoiceModel::addInvoice($user, $amount, "Cash", "Paid", input("order_id"), $transaction, $card,0,0,0,0, $amount,"by_admin");

        BalanceModel::addBalanceHistory($user,-$amount, "Payment with Cash", "Additional Payment for exceeded checkout",$invoice_id,BalanceModel::None,0);
    }
}