<?php
return [
    'PaginationRequest' => [
        'per_page' => 'Item Per Page',
        'page' => 'Page',
        'status' => 'Status',
    ],
    'CreateUserRequest'=>[
        'name' => 'Name',
        'role_type' => 'Role Type',
        'address' => 'Address',
        'phone' => 'Phone',
        'identification' => 'Identification',
        'password' => 'Password',
        'email' => 'Email',
        'age' => 'Age',
    ],
    'ChangePassRequest' => [
        'old_password' => 'Old Password',
        'new_password' => 'New Password',
        'password_confirm' => 'Password Confirm',
    ],
    'UpdateUserRequest' => [
        'user_id' => 'User ID',
        'name' => 'Name',
        'role_type' => 'Role Type',
        'address' => 'Address',
        'phone' => 'Phone',
        'identification' => 'Identification',
        'password' => 'Password',
        'email' => 'Email',
        'age' => 'Age',
    ],
    'DeleteUserRequest' => [
        'user_id' => 'User ID',
    ],
    'GetUserByUserIdRequest' => [
        'user_id' => 'User ID',
    ],
    'ListUserRequest' => [
        'type' => 'Role Type',
        'per_page' => 'Item Per Page',
        'page' => 'Page',
    ],
    'ResetPassRequest' => [
        'token' => 'Token',
        'email' => 'Email',
        'new_password' => 'New Password',
        'password_confirm' => 'Password Confirm',
    ],
    'SendEmailRequest' => [
        'email' => 'Email',
    ],
];
