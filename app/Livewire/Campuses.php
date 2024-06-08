<?php

namespace App\Livewire;

use App\Models\Campus;
use Livewire\Component;
use Livewire\WithPagination;

class Campuses extends Component
{
    public $editCampus = null, $activeFilter = false;

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

    use WithPagination;

    protected $messages = [
        'campus.campus.required' => 'El campo de sede es obligatorio.',
        'campus.address.required' => 'El campo de dirección es obligatorio.',
        'campus.city.required' => 'El campo de ciudad es obligatorio.',
    ];

    public function store()
    {
        $this->validate([
            'campus.campus' => 'required',
            'campus.address'=> 'required',
            'campus.city'=> 'required'
        ]);

        $campusExists = Campus::where('campus', $this->campus['campus'])
            ->where('city', $this->campus['city'])
            ->exists();

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
            $this->dispatch('success', 'Sede agregada correctamente.');
        } else {
            $this->addError('campus.campus', 'Sede ya registrada.');
            $this->dispatch('failed', 'Error en datos.');
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

        $campusExists = Campus::where('campus', $this->campusEdit['campus'])
            ->where('city', $this->campusEdit['city'])
            ->exists();

        if (!$campusExists) {
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
            $this->dispatch('success', 'Sede actualizada correctamente.');
        } else {
            $this->addError('campusEdit.campus', 'Sede ya registrada.');
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function delete($id)
    {
        $campus = Campus::find($id);
        $campus->active = false;
        $campus->save();
        $this->resetPage();
        $this->dispatch('warning', 'Sede desactivada.');
    }

    public function setActive($id)
    {
        $campus = Campus::find($id);
        $campus->active = true;
        $campus->save();
        $this->resetPage();
        $this->dispatch('success', 'Sede activada.');
    }

    public function close()
    {
        $this->editCampus = null;
        $this->reset();
        $this->resetPage();
    }

    public function filterByActive()
    {
        $this->activeFilter = !$this->activeFilter;
        $this->resetPage();
    }

    public function render()
    {
        sleep(1);

        $allCampuses = Campus::all();
        $allCampuses = Campus::query();

        if (!$this->activeFilter) {
            $allCampuses->where('active', true);
        }

        $allCampuses = $allCampuses->orderBy('active', 'desc')
            ->orderBy('campus', 'asc');

        $campuses = $allCampuses->paginate(10);

        return view('livewire.campuses', [
            'campuses' => $campuses
        ]);
    }
}
