<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('الرصيد الحالي') }}
            :
            @if(isset($user->wallet))
            {{$user->wallet->balance }}
            @endif
        </h2>
    </x-slot>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-3xl lg:text-4xl text-center"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400 ">{{$session->game->title}}</span></h1>

                  <div  class="grid grid-cols-3 gap-4 text-center py-3">
                    <div class="text-green-600"><b>الوقت المتبقي لبدء السحبه :</b></div>
                    <div class="text-green-700"><b>سعر السيت :</b> {{$session->game->group_price}}</div>
                    <div class="text-green-500"><b>تاريخ :</b> {{$session->date}}</div>
                  </div>
                  <div  class="grid grid-cols-2 gap-4 text-center py-3">
                    <div class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400 "><b> الجائزه الثانيه :</b> {{$session->game->second_prize}}</div>
                    <div class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400 "><b>متراكم :</b> {{$session->game->accumulated}}</div>
                  </div>
                  <div  class="grid grid-cols-3 gap-4 text-center py-3">
                    <div class="text-blue-700"><b>خط اول :</b> {{$session->game->first_line}}</div>
                    <div class="text-blue-700"><b>خط وسط :</b> {{$session->game->middle_line}}</div>
                    <div class="text-blue-700"><b>خط اخير :</b> {{$session->game->last_line}}</div>
                  </div>
                  <div  class="grid grid-cols-4 gap-4 text-center py-3">
                    <div class="text-pink-700"><b>زوايا البطاقه :</b> {{$session->game->card_corners}}</div>
                    <div class="text-pink-700"><b>نصف زوايا السيت :</b> {{$session->game->half_group_angles}}</div>
                    <div class="text-pink-700"><b>زوايا السيت :</b> {{$session->game->group_angles}}</div>
                    <div class="text-pink-700"><b>خط أفقي :</b> {{$session->game->horizontal_line}}</div>
                  </div>

                </div>
            </div>
        </div>
    </div>




    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div  class="grid grid-cols-4 gap-4">
                    @foreach ($boards as $board)
                    <div class="text-center">
                    
                    <button  data-modal-target="staticModal" data-modal-toggle="staticModal"  class="<?php if($board->is_pay){ echo 'cursor-not-allowed text-white';} ?> relative  inline-flex items-center justify-center p-0.5 mb-2 mr-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                        <span class="relative px-5 py-2.5 transition-all ease-in duration-75 <?php if(!$board->is_pay){ echo 'bg-white';} ?>  dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                            {{ str_pad($board->card_number, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </button>
                    </div>

     
                    @endforeach       
                    </div>


                    <!-- Main modal -->
                    <div id="staticModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="w-50 fixed top-25 right-25 right-0 z-50 hidden  p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
                        <div class="relative w-full h-full max-w-2xl md:h-auto">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                         
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="staticModal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-6">
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    <button type="button" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">1 => {{1*$session->game->group_price}}</button>
                                    </p>
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    <button type="button" class="w-full text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">2 => {{2*$session->game->group_price}}</button>
                                    </p>
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    <button type="button" class="w-full focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">3 => {{3*$session->game->group_price}}</button>
                                    </p>
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    <button type="button" class="w-full focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">4 => {{4*$session->game->group_price}}</button>
                                    </p>
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    <button type="button" class="w-full focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900">6 => {{6*$session->game->group_price}}</button>
                                    </p>
                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    <button type="button" class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">8 => {{8*$session->game->group_price}}</button>
                                    </p>
                                </div>


                                <div class="flex justify-center pb-6">
                                <div class="relative flex" x-data="{ ...selectMultiple('select2') }">

                                    <!-- Selected -->
                                    <div class="flex flex-wrap border border-teal-400 rounded-3xl"
                                        @click="isOpen = true;"
                                        @keydown.arrow-down.prevent="if(dropdown.length > 0) document.getElementById(elSelect.id+'_'+dropdown[0].index).focus();">

                                        <template x-for="(option,index) in selected;" :key="option.value">
                                            <p class="m-1 self-center p-2 text-xs whitespace-nowrap rounded-3xl bg-teal-200 cursor-pointer hover:bg-red-300"
                                                x-text="option.text"
                                                @click="toggle(option)">
                                            </p>
                                        </template>

                                        <input type="text" placeholder="أختار ارقام البطاقات" class="pl-2 rounded-3xl h-10"
                                            x-model="term"
                                            x-ref="input" />
                                    </div>

                                    <!-- Dropdown -->
                                    <div class="absolute mt-12 z-10 w-full max-h-72 overflow-y-auto rounded-xl bg-teal-100 "
                                        x-show="isOpen"
                                        @mousedown.away="isOpen = false">

                                        <template x-for="(option,index) in dropdown" :key="option.value">
                                            <div class="cursor-pointer hover:bg-teal-200 focus:bg-teal-300 focus:outline-none"
                                                :class="(term.length > 0 && !option.text.toLowerCase().includes(term.toLowerCase())) && 'hidden';"
                                                x-init="$el.id = elSelect.id + '_' + option.index; $el.tabIndex = option.index;"
                                                @click="toggle(option)"
                                                @keydown.enter.prevent="toggle(option);"
                                                @keydown.arrow-up.prevent="if ($el.previousElementSibling != null) $el.previousElementSibling.focus();"
                                                @keydown.arrow-down.prevent="if ($el.nextElementSibling != null) $el.nextElementSibling.focus();">

                                                <p class="p-2"
                                                x-text="option.text"></p>
                                            </div>
                                        </template>
                                    </div>
                                    </div>

                                </div>
                                <form action="{{ route('process-form') }}" method="POST">
                                @csrf
                                <input name="game_id" value="{{$session->game->id}}" class="hidden">
                                <input name="session_id" value="{{$session->id}}" class="hidden">

                                <select id="select2" name="select2[]" class="hidden" multiple>
                                @foreach ($boards as $board)
                                @if($board->is_pay==0)
                                <option value="{{$board->card_number}}">{{ str_pad($board->card_number, 3, '0', STR_PAD_LEFT) }}</option>
                                @endif
                                @endforeach  

                                </select>
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400 mx-6">
                                    <button type="submit" class="w-full focus:outline-none text-white bg-pink-500 hover:bg-pink-800 focus:ring-4 focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-900">تأكيد الدفع</button>
                                </p>
                                </form>
          
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('selectMultiple', (elSelectId) => ({

            elSelect: document.getElementById(elSelectId),
            isOpen: false,
            term: '',

            selected: [],
            dropdown: [],

            // in the <select> element, set the attributes :
            //  "multiple" to avoid to Always set "selected" to the first item
            //  class="hidden" (better than hide it with javascript which has a slow reaction)
            init()
            {
                for(var index=0; index < this.elSelect.options.length; index++)
                {
                    if (this.elSelect.options[index].selected)
                        this.selected.push(this.elSelect.options[index]);
                    else
                        this.dropdown.push(this.elSelect.options[index]);
                }
            },

            toggle(option)
            {
                console.log( this.selected);

                var property1 = (option.selected == true) ? 'dropdown' : 'selected';
                var property2 = (option.selected == true) ? 'selected' : 'dropdown';

                option.selected = !option.selected;

                // add
                this[property1].push(option);

                // remove
                this[property2] = this[property2].filter((opt, index)=>{
                    return opt.value != option.value;
                });
                
                // reorder this.dropdown to their original select.options indexes
                if (property1 == 'dropdown')
                    this.dropdown.sort((opt1, opt2) => (opt1.index > opt2.index) ? 1 : -1)

                // after adding, re-focus the input
                if (option.selected)
                    this.$refs.input.focus();
            },
        }))
    });
</script>