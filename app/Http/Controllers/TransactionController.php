<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        // Memuat relasi items (many-to-many) alih-alih item
        $transactions = Transaction::with('items')->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('transactions.index')->with('error', 'Hanya staff yang bisa menambah transaksi.');
        }
        $items = Item::all();
        return view('transactions.create', compact('items'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('transactions.index')->with('error', 'Hanya staff yang bisa menambah transaksi.');
        }
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:masuk,keluar',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $item = Item::find($validated['item_id']);
        if ($validated['type'] === 'keluar' && $item->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok tidak cukup!']);
        }

        // Generate nomor invoice otomatis
        $today = now()->format('Ymd');
        $count = Transaction::whereDate('created_at', now()->toDateString())->count() + 1;
        $invoiceNumber = "INV-{$today}-" . str_pad($count, 2, '0', STR_PAD_LEFT);

        // Simpan transaksi
        $transaction = Transaction::create([
            'invoice_number' => $invoiceNumber,
            'user_id' => Auth::id(),
            'transaction_date' => $validated['transaction_date'],
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'description' => $validated['description'],
            'total_amount' => ($item->price ?? 0) * $validated['quantity'],
        ]);

        // Simpan ke tabel pivot transaction_item
        $transaction->items()->attach($validated['item_id'], [
            'quantity' => $validated['quantity'],
            'price' => $item->price ?? 0,
        ]);

        // Update stok
        if ($validated['type'] === 'masuk') {
            $item->stock += $validated['quantity'];
        } else {
            $item->stock -= $validated['quantity'];
        }
        $item->save();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('transactions.index')->with('error', 'Hanya staff yang bisa mengedit transaksi.');
        }
        $transaction = Transaction::with('items')->findOrFail($id);
        $items = Item::all();
        return view('transactions.edit', compact('transaction', 'items'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('transactions.index')->with('error', 'Hanya staff yang bisa mengedit transaksi.');
        }
        $transaction = Transaction::findOrFail($id);
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:masuk,keluar',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        // Ambil item lama dari relasi pivot
        $oldItem = $transaction->items()->first();
        $newItem = Item::find($validated['item_id']);

        // Revert stok lama
        if ($oldItem) {
            if ($transaction->type === 'masuk') {
                $oldItem->stock -= $transaction->quantity;
            } else {
                $oldItem->stock += $transaction->quantity;
            }
            $oldItem->save();
        }

        // Update stok baru
        if ($validated['type'] === 'keluar' && $newItem->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok tidak cukup!']);
        }
        if ($validated['type'] === 'masuk') {
            $newItem->stock += $validated['quantity'];
        } else {
            $newItem->stock -= $validated['quantity'];
        }
        $newItem->save();

        // Update transaksi
        $transaction->update([
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'description' => $validated['description'],
            'transaction_date' => $validated['transaction_date'],
            'user_id' => Auth::id(),
            'total_amount' => ($newItem->price ?? 0) * $validated['quantity'],
        ]);

        // Update pivot table
        $transaction->items()->sync([$validated['item_id'] => [
            'quantity' => $validated['quantity'],
            'price' => $newItem->price ?? 0,
        ]]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('transactions.index')->with('error', 'Hanya staff yang bisa menghapus transaksi.');
        }
        $transaction = Transaction::findOrFail($id);
        $item = $transaction->items()->first();
        if ($item) {
            if ($transaction->type === 'masuk') {
                $item->stock -= $transaction->quantity;
            } else {
                $item->stock += $transaction->quantity;
            }
            $item->save();
        }

        // Hapus dari pivot table
        $transaction->items()->detach();

        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
