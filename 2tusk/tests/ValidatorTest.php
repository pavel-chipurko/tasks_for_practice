<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Validator.php';

class ValidatorTest extends TestCase
{
    private function validData(): array
    {
        return [
            'email' => 'test@mail.com',
            'password' => 'Password123',
            'repit_password' => 'Password123',
            'phone_number' => '1234567',
            'name' => 'Ivan',
            'came_from' => 'site',
            'date_birth' => '2000-01-01',
        ];
    }

    public function testValidRegistration()
    {
        $result = validateRegistration($this->validData());
        $this->assertTrue($result['status']);
    }

    public function testInvalidEmail()
    {
        $data = $this->validData();
        $data['email'] = 'abc';
        $result = validateRegistration($data);

        $this->assertFalse($result['status']);
    }

    public function testPasswordMismatch()
    {
        $data = $this->validData();
        $data['repit_password'] = 'Wrong123';
        $result = validateRegistration($data);

        $this->assertFalse($result['status']);
    }

    public function testShortPassword()
    {
        $data = $this->validData();
        $data['password'] = 'Pass1';
        $data['repit_password'] = 'Pass1';

        $result = validateRegistration($data);
        $this->assertFalse($result['status']);
    }

    public function testInvalidName()
    {
        $data = $this->validData();
        $data['name'] = 'Ivan123';

        $result = validateRegistration($data);
        $this->assertFalse($result['status']);
    }

    public function testInvalidAge()
    {
        $data = $this->validData();
        $data['date_birth'] = date('Y-m-d'); // сегодня → возраст 0

        $result = validateRegistration($data);
        $this->assertFalse($result['status']);
    }

    public function testInvalidCameFrom()
    {
        $data = $this->validData();
        $data['came_from'] = 'instagram';

        $result = validateRegistration($data);
        $this->assertFalse($result['status']);
    }
}