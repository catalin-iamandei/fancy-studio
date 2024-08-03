<?php

return [
    'date_picker_range' => [
        'Today' => [now(), now()],
        'Yesterday' => [now()->yesterday(), now()->yesterday()],
        'Current period' =>
            now()->day <= 15 ?
                [now()->startOfMonth(), now()->startOfMonth()->addDays(14)]
                :
                [now()->startOfMonth()->addDays(15), now()->endOfMonth()]
        ,
        'Last period' =>
            now()->day <= 15 ?
                [now()->startOfMonth()->subMonth()->addDays(15), now()->subMonth()->endOfMonth()]
                :
                [now()->startOfMonth(), now()->startOfMonth()->addDays(14)]
        ,
        'Current month' =>
            [now()->startOfMonth(), now()->endOfMonth()]
        ,
        'Last month' =>
            [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]
        ,
        'Current year' =>
            [now()->startOfYear(), now()->endOfYear()]
        ,
        'Last year' =>
            [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()]
        ,
    ],

];
