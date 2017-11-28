<?php

use Mrpvision\Gluu\GluuClient;

$oidc = new GluuClient('https://gluu.xxxxxxx.com', '@!XXXXX.XXXX.XXXX.XXX!0001!D716.B0F4!XXXX!201F.XXXX.XXXX.9E7D', 'XXXXXXX');
$oidc->setVerifyPeer(false);
$oidc->setHttpProxy('192.168.0.250:8080');
$oidc->providerConfigParam([
    'token_endpoint' => 'oxauth/restv1/token',
    'user_endpoint' => 'identity/restv1/scim/v2/Users',
    'group_endpoint' => 'identity/restv1/scim/v2/Groups'
]);

/*
 * Get User By ID:
 */
$user = $oidc->getUser('@!48E2.A33E.73FF.7A79!0001!D716.B0F4!0000!A477.9223.C06B.473B');

/*
 * Get all Users
 */
$users = $oidc->getUser();

/*
 * Create new user
 */

$user = new Mrpvision\Gluu\Models\User();
$schemas[] = Mrpvision\Gluu\Models\Constant::USER_SCHEMA;
$schemas[] = Mrpvision\Gluu\Models\Constant::USER_EXTENSION_SCHEMA;
$user->schemas = $schemas;
$user->externalId = 11012;
$email = new Mrpvision\Gluu\Models\Email();
$email->primary = true;
$email->value = 'nnnn.zzzz@gmail.com';
$email->type = 'other';
$emails[] = $email;
$user->emails = $emails;
$user->displayName = 'Aurther M Auth';
$user->userName = 'user.name';
$user->nickName = 'Kishor';
$name = new \Mrpvision\Gluu\Models\Name();
$name->familyName = 'Surname';
$name->givenName = 'Firstname';
$name->formatted = 'Full Name';
$name->middleName = 'Middle name';
$user->name = $name;
$group['value'] = '@!XXXX.XXXX.XXXX.XXXX!0001!D716.B0F4!0003!9B7B.XXXX';
$groups[] = $group;
$user->groups = $groups;
$extensionGluuUser = new \Mrpvision\Gluu\Models\ExtensionGluuUser();
$extensionGluuUser->kronoscustomattribute = 'kronos_username';
$user->extensionGluuUser = $extensionGluuUser;
$user->password = 'Password';
$user->preferredLanguage = "en-us";
$user->locale = "en_US";
$user->active = true;
$response = $oidc->CreateUser($user);

?>