<?php
/**
 * Created by PhpStorm.
 * User: darrell.breeden
 * Date: 4/26/2017
 * Time: 10:54 AM
 */

namespace Mrpvision\Gluu\Models;

/**
 * Class GroupMember
 * @package shairozan\sciph
 * @property string $value UID / GUID of foreign object
 * @property string $ref URL for the details of the REST object
 * @property User $user The Actual User object associated with this Member
 */

class GroupMember
{
    public $value;
    public $ref;
    public $display;
    public $user;
}