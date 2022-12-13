<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Jugadore;

class Jugadores extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nombre, $cedula, $telefono, $correo, $observaciones;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.jugadores.view', [
            'jugadores' => Jugadore::latest()
						->orWhere('nombre', 'LIKE', $keyWord)
						->orWhere('cedula', 'LIKE', $keyWord)
						->orWhere('telefono', 'LIKE', $keyWord)
						->orWhere('correo', 'LIKE', $keyWord)
						->orWhere('observaciones', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->nombre = null;
		$this->cedula = null;
		$this->telefono = null;
		$this->correo = null;
		$this->observaciones = null;
    }

    public function store()
    {
        $this->validate([
		'nombre' => 'required',
		'cedula' => 'required',
		'telefono' => 'required',
		'correo' => 'required',
		'observaciones' => 'required',
        ]);

        Jugadore::create([ 
			'nombre' => $this-> nombre,
			'cedula' => $this-> cedula,
			'telefono' => $this-> telefono,
			'correo' => $this-> correo,
			'observaciones' => $this-> observaciones
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Jugadore Successfully created.');
    }

    public function edit($id)
    {
        $record = Jugadore::findOrFail($id);

        $this->selected_id = $id; 
		$this->nombre = $record-> nombre;
		$this->cedula = $record-> cedula;
		$this->telefono = $record-> telefono;
		$this->correo = $record-> correo;
		$this->observaciones = $record-> observaciones;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'nombre' => 'required',
		'cedula' => 'required',
		'telefono' => 'required',
		'correo' => 'required',
		'observaciones' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Jugadore::find($this->selected_id);
            $record->update([ 
			'nombre' => $this-> nombre,
			'cedula' => $this-> cedula,
			'telefono' => $this-> telefono,
			'correo' => $this-> correo,
			'observaciones' => $this-> observaciones
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Jugadore Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Jugadore::where('id', $id);
            $record->delete();
        }
    }
}
