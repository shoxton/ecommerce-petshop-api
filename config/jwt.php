<?php

return [

    'private_key' => storage_path(env('JWT_KEY_PRIVATE_PATH','keys/private.key')),

    'public_key' => storage_path(env('JWT_KEY_PUBLIC_PATH','keys/public.key')),
];
