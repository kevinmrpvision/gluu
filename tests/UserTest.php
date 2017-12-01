<?php


namespace Mrpvision\Gluu\Tests;

use Mrpvision\Gluu\Exception\SessionException;
use PHPUnit\Framework\TestCase;
use Mrpvision\Gluu\Models\User;
use Mrpvision\Gluu\Models\Collection;
class UserTest extends TestCase
{
    /** @var array */

    private $user;
    private $collection;
    private $user_json;
    private $collection_json;
	
   protected function setUp()
    {
       $this->user_json = file_get_contents(__DIR__.'/user.json');
       $this->collection_json = file_get_contents(__DIR__.'/users.json');
       $this->user = User::fromJson($this->user_json);
       $this->collection = Collection::fromJson($this->collection_json);
    }

    public function testUserFromJson(){
        $this->assertInstanceOf(User::class,$this->user);
    }
    
    public function testCollectionFromJson(){
        $this->assertInstanceOf(Collection::class,$this->collection);
    }
    /*
     * @depends testUserFromJson 
     */
    public function testUserJson(){
        $this->assertNotEmpty($this->user->json());
    }
     /*
     * @depends testCollectionFromJson 
     */
    public function testCollectionUsers(){
        foreach($this->collection->resources as $user){
            $this->assertInstanceOf(User::class, $user);
        }
    }
    
    /*
     * @depends testUserFromJson 
     */
    public function testUserEmail(){
        foreach($this->user->emails as $email){
            $this->assertInstanceOf(\Mrpvision\Gluu\Models\Email::class, $email);
        }
    }
    
    /*
     * @depends testUserFromJson 
     */
    public function testUserGroup(){
        foreach($this->user->groups as $group){
            $this->assertInstanceOf(\Mrpvision\Gluu\Models\Group::class, $group);
        }
    }
    

}
