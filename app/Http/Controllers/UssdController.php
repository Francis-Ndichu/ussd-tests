<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UssdController extends Controller
{
    public function handleMainMenu($input)
    {
        $menuOptions = [
            '1' => 'Borrow Loans',
            '2' => 'Repay Loan',
            '3' => 'Emergency Saving',
            '4' => 'Withdraw cash',
            '0' => 'Exit',
        ];
    
        if (array_key_exists($input, $menuOptions)) {
  
            $selectedOption = $menuOptions[$input];
            $response = "CON {$selectedOption}\n";
    
            if ($input === '1') {
               
                $loanAmount = $this->validateLoanAmount($input, $maximumLoanLimit);
    
                if ($loanAmount === null) {
                    
                    $response .= "Enter Loan Amount:";
                } else {
                
                    $response = "CON $loanAmount";
                }
            }elseif ($input === '2') {
               
                $repayResponse = $this->handleRepayLoan($input, $maximumLoanLimit);
    
                if ($repayResponse === null) {
                
                } else {
                    
                    $response = "CON $repayResponse";
                }
            }
    
         
    
            respond_ok($response);
        } else {
       
            $response = "CON Invalid selection\n";
            foreach ($menuOptions as $key => $option) {
                $response .= "$key. $option\n";
            }
            respond_ok($response);
        }
    }
    public function handleRepayLoan($input, $maximumLoanLimit)
{
    if ($input === '2') {
       
        $response = "CON Amount to Pay:\n";
        $response .= "Enter the amount to pay:";

        $validationResult = $this->validateAmountToPay($input, $maximumLoanLimit);

        if ($validationResult === null) {
          
            $response .= "\nSelect M-Pesa Number:\n";
            $response .= "1. 2547XXXXXXXX\n";
            $response .= "2. Use another number";
        } else {
            
            $response = "CON $validationResult";
        }
    } elseif ($input === '1') {
    
        $response = "CON Enter M-Pesa PIN:\n";
    } elseif ($input === '2') {
   
        $response = "CON Enter phone number:\n";

  
        $validationResult = $this->validatePhoneNumber($input);

        if ($validationResult === null) {
          
            $response .= "Enter M-Pesa PIN:";
        } else {
        
            $response = "CON $validationResult";
        }
    } elseif ($input === 'M-Pesa PIN') {
       
        $response = "CON Do you want to pay Kshs. {$input} to MyWagePay Account No. xxxxxx?\n";
        $response .= "1. Yes\n";
        $response .= "2. No";
    } elseif ($input === '1') {

        $response = "END Thank you for using MyWagePay-Powered App\n";
    } else {
        return "Invalid selection: Please choose option 1 or 2.";
    }

    respond_ok($response);
}

    public function validateLoanAmount($input, $maximumLoanLimit) {
        if (empty($input)) {
            return "USSD must be between 1 and 160 characters. Please try again.";
        }
    

        if (preg_match('/[^0-9]/', $input)) {
            return "Invalid Amount: Please enter a valid numeric amount.";
        }
    
        if (!is_numeric($input)) {
            return "Invalid Amount: Please enter a valid numeric amount.";
        }
    
       
        $loanAmount = floatval($input);
   
        if ($loanAmount > $maximumLoanLimit) {
            return "Invalid Amount: Loan amount exceeds the maximum limit.";
        }
    
       
        return null;
    }
    public function validatePhoneNumber($input, $requiredLength) {
        if (empty($input)) {
            return "USSD must be between 1 and 160 characters. Please try again.";
        }
    
        $phoneNumber = preg_replace('/[^0-9]/', '', $input);
    
        if (strlen($phoneNumber) !== $requiredLength) {
            return "Invalid Phone Number: Phone number must have exactly $requiredLength digits.";
        }
    
   
        return null;
    }
    public function validateAmountToPay($input, $loanAmount) {
        if (empty($input)) {
            return "USSD must be between 1 and 160 characters. Please try again.";
        }
    
        if (preg_match('/[^0-9]/', $input)) {
            return "Invalid Amount: Please enter a valid numeric amount.";
        }
    
        
        $amountToPay = floatval($input);
    
        if ($amountToPay <= 0) {
            return "Invalid Amount: Amount to pay must be greater than 0.";
        }
    
        if ($amountToPay > $loanAmount) {
            return "Invalid Amount: Amount to pay exceeds the loan amount.";
        }

        return null;
    }
        
}
