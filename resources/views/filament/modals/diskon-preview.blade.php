@php
    if (!$produk_id || !$nilai) {
        $error = true;
        $message = 'Data tidak lengkap';
    } else {
        $produk = \App\Models\Produk::find($produk_id);
        if (!$produk) {
            $error = true;
            $message = 'Produk tidak ditemukan';
        } else {
            $error = false;
            $hargaAsli = (float) $produk->harga;
            $nilaiDiskon = (float) $nilai;
            
            if ($tipe === 'fixed') {
                $potongan = $nilaiDiskon;
                $hargaDiskon = max(0, $hargaAsli - $nilaiDiskon);
            } else {
                $potongan = ($hargaAsli * $nilaiDiskon) / 100;
                $hargaDiskon = $hargaAsli - $potongan;
            }
            
            $persentasePotongan = $hargaAsli > 0 ? ($potongan / $hargaAsli) * 100 : 0;
        }
    }
@endphp

<div class="space-y-4">
    @if($error)
        <div class="text-center py-8">
            <div class="text-4xl mb-2">⚠️</div>
            <p class="text-gray-500 dark:text-gray-400">{{ $message }}</p>
        </div>
    @else
        {{-- Header Info --}}
        <div class="rounded-lg bg-primary-50 dark:bg-primary-900/20 p-4 border border-primary-200 dark:border-primary-700">
            <div class="flex items-start gap-3">
                <div class="text-2xl">🏷️</div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-primary-900 dark:text-primary-100">
                        {{ $label ?? 'Preview Diskon' }}
                    </h3>
                    <p class="text-sm text-primary-700 dark:text-primary-300 mt-1">
                        Produk: <span class="font-medium">{{ $produk->nama }}</span>
                    </p>
                    <p class="text-sm text-primary-700 dark:text-primary-300">
                        Tipe: <span class="font-medium">{{ $tipe === 'persentase' ? 'Persentase' : 'Nominal' }}</span>
                        <span class="font-semibold">
                            ({{ $tipe === 'persentase' ? $nilaiDiskon . '%' : 'Rp ' . number_format($nilaiDiskon, 0, ',', '.') }})
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Price Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Harga Asli --}}
            <div class="rounded-lg bg-gray-50 dark:bg-gray-800/50 p-4 border border-gray-200 dark:border-gray-700">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    Harga Asli
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ number_format($hargaAsli, 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Rupiah</div>
            </div>

            {{-- Potongan --}}
            <div class="rounded-lg bg-danger-50 dark:bg-danger-900/20 p-4 border border-danger-200 dark:border-danger-700">
                <div class="text-xs font-medium text-danger-600 dark:text-danger-400 uppercase tracking-wider mb-2">
                    Potongan
                </div>
                <div class="text-2xl font-bold text-danger-600 dark:text-danger-400">
                    -{{ number_format($potongan, 0, ',', '.') }}
                </div>
                <div class="text-xs text-danger-500 dark:text-danger-400 mt-1">
                    {{ number_format($persentasePotongan, 1) }}%
                </div>
            </div>

            {{-- Harga Diskon --}}
            <div class="rounded-lg bg-success-50 dark:bg-success-900/20 p-4 border-2 border-success-500 dark:border-success-600">
                <div class="text-xs font-medium text-success-600 dark:text-success-400 uppercase tracking-wider mb-2">
                    Harga Diskon
                </div>
                <div class="text-2xl font-bold text-success-600 dark:text-success-400">
                    {{ number_format($hargaDiskon, 0, ',', '.') }}
                </div>
                <div class="text-xs text-success-500 dark:text-success-400 mt-1">Rupiah</div>
            </div>
        </div>

        {{-- Calculation Details --}}
        <div class="rounded-lg bg-gray-50 dark:bg-gray-800/50 p-4 border border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                📊 Detail Perhitungan
            </h4>
            <div class="space-y-2">
                @if($tipe === 'persentase')
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Harga Asli:</span>
                        <span class="font-mono text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Diskon {{ $nilaiDiskon }}%:</span>
                        <span class="font-mono text-gray-900 dark:text-gray-100">
                            {{ number_format($hargaAsli, 0, ',', '.') }} × {{ $nilaiDiskon }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Hasil Potongan:</span>
                        <span class="font-mono text-danger-600 dark:text-danger-400">
                            -Rp {{ number_format($potongan, 0, ',', '.') }}
                        </span>
                    </div>
                @else
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Harga Asli:</span>
                        <span class="font-mono text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Potongan Nominal:</span>
                        <span class="font-mono text-danger-600 dark:text-danger-400">
                            -Rp {{ number_format($potongan, 0, ',', '.') }}
                        </span>
                    </div>
                @endif
                
                <div class="border-t border-gray-300 dark:border-gray-600 my-2"></div>
                
                <div class="flex justify-between items-center pt-1">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Harga Final:</span>
                    <span class="font-mono text-lg font-bold text-success-600 dark:text-success-400">
                        Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Customer Saving --}}
        <div class="rounded-lg bg-warning-50 dark:bg-warning-900/20 p-3 border border-warning-200 dark:border-warning-700 text-center">
            <p class="text-sm text-warning-800 dark:text-warning-200">
                💰 Pelanggan hemat <span class="font-bold">Rp {{ number_format($potongan, 0, ',', '.') }}</span> 
                ({{ number_format($persentasePotongan, 1) }}%)!
            </p>
        </div>
    @endif
</div>
