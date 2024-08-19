@if($getState())
    @foreach($getState() as $variable => $value)
        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
            <strong>{{$value['name']}}</strong>: ${{$value['amount'] }}{{ '; '  }} &nbsp;&nbsp;
         </span>
    @endforeach
@endif
