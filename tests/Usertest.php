<?php
namespace App\Tests;
use App\Entity\User;
use App\Entity\Grade;
use App\Entity\Saisie;
use App\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;


class UserLoginTest extends TestCase {

    public function testLogin() {

$user = new User;
$user->setNomUser("Maurice");

$this->assertNotNull($user->getName());

    }

}
?>