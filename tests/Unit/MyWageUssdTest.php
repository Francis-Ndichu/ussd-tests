<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\UssdController;

class MyWageUssdTest extends TestCase
{
    public function testValidateLoanAmount()
    {
        $controller = new UssdController();
        

        $result = $controller->validateLoanAmount('500', 1000);
        $this->assertNull($result);

    
        $result = $controller->validateLoanAmount('1500', 1000);
        $this->assertNotEquals(null, $result);
    }

    public function testValidatePhoneNumber()
    {
        $controller = new UssdController();
        
       
        $result = $controller->validatePhoneNumber('1234567890', 10);
        $this->assertNull($result);

       
        $result = $controller->validatePhoneNumber('1234', 10);
        $this->assertNotEquals(null, $result);
    }

    public function testValidateAmountToPay()
    {
        $controller = new UssdController();
      
        $result = $controller->validateAmountToPay('500', 1000);
        $this->assertNull($result);

       
        $result = $controller->validateAmountToPay('1500', 1000);
        $this->assertNotEquals(null, $result);
    }
}
