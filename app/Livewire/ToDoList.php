<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
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

        try {
            // First Step
            $validated = $this->validateOnly('name');

            // Second Step
            Todo::create($validated);

            // Third Step
            $this->reset('name');

            // Forth Step
            session()->flash('success', 'Successfully Created !');

            $this->resetPage();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to Create Todo !');
            return;
        }
    }

    public function delete($id)
    {
        try {
            Todo::findOrFail($id)->delete();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to Delete Todo !');
            return;
        }
    }

    public function toggle($id)
    {
        try {
            $todo = Todo::find($id);
            $todo->completed = !$todo->completed;
            $todo->save();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to Toggle Todo !');
            return;
        }
    }

    public function edit($id)
    {
        try {
            $this->todoId = $id;
            $this->newName = Todo::find($id)->name;
        } catch (Exception $e) {
            session()->flash('error', 'Failed to Edit Todo !');
            return;
        }
    }

    public function cancel()
    {
        try {
            $this->reset('todoId', 'newName');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to Cancel Edit !');
            return;
        }
    }

    public function update()
    {
        try {
            $validated = $this->validateOnly('newName');
            Todo::find($this->todoId)->update([
                'name' => $validated['newName'],
            ]);

            $this->cancel();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to Update Todo !');
            return;
        }
    }


    public function render()
    {
        return view('livewire.to-do-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5),
        ]);
    }
}
