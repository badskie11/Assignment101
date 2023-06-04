<div>
    <form wire:submit.prevent="sendMessage">
        <input type="text" wire:model="message">
        @error('message') <span class="error">{{ $message }}</span> @enderror
        <button type="submit">Send</button>
    </form>
</div>
