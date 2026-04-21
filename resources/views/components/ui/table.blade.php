<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-sm text-left']) }}>
        @isset($head)
            <thead>
                <tr class="border-b border-amber-100 bg-amber-50/50">
                    {{ $head }}
                </tr>
            </thead>
        @endisset
        
        <tbody class="divide-y divide-amber-50">
            {{ $slot }}
        </tbody>
    </table>
</div>
