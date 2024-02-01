<?php
return [
    'PaginationRequest' => [
        'per_page' => 'Số lượng bản ghi trên một trang',
        'page' => 'Trang',
        'status' => 'Trạng thái',
    ],
    'CreateUserRequest' => [
        'name' => 'Tên',
        'role_type' => 'Quyền',
        'address' => 'Địa chỉ',
        'phone' => 'Số điện thoại',
        'identification' => 'Số CCCD/Passport',
        'password' => 'Mật khẩu',
        'email' => 'Email',
        'age' => 'Tuổi',
    ],
    'ChangePassRequest' => [
        'old_password' => 'Mật khẩu cũ',
        'new_password' => 'Mật khẩu mới',
        'password_confirm' => 'Xác nhận mật khẩu',
    ],
    'UpdateUserRequest' => [
        'user_id' => 'ID người dùng',
        'name' => 'Tên',
        'role_type' => 'Quyền',
        'address' => 'Địa chỉ',
        'phone' => 'Số điện thoại',
        'identification' => 'Số CCCD/Passport',
        'password' => 'Mật khẩu',
        'email' => 'Email',
        'age' => 'Tuổi',
    ],
    'DeleteUserRequest' => [
        'user_id' => 'ID người dùng',
    ],
    'GetUserByUserIdRequest' => [
        'user_id' => 'Mã người dùng',
    ],
    'ResetPassRequest' => [
        'token' => 'Token',
        'email' => 'Email',
        'new_password' => 'Mật khẩu mới',
        'password_confirm' => 'Xác nhận mật khẩu',
    ],
    'SendEmailRequest' => [
        'email' => 'Email',
    ],
];
