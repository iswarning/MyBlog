<?php

namespace App\Services;
use App\Models\User;

class RegisterService 
{
    private $name;
    private $email;
    private $password;
    
    public function __construct(array $request)
    {
        $this->name = $request['name'];
        $this->password = $request['password'];
        $this->email = $request['email'];
    }

    public function store()
    {
        return User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ]);
    }
}