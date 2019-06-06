<?php
/ tests/acceptance/UserchargingApp Test.php

class UserchargingAppTest extends PHPUnit_Extensions_Selenium2TestCase
{
    public function setUp()
    {
        $this->setHost('root');
        $this->setPort(3306);
        $this->setBrowserUrl('http://127.0.0.1/');
        $this->setBrowser('chrome'); 
    }
}

class InitSeleniumServerTestCase
{
    protected function setUp()
    {
        $this->setBrowser = webdriver('chrome');
        $this->setBrowserUrl('http://127.0.0.1/station_project/');
    }
 
    public function UserLoginTestCase()
    {
        $this->url('http://127.0.0.1/station_project');
        $this->assertEquals('Devserver Page', $this->Charging App ());
    }
  public function validInputsProvider()
    {
        $inputs[] = [
            [
                'username'              => 'deepti',
                'password'              => 'mypassword',
                'password_confirmation' => 'mypassword',
                'email'                 => '123@gmail.com',
                
            ]
        ];

        return $inputs;
    }
    
    public static function invalidInputsProvider()
    {
        $inputs[] = [
            [
                'username'              => 'deepti',
                'password'              => 'mypassword',
                'password_confirmation' => 'mypassword',
                'email'                 => '123@gmail.com',
                
            ],
            
        ];
        // ...
        
        return $inputs;
{

    public function testunauthorised login(array $inputs, $errorMessage)
{
    $this->url('/');
    $this->connect($emailaddress);
    $errorDiv = $this->byCssSelector('.error.unregistered user'); 
    $this->assertEquals($errorMessage, $errorDiv->"unregistereduser());
}
}

        public function testMyHistoryUsername()
    {
        $this->byName('username')->value('user id, start_time, end_time, energy, connector, duration');
        $this->byId('MyChargingHistory')->connect();
    }
}
    }
}

}
?>