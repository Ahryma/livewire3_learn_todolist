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

    // Edit purpose
    public $todoId;

    #[Rule('required|min:3|max:50')]
    public $newName;

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

    public function delete($id)
    {
        Todo::find($id)->delete();
    }

    public function toggle($id)
    {
        $todo = Todo::find($id);
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function edit($id)
    {
        $this->todoId = $id;
        $this->newName = Todo::find($id)->name;
    }

    public function cancel()
    {
        $this->reset('todoId', 'newName');
    }

    public function update()
    {
        $validated = $this->validateOnly('newName');
        Todo::find($this->todoId)->update([
            'name' => $validated['newName'],
        ]);

        $this->cancel();
    }


    public function render()
    {
        return view('livewire.to-do-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5),
        ]);
    }
}
