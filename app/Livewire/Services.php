<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Services extends Component
{
    public $editService = null;
    public $services;
    public $confirmation = false;

    #[Rule([
        'serviceEdit.service' => 'required'
    ])]
    public $serviceEdit = [
        'service' => '',
        'description' => ''
    ];

    public function store()
    {
        $this->validate();
        Service::create([
            'service' => $this->serviceEdit['service'],
            'description' => $this->serviceEdit['description'],
            'active' => true
        ]);
        $this->reset();
    }

    public function edit($id)
    {
        $this->editService = $id;
        $service = Service::find($id);

        $this->serviceEdit['service'] = $service->service;
        $this->serviceEdit['description'] = $service->description;
    }

    public function update()
    {
        $this->validate();
        Service::find($this->editService)->update([
            'service' => $this->serviceEdit['service'],
            'description' => $this->serviceEdit['description'],
        ]);
        $this->reset();
        $this->editService = null;
    }

    public function close()
    {
        $this->editService = null;
    }

    public function delete($id)
    {
        $service = Service::find($id);
        $service->update([
            'active' => false
        ]);
        $this->render();
    }

    public function render()
    {
        $this->services = Service::where('active', true)->get();
        return view('livewire.services');
    }
}
