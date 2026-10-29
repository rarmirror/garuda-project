<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>E-Voucher Hotel {{ $booking->code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #1f2937; background: #fff; }

        .header { background: #1e3a8a; color: white; padding: 24px 30px; }
        .header h1 { font-size: 22px; font-weight: bold; }
        .header p { font-size: 12px; opacity: 0.8; margin-top: 4px; }

        .voucher-code { background: #eff6ff; border: 2px dashed #3b82f6; border-radius: 8px;
                        padding: 14px 20px; margin: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
        .voucher-code .code { font-size: 20px; font-weight: bold; color: #1d4ed8; letter-spacing: 2px; }
        .voucher-code .status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }
        .status-failed { background: #fee2e2; color: #b91c1c; }

        .section { margin: 0 30px 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #6b7280; text-transform: uppercase;
                         letter-spacing: 1px; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .field label { font-size: 11px; color: #9ca3af; margin-bottom: 2px; display: block; }
        .field span { font-weight: bold; font-size: 13px; }

        .hotel-box { background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px;
                     padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; }
        .hotel-name { font-size: 18px; font-weight: bold; color: #166534; }
        .hotel-room { font-size: 13px; color: #4b5563; margin-top: 3px; }
        .nights-badge { background: #1d4ed8; color: white; padding: 6px 14px; border-radius: 6px;
                        font-size: 13px; font-weight: bold; text-align: center; }

        .checkin-row { display: flex; gap: 16px; margin-top: 12px; }
        .checkin-box { flex: 1; background: #f8fafc; border-radius: 8px; padding: 10px 14px; text-align: center; }
        .checkin-label { font-size: 10px; color: #9ca3af; margin-bottom: 2px; }
        .checkin-date { font-size: 15px; font-weight: bold; color: #1f2937; }
        .checkin-arrow { display: flex; align-items: center; font-size: 22px; color: #3b82f6; }

        .guest-row { display: flex; justify-content: space-between; align-items: center;
                     padding: 8px 12px; border-radius: 6px; margin-bottom: 6px; }
        .guest-row:nth-child(odd) { background: #f1f5f9; }

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
        <h1>🏨 GARUDA — E-VOUCHER HOTEL</h1>
        <p>Booking Hotel Online · garuda.id</p>
    </div>

    {{-- KODE BOOKING --}}
    <div class="voucher-code">
        <div>
            <div style="font-size:11px; color:#6b7280; margin-bottom:4px;">KODE BOOKING HOTEL</div>
            <div class="code">{{ $booking->code }}</div>
        </div>
        @php
            $statusClass = match($booking->payment_status) {
                'paid'    => 'status-paid',
                'pending' => 'status-pending',
                default   => 'status-failed',
            };
            $statusLabel = match($booking->payment_status) {
                'paid'    => 'LUNAS',
                'pending' => 'PENDING',
                default   => 'DIBATALKAN',
            };
        @endphp
        <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
    </div>

    {{-- INFO HOTEL --}}
    <div class="section">
        <div class="section-title">Informasi Hotel</div>
        <div class="hotel-box">
            <div>
                <div class="hotel-name">{{ $booking->hotel->name }}</div>
                <div class="hotel-room">Kamar: {{ $booking->room->name }}</div>
                @if ($booking->hotel->address ?? false)
                    <div style="font-size:11px; color:#6b7280; margin-top:2px;">{{ $booking->hotel->address }}</div>
                @endif
            </div>
            <div class="nights-badge">
                {{ $booking->number_of_nights }}<br>
                <span style="font-size:10px; font-weight:normal;">malam</span>
            </div>
        </div>

        <div class="checkin-row">
            <div class="checkin-box">
                <div class="checkin-label">CHECK IN</div>
                <div class="checkin-date">{{ $booking->check_in->format('d M Y') }}</div>
            </div>
            <div class="checkin-arrow">→</div>
            <div class="checkin-box">
                <div class="checkin-label">CHECK OUT</div>
                <div class="checkin-date">{{ $booking->check_out->format('d M Y') }}</div>
            </div>
        </div>
    </div>

    {{-- PEMESAN --}}
    <div class="section">
        <div class="section-title">Informasi Pemesan</div>
        <div class="grid-2">
            <div class="field"><label>Nama</label><span>{{ $booking->name }}</span></div>
            <div class="field"><label>Email</label><span>{{ $booking->email }}</span></div>
            <div class="field"><label>Telepon</label><span>{{ $booking->phone }}</span></div>
            <div class="field"><label>Jumlah Tamu</label><span>{{ $booking->number_of_guests }} orang</span></div>
        </div>
    </div>

    {{-- DAFTAR TAMU --}}
    @if ($booking->guests->count() > 0)
        <div class="section">
            <div class="section-title">Daftar Tamu</div>
            @foreach ($booking->guests as $i => $guest)
                <div class="guest-row">
                    <div>
                        <strong>{{ $i + 1 }}. {{ $guest->name }}</strong>
                        <span style="color:#9ca3af; font-size:11px; margin-left:8px;">
                            {{ \Carbon\Carbon::parse($guest->date_of_birth)->format('d M Y') }}
                            · {{ $guest->nationality }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- HARGA --}}
    <div class="section">
        <div class="section-title">Rincian Pembayaran</div>
        <div class="price-row">
            <span>Subtotal ({{ $booking->number_of_nights }} malam × Rp {{ number_format($booking->subtotal / $booking->number_of_nights, 0, ',', '.') }})</span>
            <span>Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span>
        </div>
        @if ($booking->promo)
            <div class="price-row" style="color:#15803d">
                <span>Diskon ({{ $booking->promo->code }})</span>
                <span>- Rp {{ number_format($booking->subtotal - $booking->grandtotal, 0, ',', '.') }}</span>
            </div>
        @endif
        <div class="price-total">
            <span>GRAND TOTAL</span>
            <span>Rp {{ number_format($booking->grandtotal, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <p>Dokumen ini merupakan e-voucher resmi dari <strong>Garuda Booking</strong>.</p>
        <p style="margin-top:4px;">Tunjukkan voucher ini saat check-in di hotel. Terima kasih telah menggunakan layanan kami.</p>
    </div>

</body>
</html>
