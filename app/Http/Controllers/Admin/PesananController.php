<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Menampilkan daftar pesanan untuk admin
     */
    public function index()
    {
        $pesanan = Pesanan::with('table')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.pesanan.index', compact('pesanan'));
    }

    /**
     * Update status pesanan dan kirim invoice jika pembayaran dikonfirmasi
     */
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => 'required|in:waiting_payment,processing,completed'
        ]);

        $oldStatus = $pesanan->status;
        $newStatus = $request->status;

        $pesanan->update([
            'status' => $newStatus
        ]);

        $successMessage = 'Status pesanan berhasil diupdate!';

        // Jika status berubah dari waiting_payment ke processing, kirim invoice
        if ($oldStatus === 'waiting_payment' && $newStatus === 'processing') {
            $this->sendInvoiceToCustomer($pesanan);
            $successMessage = '✅ Pembayaran dikonfirmasi! Invoice telah dikirim ke WhatsApp customer.';
        } elseif ($newStatus === 'completed') {
            $successMessage = '✅ Pesanan ditandai selesai!';
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Kirim invoice WhatsApp ke customer setelah pembayaran dikonfirmasi
     */
    private function sendInvoiceToCustomer($order)
    {
        try {
            // Format list items
            $itemsList = '';
            foreach ($order->items as $index => $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $itemsList .= ($index + 1) . ". {$item['name']}\n";
                $itemsList .= "   Rp " . number_format($item['price'], 0, ',', '.') . " x {$item['quantity']} = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            }

            $paymentMethodText = $order->payment_method === 'qris' ? 'QRIS' : 'Cash';

            // Format invoice WhatsApp
            $message = "🧾 *INVOICE PESANAN*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "✅ Pembayaran telah dikonfirmasi!\n\n";
            $message .= "📋 Order ID: #" . $order->id . "\n";
            $message .= "🪑 Meja: *" . $order->table->nomer_meja . "*\n";
            
            if ($order->customer_name) {
                $message .= "👤 Nama: " . $order->customer_name . "\n";
            }
            
            $message .= "📅 Tanggal: " . $order->created_at->format('d M Y, H:i') . "\n";
            $message .= "💳 Pembayaran: *{$paymentMethodText}*\n";
            $message .= "⏰ Status: *Sedang Diproses*\n\n";
            
            $message .= "📝 *DETAIL PESANAN:*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= $itemsList;
            
            if ($order->notes) {
                $message .= "\n📌 Catatan: " . $order->notes . "\n";
            }
            
            $message .= "\n━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "💰 *TOTAL: Rp " . number_format($order->total, 0, ',', '.') . "*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "Pesanan Anda sedang diproses oleh dapur.\n";
            $message .= "Mohon menunggu, kami akan segera menghidangkan pesanan Anda.\n\n";
            $message .= "Terima kasih telah memesan! 🙏\n";
            $message .= "*Warung Desa*";

            // Format nomor dengan kode negara jika belum ada
            $phone = $order->customer_phone;
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            } elseif (substr($phone, 0, 2) !== '62') {
                $phone = '62' . $phone;
            }

            // Kirim ke customer
            $response = $this->whatsappService->sendMessageToCustomer($phone, $message);
            
            if ($response) {
                Log::info('Invoice sent to customer after payment confirmation. Order #' . $order->id);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send invoice after payment: ' . $e->getMessage());
        }
    }
}
