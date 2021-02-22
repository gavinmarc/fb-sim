<?php

return [
  'model' => App\Models\User::class,

  'search' => ['name', 'email'],

  'update' => true,
  'delete' => true,

  'with_auth' => false,

  'validation' => [
    'name' => 'required',
    'email' => 'required|email',
  ],

  'fields' => [
    'name' => 'text',
    'email' => 'email'
  ],

  'show' => ['name', 'email'],
];
