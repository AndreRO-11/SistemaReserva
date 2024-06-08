<?php

namespace App\Livewire;

use App\Models\Type;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Types extends Component
{
    public $editType = null;
    public $typesCount, $activeFilter = false;

    public $type, $typeEdit;

    use WithPagination;

    protected $messages = [
        'type.required' => 'El campo de tipo de espacio es obligatorio.',
    ];

    public function store()
    {
        $this->validate([
            'type' => 'required'
        ]);

        $type = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->type)));

        $typeExists = Type::where('type', $this->type)->exists();

        if (!$typeExists) {
            $typeStore = new Type();
            $typeStore->type = $type;
            $typeStore->save();

            $this->reset();
            $this->dispatch('success', 'Tipo de espacio agregado correctamente.');
        } else {
            $this->addError('type', 'Tipo de espacio existente.');
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function edit($id)
    {
        $this->editType = $id;
        $type = Type::find($id);

        $this->typeEdit = $type->type;
    }

    public function update()
    {
        $this->validate([
            'typeEdit' => 'required'
        ]);

        $id = $this->editType;
        $typeExists = Type::where('type', $this->typeEdit)->exists();

        $type = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->typeEdit)));

        if (!$typeExists) {
            $typeUpdate = Type::find($id);
            $typeUpdate->type = $type;
            $typeUpdate->save();

            $this->reset();
            $this->editType = null;
            $this->dispatch('success', 'Tipo de espacio actualizado correctamente.');
        } else {
            $this->addError('typeEdit', 'Tipo de expacio existente.');
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function close()
    {
        $this->editType = null;
        $this->reset();
        $this->resetPage();
    }

    public function delete($id)
    {
        $type = Type::find($id);
        $type->active = false;
        $type->save();
        $this->resetPage();
        $this->dispatch('warning', 'Tipo de espacio desactivado.');
    }

    public function setActive($id)
    {
        $type = Type::find($id);
        $type->active = true;
        $type->save();
        $this->resetPage();
        $this->dispatch('success', 'Tipo de espacio activado.');
    }

    public function filterByActive()
    {
        $this->activeFilter = !$this->activeFilter;
        $this->resetPage();
    }

    public function render()
    {
        sleep(1);

        $allTypes = Type::all();
        $allTypes = Type::query();

        if (!$this->activeFilter) {
            $allTypes->where('active', true);
        }

        $allTypes = $allTypes->orderBy('active', 'desc')
            ->orderBy('type', 'asc');

        $types = $allTypes->paginate(10);

        return view('livewire.types', [
            'types' => $types
        ]);
    }
}
