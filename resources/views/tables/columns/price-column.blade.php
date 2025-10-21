<div>
    <div class="flex flex-col">
        @if ($getRecord()->discount_price)
            <div class="flex items-center space-x-2 gap-2">
                <span class="text-sm text-gray-500 line-through">SAR {{ number_format($getRecord()->price, 2) }}</span>
                <span class="text-sm font-medium">SAR {{ number_format($getRecord()->discount_price, 2) }}</span>
            </div>
        @else
            <span class="font-normal text-sm">SAR {{ number_format($getRecord()->price, 2) }}</span>
        @endif
    </div>
