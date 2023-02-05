<?php

return [
    'locale'    => [
        // The application's default localization/language
        'default'   => 'en_US',
        // The locale used by the app in case the default is left out
        'fallback'   => 'en_US',
    ],
    'storage' => str_replace("config", "uploads/", dirname(__FILE__))
];
