<div>
    {{-- The best athlete wants his opponent at his best. --}}

    @include('livewire.includes.create-todo-box')
    @include('livewire.includes.search-box')

    <div id="todos-list">
        @foreach ($todos as $item)
        @include('livewire.includes.todo-card')
        @endforeach

        <div class="my-2">
            <!-- Pagination goes here -->
            {{ $todos->links() }}
        </div>
    </div>
</div>