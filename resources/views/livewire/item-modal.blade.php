<div>
    <!-- Button to open the modal -->
    <button wire:click="openModal">Open Modal</button>

    <!-- The modal -->
    @if ($isOpen)
        <div>
            <div>
                <!-- Modal content -->
                <h2>{{ $item->title }}</h2>
                <p>{{ $item->description }}</p>

                <!-- Edit form -->
                <form wire:submit.prevent="save">
                    <label for="title">Title:</label>
                    <input type="text" wire:model="item.title" id="title">

                    <label for="description">Description:</label>
                    <textarea wire:model="item.description" id="description"></textarea>

                    <button type="submit">Save</button>
                </form>

                <!-- Close button -->
                <button wire:click="closeModal">Close</button>
            </div>
        </div>
    @endif
</div>
