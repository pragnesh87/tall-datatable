<div class="antialiased sans-serif bg-gray-200">
    <div class="container mx-auto py-6 px-4" x-data="datatables()" x-cloak>
        <h1 class="text-3xl py-4 border-b mb-10">Datatable</h1>

        <div class="mb-4 flex justify-between items-center">
            <div class="flex-1 pr-4">
                <div class="relative md:w-1/3">
                    <input type="search"
                        class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                        placeholder="Search..." wire:model.lazy="search">
                    <div class="absolute top-0 left-0 inline-flex items-center p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                            <circle cx="10" cy="10" r="7" />
                            <line x1="21" y1="21" x2="15" y2="15" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mr-2" x-show="selectedRows.length > 1">
                <div class="relative">
                    <button x-on:click="showMultiOption = !showMultiOption"
                        x-on:keydown.escape="showMultiOption = false"
                        class="rounded-lg inline-flex items-center bg-white hover:text-blue-500 focus:outline-none focus:shadow-outline text-gray-500 font-semibold py-2 px-2 md:px-4">
                        <span class="hidden md:block" x-ref="multiselect" x-text="selecttext"></span>
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                            height="24">
                            <path
                                d="M15.3 9.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"
                                class="heroicon-ui"></path>
                        </svg>
                    </button>
                    <ul x-show="showMultiOption" x-on:click.away="showMultiOption = false"
                        class="absolute font-normal bg-white shadow overflow-hidden rounded w-48 border mt-2 py-1 right-0 z-20">
                        <li>
                            <a href="#" class="flex items-center px-3 py-3 hover:bg-gray-200"
                                wire:click.prevent="$emit('swal:deleteconfirm', 'deleteSelected')">
                                <span class="ml-2">Delete</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-3 py-3 hover:bg-gray-200">
                                <span class="ml-2">Export</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="">
                <div class="relative inline-flex mr-2">
                    <svg class="w-2 h-2 absolute top-0 right-0 m-4 pointer-events-none"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 412 232">
                        <path
                            d="M206 171.144L42.678 7.822c-9.763-9.763-25.592-9.763-35.355 0-9.763 9.764-9.763 25.592 0 35.355l181 181c4.88 4.882 11.279 7.323 17.677 7.323s12.796-2.441 17.678-7.322l181-181c9.763-9.764 9.763-25.592 0-35.355-9.763-9.763-25.592-9.763-35.355 0L206 171.144z"
                            fill="#648299" fill-rule="nonzero" /></svg>
                    <select wire:model="paginate" name="paginate" id="paginate"
                        class="border border-gray-300 rounded-lg text-gray-500 h-10 pl-5 pr-10 inline-flex items-center bg-white hover:text-blue-500 focus:outline-none focus:shadow-outline appearance-none">
                        @foreach ($this->defaultPageOptions as $page)
                        <option value="{{ $page }}">{{ $page }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <div class="shadow rounded-lg flex">
                    <div class="relative">
                        <button x-on:click.prevent="open = !open"
                            class="rounded-lg inline-flex items-center bg-white hover:text-blue-500 focus:outline-none focus:shadow-outline text-gray-500 font-semibold py-2 px-2 md:px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:hidden" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                <path
                                    d="M5.5 5h13a1 1 0 0 1 0.5 1.5L14 12L14 19L10 16L10 12L5 6.5a1 1 0 0 1 0.5 -1.5" />
                            </svg>
                            <span class="hidden md:block">Display</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>

                        <div x-show="open" x-on:click.away="open = false"
                            class="z-40 absolute top-0 right-0 w-40 bg-white rounded-lg shadow-lg mt-12 -mr-1 block py-1 overflow-hidden">
                            <template x-for="heading in columns">
                                <label
                                    class="flex justify-start items-center text-truncate hover:bg-gray-100 px-4 py-2">
                                    <div class="text-teal-600 mr-3">
                                        <input type="checkbox"
                                            class="form-checkbox focus:outline-none focus:shadow-outline" checked
                                            x-on:click="toggleColumn(heading.key)">
                                    </div>
                                    <div class="select-none text-gray-700" x-text="heading.value"></div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                <thead>
                    <tr class="text-left">
                        <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100">
                            <label
                                class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer">
                                <input id="selectAll" type="checkbox"
                                    class="form-checkbox focus:outline-none focus:shadow-outline"
                                    x-on:click="selectAllCheckbox($event);">
                            </label>
                        </th>
                        <template x-for="heading in columns">
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs"
                                x-text="heading.value" :x-ref="heading.key" :class="{ [heading.key]: true }"></th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td class="border-dashed border-t border-gray-200 px-3">
                            <label
                                class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer">
                                <input type="checkbox"
                                    class="form-checkbox rowCheckbox focus:outline-none focus:shadow-outline"
                                    :name="{{ $user->id }}" x-on:click="getRowDetail($event, {{ $user->id }})">
                            </label>
                        </td>
                        <td class="border-dashed border-t border-gray-200 id">
                            <span class="text-gray-700 px-6 py-3 flex items-center">{{ $user->id }}</span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 name">
                            <span class="text-gray-700 px-6 py-3 flex items-center">{{ $user->name }}</span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 email">
                            <span class="text-gray-700 px-6 py-3 flex items-center">{{ $user->email }}</span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 role">
                            <span class="text-gray-700 px-6 py-3 flex items-center">{{ $user->role->name }}</span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 post">
                            <span
                                class="text-gray-700 px-6 py-3 flex items-center">{{ $user->posts->first()->title ?? '' }}</span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 gender">
                            <span class="text-gray-700 px-6 py-3 flex items-center">{{ ucfirst($user->gender) }}</span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 phone">
                            <span class="text-gray-700 px-6 py-3 flex items-center">{{ $user->phone }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan=6>@lang('User Not Found')</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="m-5">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</div>


@push('scripts')
<script>
    function datatables() {
    			return {
    				selectedRows: [],
                    columns: @entangle('columns'),
    				open: false,
                    showMultiOption: false,
                    selecttext: '0 Selected',

    				toggleColumn(key) {
    					// Note: All td must have the same class name as the headings key!
    					let columns = document.querySelectorAll('.' + key);

    					if (this.$refs[key].classList.contains('hidden') && this.$refs[key].classList.contains(key)) {
    						columns.forEach(column => {
    							column.classList.remove('hidden');
    						});
    					} else {
    						columns.forEach(column => {
    							column.classList.add('hidden');
    						});
    					}
    				},

    				getRowDetail($event, id) {
    					let rows = this.selectedRows;

    					if (rows.includes(id)) {
    						let index = rows.indexOf(id);
    						rows.splice(index, 1);
                            document.getElementById('selectAll').checked = false;
    					} else {
    						rows.push(id);
    					}
                        if(document.querySelectorAll('.rowCheckbox').length == rows.length){
                            document.getElementById('selectAll').checked = true;
                        }
                        this.selecttext = rows.length + ' Selected';
                        @this.set('selected', rows);
    				},

    				selectAllCheckbox($event) {
    					let columns = document.querySelectorAll('.rowCheckbox');

    					this.selectedRows = [];

    					if ($event.target.checked == true) {
    						columns.forEach(column => {
    							column.checked = true
    							this.selectedRows.push(parseInt(column.name))
    						});
    					} else {
    						columns.forEach(column => {
    							column.checked = false
    						});
    						this.selectedRows = [];
    					}

                        this.selecttext = this.selectedRows.length + ' Selected';
                        @this.set('selected', this.selectedRows);
    					//console.log(this.selectedRows);
    				}
    			}
    		}
</script>
@endpush
