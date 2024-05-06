<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Services extends Component
{
    public $editService = null;
    public $services;

    public $service = [
        'service' => '',
        'description' => ''
    ];

    public $serviceEdit = [
        'service' => '',
        'description' => ''
    ];

    public function store()
    {
        $this->validate([
            'service.service' => 'required',
        ]);

        $serviceExists = Service::where('service', $this->service['service'])->exists();

        if (!$serviceExists) {
            $serviceStore = new Service();
            $serviceStore->service = $this->service['service'];
            $serviceStore->description = $this->service['description'];
            $serviceStore->save();

            $this->reset();
        } else {
            $this->addError('service.service', 'Servicio existente.');
        }
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
        $this->validate([
            'serviceEdit.service' => 'required'
        ]);

        $id = $this->editService;

        $serviceUpdate = Service::find($id);
        $serviceUpdate->service = $this->serviceEdit['service'];
        $serviceUpdate->description = $this->serviceEdit['description'];
        $serviceUpdate->save();

        $this->reset();
        $this->editService = null;
    }

    public function close()
    {
        $this->editService = null;
        $this->reset();
    }

    public function delete($id)
    {
        $service = Service::find($id);
        $service->active = false;
        $service->save();
    }

    public function setActive($id)
    {
        $service = Service::find($id);
        $service->active = true;
        $service->save();
    }

    public function render()
    {
        $this->services = Service::all();
        return view('livewire.services');
    }
}
