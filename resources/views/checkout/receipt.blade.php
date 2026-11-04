<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $order->order_number }} - Stride Footwear</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier Prime', monospace, sans-serif;
            background-color: #f1f3f7;
            color: #141b2b;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .action-bar {
            width: 100%;
            max-width: 480px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .btn {
            background-color: #004725;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-outline {
            background-color: #ffffff;
            color: #141b2b;
            border: 1px solid #c0c9bf;
        }
        .receipt-card {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            padding: 28px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e1e8fd;
            position: relative;
        }
        .header {
            text-align: center;
            padding-bottom: 16px;
            border-bottom: 2px dashed #707971;
            margin-bottom: 16px;
        }
        .brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #004725;
        }
        .store-sub {
            font-size: 11px;
            color: #404941;
            margin-top: 4px;
            line-height: 1.4;
        }
        .badge-lunas {
            display: inline-block;
            background-color: #cbead6;
            color: #004725;
            padding: 3px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 8px;
            letter-spacing: 0.5px;
        }
        .meta-table {
            width: 100%;
            font-size: 12px;
            margin-bottom: 16px;
            border-bottom: 1px dashed #c0c9bf;
            padding-bottom: 12px;
        }
        .meta-table td {
            padding: 3px 0;
        }
        .meta-table td.label {
            color: #404941;
        }
        .meta-table td.value {
            text-align: right;
            font-weight: 700;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 16px;
        }
        .items-table th {
            text-align: left;
            padding-bottom: 8px;
            border-bottom: 1px solid #141b2b;
            font-size: 11px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 8px 0;
            border-bottom: 1px dotted #e1e8fd;
            vertical-align: top;
        }
        .items-table .item-name {
            font-weight: 700;
        }
        .items-table .item-variant {
            font-size: 10px;
            color: #404941;
        }
        .totals-table {
            width: 100%;
            font-size: 12px;
            border-top: 1px dashed #707971;
            padding-top: 12px;
            margin-bottom: 16px;
        }
        .totals-table td {
            padding: 3px 0;
        }
        .totals-table td.right {
            text-align: right;
            font-weight: 600;
        }
        .totals-table tr.grand-total td {
            font-size: 15px;
            font-weight: 700;
            color: #004725;
            padding-top: 8px;
            border-top: 1px solid #141b2b;
        }
        .barcode {
            text-align: center;
            font-size: 15px;
            letter-spacing: 3px;
            margin: 16px 0 6px 0;
            font-weight: bold;
        }
        .footer-note {
            text-align: center;
            font-size: 10px;
            color: #707971;
            line-height: 1.5;
            border-top: 1px dashed #c0c9bf;
            padding-top: 12px;
            margin-top: 12px;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .receipt-card {
                border: none;
                box-shadow: none;
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('checkout.success', $order->order_number) }}" class="btn btn-outline">
            &larr; Kembali ke Toko
        </a>
        <button onclick="window.print()" class="btn">
            Cetak Struk Sekarang
        </button>
    </div>

    <div class="receipt-card">
        <div class="header">
            <div class="brand">STRIDE FOOTWEAR</div>
            <div class="store-sub">
                PT Stride Footwear Indonesia<br>
                Grand Indonesia Level 3, Jl. M.H. Thamrin No. 1, Jakarta<br>
                WA: 0812-3456-7890 &bull; www.stridefootwear.com
            </div>
            <div class="badge-lunas">
                *** LUNAS / PAID ***
            </div>
        </div>

        <table class="meta-table">
            <tr>
                <td class="label">No. Pesanan</td>
                <td class="value">#{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td class="label">No. Transaksi</td>
                <td class="value">{{ $order->payment?->transaction_id ?? ('TXN-' . strtoupper(substr(md5($order->id), 0, 8))) }}</td>
            </tr>
            <tr>
                <td class="label">Waktu Transaksi</td>
                <td class="value">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="label">Nama Pembeli</td>
                <td class="value">{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <td class="label">Metode Bayar</td>
                <td class="value">{{ strtoupper($order->payment?->payment_method ?? 'ONLINE') }}</td>
            </tr>
            <tr>
                <td class="label">Metode Kirim</td>
                <td class="value">{{ strtoupper($order->shipping_method ?? 'STANDARD') }}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    @php
                        $var = is_array($item->variant_details) ? $item->variant_details : json_decode($item->variant_details, true);
                    @endphp
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product_name }}</div>
                            <div class="item-variant">Size: {{ $var['size'] ?? ($item->variant?->size ?? 'Std') }} {{ !empty($var['color']) ? '| ' . $var['color'] : '' }}</div>
                        </td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td class="right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($order->discount_amount > 0)
                <tr>
                    <td>Diskon Kupon ({{ $order->coupon_code ?? 'Promo' }})</td>
                    <td class="right">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td>Ongkos Kirim</td>
                <td class="right">{{ $order->shipping_fee == 0 ? 'GRATIS' : 'Rp ' . number_format($order->shipping_fee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak PPN (11%)</td>
                <td class="right">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td>TOTAL BAYAR</td>
                <td class="right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="barcode">
            ||||| | ||| |||| || ||||| |||||
        </div>
        <div style="text-align: center; font-size: 10px; color: #707971;">
            AUTH: {{ $order->order_number }}
        </div>

        <div class="footer-note">
            Terima kasih telah berbelanja di Stride Footwear.<br>
            Simpan struk ini sebagai bukti pembayaran sah dan kartu garansi 30 hari penukaran ukuran.<br>
            www.stridefootwear.com
        </div>
    </div>

</body>
</html>
