<?php

// config for gamalkh/GptContentReviewer
return [

    //GPT Version
    'api_key' => env('CHAT_GPT_API_KEY'),
    'review_threshold' => 0.7,

    //    gpt-4o, gpt-4o-mini, gpt-4-turbo, gpt-4, and gpt-3.5-turbo
    'Model' => env('GPT_MODEL', 'gpt-3.5-turbo'),

];
