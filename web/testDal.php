<?php

require_once __DIR__.'/../vendor/autoload.php';

$repository = \HotspotMap\dal\DALFactory::getRepository('User');
$connexion = \HotspotMap\dal\DALFactory::getConnexion();

$connexion->beginTransaction();

/// Insertion
$user = new \hotspotMap\model\User();
$user->setPrivilege(0);
$user->setDisplayName("Display Name");
$user->setMailAddress("good@address.fr");
$repository->save($user);
$id = $user->getUserId();
///
$user = $repository->findOneById($id);

print "User test findOneById : " . $user->getUserId();
print " | " . $user->getMailAddress();
print " | " . $user->getPrivilege();
print " | " . $user->getDisplayName();

$connexion->rollBack();