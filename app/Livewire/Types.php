<?php

namespace App\Livewire;

use App\Models\Type;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Types extends Component
{
    public $editType = null;
    public $types;

    #[Validate('required')]
    public $type;

    public function store()
    {
        $this->validate();
        Type::create([
            'type' => $this->type
        ]);

        $this->reset();
    }

    public function edit($id)
    {
        $this->editType = $id;
        $type = Type::find($id);

        $this->type = $type->type;
    }

    public function update()
    {
        $this->validate();
        Type::find($this->editType)->update([
            'type' => $this->type,
        ]);
        $this->reset();
        $this->editType = null;
    }

    public function delete($id)
    {
        $type = Type::find($id);
        $type->delete();
    }

    public function render()
    {
        $this->types = Type::all();
        return view('livewire.types');
    }
}
