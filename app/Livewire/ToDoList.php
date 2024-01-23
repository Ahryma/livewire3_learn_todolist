<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

class ToDoList extends Component
{

    use WithPagination;

    // Rule for Name
    #[Rule('required|min:3|max:50')]
    public $name;

    public $search;

    public function create()
    {
        /* Steps :
        1. Validate
        2. Create the To Do List
        3. Clear inputs
        4. Send Flash Message
        */

        // First Step
        $validated = $this->validateOnly('name');

        // Second Step
        Todo::create($validated);

        // Third Step
        $this->reset('name');

        // Forth Step
        session()->flash('success', 'Successfully Created !');
    }


    public function render()
    {
        return view('livewire.to-do-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5),
        ]);
    }
}
