<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Resident;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Residents extends Component
{
    use LivewireAlert;
    use WithPagination;
    use WithFileUploads;

    public $CivilStatus, $FirstName, $MiddleName, $LastName, $Suffix, $DateofBirth, $PlaceofBirth, $forUpdate;
    public $searchTerm;
    public $list;
    public $file; // Added property for file upload
    public $progress; // Added property for progress tracking

    protected $paginationTheme = 'bootstrap';

    public $draggingItemId;
    public $draggingItemIndex;
    public $isDragging = false;

    public function render()
    {
        $this->list = Resident::orderBy('id','DESC')->get();
        $residents = $this->getResidentList()->paginate(4);
        return view('livewire.residents', compact('residents'));
    }

    public function delete($id)
    {
        $delete = Resident::where('id', $id)->delete();
        if ($delete) {
            $this->alert('success', 'Successfully deleted!');
        }
    }

    public function update($id)
    {
        $info = Resident::where('id', $id)->first();

        if (isset($info)) {
            $this->sessionID = $id;
            $this->forUpdate = true;
            $this->FirstName = $info->FirstName;
            $this->MiddleName = $info->MiddleName;
            $this->LastName = $info->LastName;
            $this->Suffix = $info->Suffix;
            $this->DateofBirth = $info->DateofBirth;
            $this->PlaceofBirth = $info->PlaceofBirth;
            $this->CivilStatus = $info->CivilStatus;
        }
    }

    public function saveResident()
    {
        $validate = $this->validate([
            'FirstName' => 'required',
            'LastName' => 'required',
            'MiddleName' => 'required',
            'DateofBirth' => 'required',
            'PlaceofBirth' => 'required',
            'CivilStatus' => 'required'
        ], [
            'FirstName.required' => 'First Name is required',
            'LastName.required' => 'Last Name is required',
            'MiddleName.required' => 'Middle Name is required',
            'DateofBirth.required' => 'Date of Birth is required',
            'PlaceofBirth.required' => 'Place of Birth is required',
            'CivilStatus.required' => 'Civil Status is required',
        ]);

        if ($validate) {
            if ($this->forUpdate) {
                $data = [
                    'FirstName' => $this->FirstName,
                    'MiddleName' => $this->MiddleName,
                    'LastName' => $this->LastName,
                    'Suffix' => $this->Suffix,
                    'DateofBirth' => $this->DateofBirth,
                    'PlaceofBirth' => $this->PlaceofBirth,
                    'CivilStatus' => $this->CivilStatus,
                ];

                $update = Resident::where('id', $this->sessionID)
                    ->update($data);
                if ($update) {
                    $this->alert('success', $this->FirstName . ' ' . $this->LastName . ' has been updated', ['toast' => false, 'position' => 'center']);
                }
            } else {
                $resident = new Resident();
                $resident->ResidentNo = strtoupper(uniqid());
                $resident->FirstName = $this->FirstName;
                $resident->MiddleName = $this->MiddleName;
                $resident->LastName = $this->LastName;
                $resident->Suffix = $this->Suffix;
                $resident->DateofBirth = $this->DateofBirth;
                $resident->PlaceofBirth = $this->PlaceofBirth;
                $resident->CivilStatus = $this->CivilStatus;
                $resident->save();

                $this->alert('success', $this->FirstName . ' ' . $this->LastName . ' has been added', ['toast' => false, 'position' => 'center']);
            }

            $this->reset([
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'DateofBirth',
                'PlaceofBirth',
                'CivilStatus',
            ]);
        }
    }

    public function getResidentList()
    {
        $query = Resident::query();

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('FirstName', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('LastName', 'LIKE', '%' . $this->searchTerm . '%');
            });
        }

        return $query->orderBy('order', 'ASC')->orderBy('id', 'DESC');
    }

    public function updatedFile($file)
    {
        $this->validate([
            'file' => 'required|file|mimes:jpeg,png,pdf|max:2048', // Adjust the maximum file size as needed
        ]);

        $this->upload();
    }

    public function upload()
{
    $this->validate([
        'file' => 'required|file|mimes:jpeg,png,pdf|max:2048',
    ]);

    $filename = $this->file->store('uploads', 'public');

    // Your logic to process the uploaded file and save the filename in your database or perform any other operations.

    // Reset the file input and progress value.
    $this->file = null;
    $this->progress = 0;
}


    public function residentOrderUpdated($residentId, $newIndex)
    {
        $residents = $this->getResidentList()->get();
        $currentOrder = Arr::pluck($residents, 'id');

        $newOrder = Arr::moveElement($currentOrder, $currentOrder[$residentId], $newIndex);
        $this->updateOrder($newOrder);
    }

    public function updateOrder($list)
    {
        foreach ($list as $index => $residentId) {
            Resident::where('id', $residentId)->update(['order' => $index]);
        }
    }

    public function startDragging($itemId, $index)
    {
        $this->draggingItemId = $itemId;
        $this->draggingItemIndex = $index;
        $this->isDragging = true;
    }

    public function finishDragging()
    {
        $this->isDragging = false;
        $this->dispatchBrowserEvent('residentOrderUpdated', ['residentId' => $this->draggingItemId, 'newIndex' => $this->draggingItemIndex]);
    }
}
