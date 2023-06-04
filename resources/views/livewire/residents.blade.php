<div>
    <div class="card-body">
        <h5>Add New Residents</h5>
        <form wire:submit.prevent="saveResident">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-label">First Name</div>
                        <input type="text" wire:model="FirstName" class="form-control">
                        @error('FirstName')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="form-label">Middle Name</div>
                        <input type="text" wire:model="MiddleName" class="form-control">
                        @error('MiddleName')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-label">Last Name</div>
                        <input type="text" wire:model="LastName" class="form-control">
                        @error('LastName')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="form-label">Suffix</div>
                        <input type="text" wire:model="Suffix" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-label">Date of Birth</div>
                        <input type="date" wire:model="DateofBirth" class="form-control">
                        @error('DateofBirth')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-label">Civil Status</div>
                        <select wire:model="CivilStatus" class="form-control">
                            <option value="">--Select Status--</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Separated">Separated</option>
                            <option value="Widow">Widow</option>
                        </select>
                        @error('CivilStatus')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-label">Place of Birth</div>
                        <input type="text" wire:model="PlaceofBirth" class="form-control">
                        @error('PlaceofBirth')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    @if($forUpdate)
                        <button class="btn btn-primary" type="submit">Update</button>
                    @else
                        <button class="btn btn-primary" type="submit">Save</button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <hr>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Residents List
                </div>
                <div>
                    <input type="text" wire:model="searchTerm" placeholder="Search..." class="form-control">
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Suffix</th>
                    <th>Date of Birth</th>
                    <th>Place of Birth</th>
                    <th>Civil Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody wire:sortable="updateOrder">
                @foreach ($residents as $resident)
                    <tr wire:sortable.item="{{ $resident->id }}" wire:key="{{ $resident->id }}">
                        <td>{{ $resident->FirstName }}</td>
                        <td>{{ $resident->MiddleName }}</td>
                        <td>{{ $resident->LastName }}</td>
                        <td>{{ $resident->Suffix }}</td>
                        <td>{{ $resident->DateofBirth }}</td>
                        <td>{{ $resident->PlaceofBirth }}</td>
                        <td>{{ $resident->CivilStatus }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" wire:click="update('{{ $resident->id }}')">Edit</button>
                            <button class="btn btn-danger btn-sm" wire:click="delete('{{ $resident->id }}')">Remove</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $residents->links() }}
    </div>
</div>

<div>
    <form wire:submit.prevent="upload" enctype="multipart/form-data">
        <div>
            <input type="file" wire:model="file" id="file-input">
            <button wire:loading.attr="disabled" wire:target="upload" class="btn btn-primary">Upload</button>
            <div wire:loading wire:target="upload">Uploading...</div>
            <div wire:loading wire:target="upload" wire:poll="upload" class="mt-2">
                <progress max="100" wire:model="progress"></progress>
                {{ $progress }}% Uploaded
            </div>
            @error('file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </form>
</div>



@push('scripts')
<script>
    document.addEventListener("livewire:load", function () {
        const sortableList = document.querySelector('tbody');
        new Sortable(sortableList, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onUpdate: function (event) {
                let itemIds = [];
                let items = event.to.children;
                for (let i = 0; i < items.length; i++) {
                    itemIds.push(items[i].getAttribute('wire:key'));
                }
                Livewire.emit('updateOrder', itemIds);
            },
        });
    });
</script>
@endpush
