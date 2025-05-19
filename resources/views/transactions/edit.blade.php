@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-semibold mb-4">Edit Transaksi</h1>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('transactions.update', $transaction->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="customer_id" class="block text-gray-700 text-sm font-bold mb-2">Pelanggan</label>
                        <select name="customer_id" id="customer_id" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $transaction->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="product_id" class="block text-gray-700 text-sm font-bold mb-2">Produk</label>
                        <select name="product_id" id="product_id" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ old('product_id', $transaction->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (Rp {{ number_format($product->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Barang</label>
                        <input type="number" name="quantity" id="quantity" 
                               value="{{ old('quantity', $transaction->quantity) }}" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               min="1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Satuan</label>
                        <div class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                            Rp <span id="unit_price_display">0</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Total Harga</label>
                        <div class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                            Rp <span id="total_price_display">0</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="transaction_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Transaksi</label>
                        <input type="date" name="transaction_date" id="transaction_date" 
                               value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update
                        </button>
                        <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const unitPriceDisplay = document.getElementById('unit_price_display');
        const totalPriceDisplay = document.getElementById('total_price_display');

        function calculateTotal() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = selectedOption ? parseFloat(selectedOption.getAttribute('data-price')) : 0;
            const quantity = parseInt(quantityInput.value) || 0;
            
            unitPriceDisplay.textContent = price.toLocaleString('id-ID');
            totalPriceDisplay.textContent = (price * quantity).toLocaleString('id-ID');
        }

        productSelect.addEventListener('change', calculateTotal);
        quantityInput.addEventListener('input', calculateTotal);

        // Initialize on page load
        calculateTotal();
    });
</script>
@endsection