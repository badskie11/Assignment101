<div wire:sortable="dragDrop" wire:sortable-group="listItems" class="sortable-list">
    @foreach ($listItems as $item)
        <div wire:key="item-{{ $item->id }}" wire:sortable.item="{{ $item->id }}" class="sortable-item">
            {{ $item->name }}
        </div>
    @endforeach
</div>
