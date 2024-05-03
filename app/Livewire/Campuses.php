<?php

namespace App\Livewire;

use App\Models\Campus;
use Livewire\Component;

class Campuses extends Component
{
    public $editCampus = null, $campuses;

    public $campus = [
        'campus' => '',
        'address' => '',
        'city' => ''
    ];

    public $campusEdit = [
        'campus' => '',
        'address' => '',
        'city' => ''
    ];

    public function store()
    {
        $this->validate([
            'campus.campus' => 'required',
            'campus.address'=> 'required',
            'campus.city'=> 'required'
        ]);

        $campusExists = Campus::where('campus', $this->campus['campus'])->exists();

        if (!$campusExists) {
            $campus = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->campus['campus'])));
            $address = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->campus['address'])));
            $city = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->campus['city'])));

            $campusStore = new Campus();
            $campusStore->campus = $campus;
            $campusStore->address = $address;
            $campusStore->city = $city;
            $campusStore->save();

            $this->reset();
        } else {
            $this->addError('campus.campus', 'Campus ya registrado.');
        }
    }

    public function edit($id)
    {
        $this->editCampus = $id;
        $campus = Campus::find($id);

        $this->campusEdit['campus'] = $campus->campus;
        $this->campusEdit['address'] = $campus->address;
        $this->campusEdit['city'] = $campus->city;
    }

    public function update()
    {
        $this->validate([
            'campusEdit.campus'=> 'required',
            'campusEdit.address'=> 'required',
            'campusEdit.city'=> 'required'
        ]);

        $campusExists = Campus::where('campus', $this->campusEdit['campus'])->exists();

        if ($campusExists) {
            $id = $this->editCampus;

            $campus = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->campusEdit['campus'])));
            $address = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->campusEdit['address'])));
            $city = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->campusEdit['city'])));

            $campusUpdate = Campus::find($id);
            $campusUpdate->campus = $campus;
            $campusUpdate->address = $address;
            $campusUpdate->city = $city;
            $campusUpdate->save();

            $this->reset();
            $this->editCampus = null;
        } else {
            $this->addError('campusEdit.campus', 'Campus ya registrado.');
        }
    }

    public function delete($id)
    {
        $campus = Campus::find($id);
        $campus->active = false;
        $campus->save();
    }

    public function setActive($id)
    {
        $campus = Campus::find($id);
        $campus->active = true;
        $campus->save();
    }

    public function close()
    {
        $this->editCampus = null;
        $this->reset();
    }

    public function render()
    {
        $this->campuses = Campus::all();

        return view('livewire.campuses');
    }
}
