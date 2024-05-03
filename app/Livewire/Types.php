<?php

namespace App\Livewire;

use App\Models\Type;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Types extends Component
{
    public $editType = null;
    public $types;

    public $type, $typeEdit;

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
        } else {
            $this->addError('type', 'Tipo de espacio existente.');
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
        } else {
            $this->addError('typeEdit', 'Tipo de expacio existente.');
        }
    }

    public function close()
    {
        $this->editType = null;
        $this->reset();
    }

    public function delete($id)
    {
        $type = Type::find($id);
        $type->active = false;
        $type->save();
    }

    public function setActive($id)
    {
        $type = Type::find($id);
        $type->active = true;
        $type->save();
    }

    public function render()
    {
        $this->types = Type::all();

        return view('livewire.types');
    }
}
