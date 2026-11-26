<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit.prevent class="flex flex-wrap items-end gap-4 rounded-xl bg-white p-4 shadow-sm dark:bg-gray-900">
            <div>
                <label class="text-sm font-medium">Dari tanggal</label>
                <input type="date" wire:model.live="from" class="fi-input mt-1 rounded-lg border-gray-300 dark:bg-gray-800">
            </div>
            <div>
                <label class="text-sm font-medium">Sampai tanggal</label>
                <input type="date" wire:model.live="to" class="fi-input mt-1 rounded-lg border-gray-300 dark:bg-gray-800">
            </div>
            <div>
                <label class="text-sm font-medium">Status untuk export</label>
                <select wire:model.live="status" class="fi-input mt-1 rounded-lg border-gray-300 dark:bg-gray-800">
                    <option value="">Semua status</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <button type="button" wire:click="$set('from', null); $set('to', null); $set('status', null)" class="fi-btn fi-btn-color-gray">Reset filter</button>
        </form>

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ([['Total Transaksi Pesawat', $flightCount], ['Total Booking Hotel', $hotelCount], ['Pembayaran Paid', $paidCount], ['Pembayaran Pending', $pendingCount], ['Pembayaran Failed', $failedCount]] as [$label, $value])
                <div class="rounded-xl bg-white p-5 shadow-sm dark:bg-gray-900">
                    <div class="text-sm text-gray-500">{{ $label }}</div>
                    <div class="mt-2 text-2xl font-bold">{{ number_format($value, 0, ',', '.') }}</div>
                </div>
            @endforeach
            <div class="rounded-xl bg-white p-5 shadow-sm dark:bg-gray-900">
                <div class="text-sm text-gray-500">Total nilai transaksi berstatus Paid</div>
                <div class="mt-2 text-2xl font-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
            </div>
        </div>

        <section class="rounded-xl bg-white p-5 shadow-sm dark:bg-gray-900">
            <h2 class="text-base font-semibold">Download laporan (CSV untuk Microsoft Excel)</h2>
            <p class="mt-1 text-sm text-gray-500">Filter tanggal dan status di atas akan diterapkan pada file yang diunduh.</p>
            <div class="mt-4 flex flex-wrap gap-3">
                @foreach (['flights' => 'Detail Transaksi Pesawat', 'hotels' => 'Detail Booking Hotel', 'finance' => 'Rekap Keuangan', 'customers' => 'Rekap Pelanggan'] as $type => $label)
                    <a href="{{ $this->exportUrl($type) }}" class="fi-btn fi-btn-size-md fi-btn-color-primary">{{ $label }}</a>
                @endforeach
            </div>
        </section>
        <p class="text-xs text-gray-500">Catatan: aplikasi saat ini hanya menyimpan status paid, pending, dan failed. Data refund terpisah belum tersedia pada skema transaksi, sehingga refund tidak dihitung sebagai angka tersendiri. Nilai paid di laporan adalah nilai transaksi berstatus paid, bukan konfirmasi settlement bank.</p>
    </div>
</x-filament-panels::page>
