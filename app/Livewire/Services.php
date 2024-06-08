<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Services extends Component
{
    public $editService = null, $activeFilter = false;

    use WithPagination;

    public $service = [
        'service' => '',
        'description' => ''
    ];

    public $serviceEdit = [
        'service' => '',
        'description' => ''
    ];

    protected $messages = [
        'service.service.required' => 'El campo de servicio es obligatorio.',
        'serviceEdit.service.unique' => 'Servicio ya registrado.',
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
            $this->dispatch('success', 'Servicio agregado correctamente.');
        } else {
            $this->addError('service.service', 'Servicio ya registrado.');
            $this->dispatch('failed', 'Error en datos.');
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
        $id = $this->editService;

        $this->validate([
            'serviceEdit.service' => 'required|unique:services,service,' . $id,
        ]);

        $serviceUpdate = Service::find($id);

        $serviceUpdate->service = $this->serviceEdit['service'];
        $serviceUpdate->description = $this->serviceEdit['description'];
        $serviceUpdate->save();

        $this->reset();
        $this->editService = null;
        $this->dispatch('success', 'Servicio actualizado correctamente.');
    }

    public function close()
    {
        $this->editService = null;
        $this->reset();
        $this->resetPage();
        $this->dispatch('warning', 'No se han guardado los cambios.');
    }

    public function delete($id)
    {
        $service = Service::find($id);
        $service->active = false;
        $service->save();
        $this->resetPage();
        $this->dispatch('warning', 'Servicio desactivado.');
    }

    public function setActive($id)
    {
        $service = Service::find($id);
        $service->active = true;
        $service->save();
        $this->resetPage();
        $this->dispatch('success', 'Servicio activado.');
    }

    public function filterByActive()
    {
        $this->activeFilter = !$this->activeFilter;
        $this->resetPage();
    }

    public function render()
    {
        sleep(1);

        $allServices = Service::all();
        $allServices = Service::query();

        if (!$this->activeFilter) {
            $allServices->where('active', true);
        }

        // ORDEN
        $allServices = $allServices->orderBy('active', 'desc')
            ->orderBy('service', 'asc');

        $services = $allServices->paginate(5);

        return view('livewire.services', [
            'services' => $services
        ]);
    }
}
