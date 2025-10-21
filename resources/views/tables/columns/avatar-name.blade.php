<div>
    <div class="flex items-center gap-2">
        <img src="{{ $getRecord()->getFilamentAvatarUrl() }}" alt="{{ $getRecord()->name }}"
            class="w-10 h-10 rounded-full object-cover">
        <p class="text-sm">{{ $getRecord()->name }}</p>
    </div>
</div>
