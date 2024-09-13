<x-filament-panels::page>
{{--    {{ dd($record->posts) }}--}}
    <style>
        .tutorials-category p{
            margin: 16px 0;
        }
        .tutorials-category figcaption.attachment__caption {
            display: none;
        }
        .tutorials-category menu, .tutorials-category ol, .tutorials-category ul {
            list-style: revert;
            margin: revert;
            padding: revert;
        }
        .tutorials-category iframe {
            aspect-ratio: 16 / 9;
            width: 100% !important;
            height: auto;
        }
        .tutorials-category .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    </style>


    <div class="tutorials-category">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl py-16 sm:py-24 lg:max-w-none lg:py-32">
                <h2 class="mb-5 text-2xl font-bold">{{ $record->name }}</h2>

                <div class="grid grid-cols-2 gap-4">
                    @forelse($record->posts as $post)
                        <div>
                            <div class="relative h-80 w-full overflow-hidden rounded-lg bg-white sm:aspect-h-1 sm:aspect-w-2 lg:aspect-h-1 lg:aspect-w-1 group-hover:opacity-75 sm:h-64">
                                <a href="{{route('filament.admin.resources.tutorials.posts.view', $post->id)}}">
                                    <img src="http://fancy-studio.test/{{$post->banner}}" alt="{{$post->title}}" class="h-full w-full object-cover object-center" />
                                </a>
                            </div>
                            <h3 class="mt-6 text-sm">
                                <a href="{{route('filament.admin.resources.tutorials.posts.view', $post->id)}}">
                                    <span class="absolute inset-0"></span>
                                    {{ $post->created_at->format('d.m.Y H:i') }}
                                </a>
                            </h3>
                            <a href="{{route('filament.admin.resources.tutorials.posts.view', $post->id)}}" class="text-xl font-semibold text-gray-900 mt-6">
                                {{ $post->title }}
                            </a>
                        </div>
                        @empty
                        <p>Niciun tutorial disponibil momentan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-filament-panels::page>
