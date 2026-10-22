<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>E-Tiket {{ $transaction->code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #1f2937; background: #fff; }

        .header { background: #1e3a8a; color: white; padding: 24px 30px; }
        .header h1 { font-size: 22px; font-weight: bold; }
        .header p { font-size: 12px; opacity: 0.8; margin-top: 4px; }

        .ticket-code { background: #eff6ff; border: 2px dashed #3b82f6; border-radius: 8px;
                       padding: 14px 20px; margin: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
        .ticket-code .code { font-size: 20px; font-weight: bold; color: #1d4ed8; letter-spacing: 2px; }
        .ticket-code .status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }
        .status-failed { background: #fee2e2; color: #b91c1c; }

        .section { margin: 0 30px 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #6b7280; text-transform: uppercase;
                         letter-spacing: 1px; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .field label { font-size: 11px; color: #9ca3af; margin-bottom: 2px; display: block; }
        .field span { font-weight: bold; font-size: 13px; }

        .route-box { background: #f8fafc; border-radius: 8px; padding: 14px 18px;
                     display: flex; align-items: center; justify-content: space-between; }
        .route-city { font-size: 20px; font-weight: bold; color: #1d4ed8; }
        .route-label { font-size: 10px; color: #9ca3af; margin-top: 2px; }
        .route-arrow { font-size: 22px; color: #3b82f6; }

        .passenger-row { display: flex; justify-content: space-between; align-items: center;
                         padding: 8px 12px; border-radius: 6px; margin-bottom: 6px; }
        .passenger-row:nth-child(odd) { background: #f1f5f9; }
        .seat-badge { background: #dbeafe; color: #1d4ed8; padding: 3px 10px; border-radius: 4px;
                      font-size: 12px; font-weight: bold; }

        .price-row { display: flex; justify-content: space-between; padding: 6px 0;
                     border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        .price-total { display: flex; justify-content: space-between; padding: 10px 0 0;
                       font-weight: bold; font-size: 15px; color: #1d4ed8; }

        .footer { background: #f8fafc; border-top: 2px solid #e5e7eb; padding: 14px 30px;
                  text-align: center; color: #9ca3af; font-size: 11px; margin-top: 20px; }
        .footer strong { color: #1d4ed8; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>✈ GARUDA — E-TIKET</h1>
        <p>Booking Tiket Pesawat Online · garuda.id</p>
    </div>

    {{-- KODE BOOKING --}}
    <div class="ticket-code">
        <div>
            <div style="font-size:11px; color:#6b7280; margin-bottom:4px;">KODE BOOKING</div>
            <div class="code">{{ $transaction->code }}</div>
        </div>
        @php
            $statusClass = match($transaction->payment_status) {
                'paid'    => 'status-paid',
                'pending' => 'status-pending',
                default   => 'status-failed',
            };
            $statusLabel = match($transaction->payment_status) {
                'paid'    => 'LUNAS',
                'pending' => 'PENDING',
                default   => 'DIBATALKAN',
            };
        @endphp
        <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
    </div>

    {{-- RUTE --}}
    <div class="section">
        <div class="section-title">Rute Penerbangan</div>
        <div class="route-box">
            <div style="text-align:center">
                <div class="route-city">{{ $transaction->flight->segments->first()?->airport?->iata_code ?? '-' }}</div>
                <div class="route-label">{{ $transaction->flight->segments->first()?->airport?->city ?? '-' }}</div>
                <div style="font-size:11px; color:#6b7280; margin-top:4px;">
                    {{ $transaction->flight->segments->first()?->time
                        ? \Carbon\Carbon::parse($transaction->flight->segments->first()->time)->format('d M Y, H:i')
                        : '-' }}
                </div>
            </div>
            <div class="route-arrow">→</div>
            <div style="text-align:center">
                <div class="route-city">{{ $transaction->flight->segments->last()?->airport?->iata_code ?? '-' }}</div>
                <div class="route-label">{{ $transaction->flight->segments->last()?->airport?->city ?? '-' }}</div>
                <div style="font-size:11px; color:#6b7280; margin-top:4px;">
                    {{ $transaction->flight->segments->last()?->time
                        ? \Carbon\Carbon::parse($transaction->flight->segments->last()->time)->format('d M Y, H:i')
                        : '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- INFO PENERBANGAN --}}
    <div class="section">
        <div class="section-title">Detail Penerbangan</div>
        <div class="grid-2">
            <div class="field"><label>Maskapai</label><span>{{ $transaction->flight->airline->name ?? '-' }}</span></div>
            <div class="field"><label>No. Penerbangan</label><span>{{ $transaction->flight->flight_number ?? '-' }}</span></div>
            <div class="field"><label>Kelas</label><span style="text-transform:capitalize">{{ $transaction->flightClass->class_type ?? '-' }}</span></div>
            <div class="field"><label>Jumlah Penumpang</label><span>{{ $transaction->number_of_passengers }} orang</span></div>
        </div>
    </div>

    {{-- PEMESAN --}}
    <div class="section">
        <div class="section-title">Informasi Pemesan</div>
        <div class="grid-2">
            <div class="field"><label>Nama</label><span>{{ $transaction->name }}</span></div>
            <div class="field"><label>Email</label><span>{{ $transaction->email }}</span></div>
            <div class="field"><label>Telepon</label><span>{{ $transaction->phone }}</span></div>
        </div>
    </div>

    {{-- PENUMPANG --}}
    <div class="section">
        <div class="section-title">Daftar Penumpang</div>
        @foreach ($transaction->passengers as $i => $passenger)
            <div class="passenger-row">
                <div>
                    <strong>{{ $i + 1 }}. {{ $passenger->name }}</strong>
                    <span style="color:#9ca3af; font-size:11px; margin-left:8px;">
                        {{ \Carbon\Carbon::parse($passenger->date_of_birth)->format('d M Y') }}
                        · {{ $passenger->nationality }}
                    </span>
                </div>
                <span class="seat-badge">Kursi {{ $passenger->flightSeat?->name ?? '-' }}</span>
            </div>
        @endforeach
    </div>

    {{-- HARGA --}}
    <div class="section">
        <div class="section-title">Rincian Pembayaran</div>
        <div class="price-row">
            <span>Subtotal ({{ $transaction->number_of_passengers }} penumpang)</span>
            <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>
        @if ($transaction->promo)
            <div class="price-row" style="color:#15803d">
                <span>Diskon ({{ $transaction->promo->code }})</span>
                <span>- Rp {{ number_format($transaction->subtotal - $transaction->grandtotal, 0, ',', '.') }}</span>
            </div>
        @endif
        <div class="price-total">
            <span>GRAND TOTAL</span>
            <span>Rp {{ number_format($transaction->grandtotal, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <p>Dokumen ini merupakan e-tiket resmi dari <strong>Garuda Booking</strong>.</p>
        <p style="margin-top:4px;">Harap tunjukkan tiket ini saat check-in di bandara. Terima kasih telah menggunakan layanan kami.</p>
    </div>

</body>
</html>