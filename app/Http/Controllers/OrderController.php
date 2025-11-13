<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pesanan;
use App\Models\Table;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }
    /**
     * Display menu untuk customer dengan token meja
     */
    public function index(Request $request)
    {
        // Ambil token meja dari query parameter
        $token = $request->query('t');
        
        if (!$token) {
            return view('customer.menu', [
                'error' => 'Token meja tidak ditemukan. Silakan scan QR code meja Anda.',
                'items' => [],
                'table' => null
            ]);
        }

        // Validasi token meja
        $table = Table::where('token', $token)->first();
        
        if (!$table) {
            return view('customer.menu', [
                'error' => 'Token meja tidak valid.',
                'items' => [],
                'table' => null
            ]);
        }

        // Ambil semua menu
        $items = Item::all();

        return view('customer.menu', [
            'items' => $items,
            'table' => $table,
            'token' => $token,
            'error' => null
        ]);
    }

    /**
     * Simpan order dari localStorage
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'token' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.name' => 'required|string',
                'customer_name' => 'nullable|string|max:255',
                'customer_phone' => 'required|string|min:10|max:15',
                'payment_method' => 'required|in:cash,qris',
                'notes' => 'nullable|string',
            ]);

            // Validasi token meja
            $table = Table::where('token', $validated['token'])->first();
            
            if (!$table) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token meja tidak valid.'
                ], 400);
            }

            // Hitung total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Simpan order
            $order = Pesanan::create([
                'table_id' => $table->id,
                'token' => $validated['token'],
                'items' => $validated['items'],
                'total' => $total,
                'status' => 'waiting_payment',
                'payment_method' => $validated['payment_method'],
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Kirim notifikasi WhatsApp ke admin
            $this->sendWhatsAppNotification($order, $table);
            
            // JANGAN kirim invoice dulu, tunggu sampai admin konfirmasi pembayaran

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order' => $order
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan.'
            ], 500);
        }
    }

    /**
     * Lihat history pesanan berdasarkan token meja
     */
    public function history(Request $request)
    {
        $token = $request->query('t');
        
        if (!$token) {
            return view('customer.history', [
                'error' => 'Token meja tidak ditemukan.',
                'orders' => [],
                'table' => null
            ]);
        }

        // Validasi token
        $table = Table::where('token', $token)->first();
        
        if (!$table) {
            return view('customer.history', [
                'error' => 'Token meja tidak valid.',
                'orders' => [],
                'table' => null
            ]);
        }

        // Ambil pesanan berdasarkan meja
        $orders = Pesanan::where('table_id', $table->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.history', [
            'orders' => $orders,
            'table' => $table,
            'token' => $token,
            'error' => null
        ]);
    }

    /**
     * Kirim notifikasi WhatsApp ke admin
     */
    private function sendWhatsAppNotification($order, $table)
    {
        try {
            // Format list items
            $itemsList = '';
            foreach ($order->items as $index => $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $itemsList .= ($index + 1) . ". {$item['name']}\n";
                $itemsList .= "   Rp " . number_format($item['price'], 0, ',', '.') . " x {$item['quantity']} = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            }

            // Format pesan WhatsApp
            $message = "🔔 *PESANAN BARU!*\n\n";
            $message .= "📋 Order #" . $order->id . "\n";
            $message .= "🪑 Meja: *" . $table->nomer_meja . "*\n";
            
            if ($order->customer_name) {
                $message .= "👤 Nama: " . $order->customer_name . "\n";
            }
            
            $message .= "📅 Waktu: " . $order->created_at->format('d M Y, H:i') . "\n\n";
            
            $message .= "📝 *DETAIL PESANAN:*\n";
            $message .= $itemsList;
            
            if ($order->notes) {
                $message .= "\n📌 Catatan: " . $order->notes . "\n";
            }
            
            $message .= "\n💰 *TOTAL: Rp " . number_format($order->total, 0, ',', '.') . "*\n\n";
            $message .= "Silakan segera proses pesanan ini.\n";
            $message .= "Dashboard: " . url('/admin/pesanan');

            // Kirim ke admin
            $this->whatsappService->sendMessageToAdmin($message);
            
            Log::info('WhatsApp notification sent for Order #' . $order->id);
        } catch (\Exception $e) {
            // Log error tapi jangan gagalkan order
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }

    /**
     * Kirim invoice WhatsApp ke customer
     */
    private function sendInvoiceToCustomer($order, $table)
    {
        try {
            // Format list items
            $itemsList = '';
            foreach ($order->items as $index => $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $itemsList .= ($index + 1) . ". {$item['name']}\n";
                $itemsList .= "   Rp " . number_format($item['price'], 0, ',', '.') . " x {$item['quantity']} = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            }

            // Format invoice WhatsApp
            $message = "🧾 *INVOICE PESANAN*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "✅ Pesanan Anda berhasil dibuat!\n\n";
            $message .= "📋 Order ID: #" . $order->id . "\n";
            $message .= "🪑 Meja: *" . $table->nomer_meja . "*\n";
            
            if ($order->customer_name) {
                $message .= "👤 Nama: " . $order->customer_name . "\n";
            }
            
            $message .= "📅 Tanggal: " . $order->created_at->format('d M Y, H:i') . "\n";
            $message .= "⏰ Status: *Pending*\n\n";
            
            $message .= "📝 *DETAIL PESANAN:*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= $itemsList;
            
            if ($order->notes) {
                $message .= "\n📌 Catatan: " . $order->notes . "\n";
            }
            
            $message .= "\n━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "💰 *TOTAL: Rp " . number_format($order->total, 0, ',', '.') . "*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "Pesanan Anda sedang diproses.\n";
            $message .= "Mohon menunggu, kami akan segera menghidangkan pesanan Anda.\n\n";
            $message .= "Terima kasih telah memesan! 🙏\n";
            $message .= "*Warung Desa*";

            // Format nomor dengan kode negara jika belum ada
            $phone = $order->customer_phone;
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1); // Ubah 08xxx menjadi 628xxx
            } elseif (substr($phone, 0, 2) !== '62') {
                $phone = '62' . $phone;
            }

            // Kirim ke customer via WhatsappService
            $response = $this->whatsappService->sendMessageToCustomer($phone, $message);
            
            if ($response) {
                Log::info('Invoice sent to customer phone: ' . $phone . ' for Order #' . $order->id);
            } else {
                Log::warning('Failed to send invoice to customer phone: ' . $phone);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan gagalkan order
            Log::error('Failed to send invoice to customer: ' . $e->getMessage());
        }
    }
}
