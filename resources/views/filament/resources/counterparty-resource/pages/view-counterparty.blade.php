<x-filament-panels::page>
    @php
        $stats     = $this->getCounterpartyStats();
        $purchases = $this->getPurchases();
    @endphp

    <div class="space-y-6">

        {{-- ── Kontragent ma'lumotlari ── --}}
        <x-filament::section>
            <x-slot name="heading">Kontragent ma'lumotlari</x-slot>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Ism</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $record->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Telefon</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $record->phone ?? '—' }}</dd>
                </div>
            </dl>
        </x-filament::section>

        {{-- ── Statistika kartochkalari ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            @php
                $cards = [
                    ['label' => 'Jami soni',    'value' => number_format($stats['total_count']),               'color' => 'violet', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['label' => 'Jami summa',   'value' => number_format($stats['total_sum'],  0, '.', ' '),   'color' => 'blue',   'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z'],
                    ['label' => "To'langan",    'value' => number_format($stats['payed_sum'],  0, '.', ' '),   'color' => 'green',  'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Qarz',         'value' => number_format($stats['debt_sum'],   0, '.', ' '),   'color' => 'red',    'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'],
                ];

                $colorMap = [
                    'violet' => ['bg' => 'bg-violet-100 dark:bg-violet-900/40', 'icon' => 'text-violet-600 dark:text-violet-400', 'val' => 'text-gray-900 dark:text-white'],
                    'blue'   => ['bg' => 'bg-blue-100 dark:bg-blue-900/40',     'icon' => 'text-blue-600 dark:text-blue-400',     'val' => 'text-gray-900 dark:text-white'],
                    'green'  => ['bg' => 'bg-green-100 dark:bg-green-900/40',   'icon' => 'text-green-600 dark:text-green-400',   'val' => 'text-green-700 dark:text-green-400'],
                    'red'    => ['bg' => 'bg-red-100 dark:bg-red-900/40',       'icon' => 'text-red-600 dark:text-red-400',       'val' => 'text-red-700 dark:text-red-400'],
                ];
            @endphp

            @foreach ($cards as $card)
                @php $c = $colorMap[$card['color']]; @endphp
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm flex items-center gap-3">
                    <div class="shrink-0 w-10 h-10 rounded-lg {{ $c['bg'] }} flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor"
                             class="w-5 h-5 {{ $c['icon'] }}" style="width:20px;height:20px;flex-shrink:0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                        <p class="text-lg font-bold leading-tight {{ $c['val'] }}">{{ $card['value'] }}</p>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- ── Xaridlar tarixi ── --}}
        <x-filament::section>
            <x-slot name="heading">Xaridlar tarixi</x-slot>

            @if ($purchases->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1" stroke="currentColor"
                         style="width:48px;height:48px;flex-shrink:0"
                         class="text-gray-300 dark:text-gray-600 mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Hali xaridlar mavjud emas</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 px-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide whitespace-nowrap">#</th>
                            <th class="py-2 px-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide whitespace-nowrap">Sana</th>
                            <th class="py-2 px-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Mahsulotlar</th>
                            <th class="py-2 px-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide whitespace-nowrap">Soni</th>
                            <th class="py-2 px-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide whitespace-nowrap">Summa</th>
                            <th class="py-2 px-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide whitespace-nowrap">To'langan</th>
                            <th class="py-2 px-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide whitespace-nowrap">Qarz</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($purchases as $purchase)
                            <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">

                                <td class="py-2.5 px-3 text-gray-400 dark:text-gray-500 tabular-nums text-xs">
                                    #{{ $purchase->id }}
                                </td>

                                <td class="py-2.5 px-3 text-gray-600 dark:text-gray-300 whitespace-nowrap tabular-nums">
                                    {{ $purchase->created_at->format('d.m.Y') }}<br>
                                    <span class="text-xs text-gray-400">{{ $purchase->created_at->format('H:i') }}</span>
                                </td>

                                <td class="py-2.5 px-3">
                                    @if ($purchase->items->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($purchase->items as $item)
                                                <span class="inline-block rounded bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 text-xs text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                                        {{ $item->product?->model_name ?? '—' }}
                                                        <span class="font-bold">×{{ $item->count }}</span>
                                                    </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>

                                <td class="py-2.5 px-3 text-right tabular-nums font-medium text-gray-800 dark:text-gray-200">
                                    {{ number_format($purchase->count) }}
                                </td>

                                <td class="py-2.5 px-3 text-right tabular-nums font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ number_format($purchase->sum, 0, '.', ' ') }}
                                </td>

                                <td class="py-2.5 px-3 text-right tabular-nums whitespace-nowrap">
                                        <span class="font-medium text-green-600 dark:text-green-400">
                                            {{ number_format($purchase->payed_sum, 0, '.', ' ') }}
                                        </span>
                                </td>

                                <td class="py-2.5 px-3 text-right tabular-nums whitespace-nowrap">
                                    @if ($purchase->debt_sum > 0)
                                        <span class="inline-block rounded bg-red-50 dark:bg-red-900/30 px-1.5 py-0.5 text-xs font-semibold text-red-700 dark:text-red-400">
                                                {{ number_format($purchase->debt_sum, 0, '.', ' ') }}
                                            </span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr class="border-t-2 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 font-semibold">
                            <td colspan="3" class="py-2.5 px-3 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jami</td>
                            <td class="py-2.5 px-3 text-right tabular-nums text-gray-900 dark:text-white">
                                {{ number_format($stats['total_count']) }}
                            </td>
                            <td class="py-2.5 px-3 text-right tabular-nums text-gray-900 dark:text-white whitespace-nowrap">
                                {{ number_format($stats['total_sum'], 0, '.', ' ') }}
                            </td>
                            <td class="py-2.5 px-3 text-right tabular-nums text-green-600 dark:text-green-400 whitespace-nowrap">
                                {{ number_format($stats['payed_sum'], 0, '.', ' ') }}
                            </td>
                            <td class="py-2.5 px-3 text-right tabular-nums text-red-600 dark:text-red-400 whitespace-nowrap">
                                {{ number_format($stats['debt_sum'], 0, '.', ' ') }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </x-filament::section>

    </div>
</x-filament-panels::page>
