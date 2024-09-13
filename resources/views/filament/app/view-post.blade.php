<x-filament-panels::page>
{{--    {{ dd($record) }}--}}
    <style>
        .tutorials-single p{
            margin: 16px 0;
        }
        .tutorials-single figcaption.attachment__caption {
            display: none;
        }
        .tutorials-single menu, .tutorials-single ol, .tutorials-single ul {
            list-style: revert;
            margin: revert;
            padding: revert;
        }
        .tutorials-single iframe {
            aspect-ratio: 16 / 9;
            width: 100% !important;
            height: auto;
        }
    </style>
    <div class="px-6 py-32 lg:px-8 tutorials-single">
        <div class="mx-auto max-w-5xl text-base leading-7">
            <p class="text-base font-semibold leading-7 text-indigo-600">{{ $record->category->name }}</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $record->title }}</h1>
            @if($record->banner)
                <img src="http://fancy-studio.test/{{$record->banner}}" alt="{{$record->title}}" class="h-full w-full object-cover object-center mt-6" />
            @endif
            <div class="mt-6">
                {!! $record->content !!}
            </div>
        </div>
    </div>

</x-filament-panels::page>
