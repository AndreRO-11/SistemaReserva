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
        'type.unique' => 'Tipo de espacio ya registrado.',
        'typeEdit.required' => 'El campo de tipo de espacio es obligatorio.',
        'typeEdit.unique' => 'Tipo de espacio ya registrado.',
    ];

    public function store()
    {
        $type = $this->type;

        $this->validate([
            'type' => 'required|unique:types,type,' . $type
        ]);

        $type = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->type)));

        $typeStore = new Type();
        $typeStore->type = $type;

        if ($typeStore->save()) {
            $this->reset();
            $this->dispatch('success', 'Tipo de espacio agregado correctamente.');
        } else {
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
        $id = $this->editType;

        $this->validate([
            'typeEdit' => 'required|unique:types,type,' . $id
        ]);

        $type = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->typeEdit)));

        $typeUpdate = Type::find($id);
        $typeUpdate->type = $type;

        if ($typeUpdate->save()) {
            $this->reset();
            $this->editType = null;
            $this->dispatch('success', 'Tipo de espacio actualizado correctamente.');
        } else {
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function close()
    {
        $this->editType = null;
        $this->reset();
        $this->resetPage();
        $this->dispatch('warning', 'No se han guardado los cambios.');
    }

    public function delete($id)
    {
        $type = Type::find($id);
        $type->active = false;
        $type->save();
        $this->resetPage();
        $this->dispatch('success', 'Tipo de espacio desactivado.');
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
