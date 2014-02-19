<?php

require_once __DIR__.'/../vendor/autoload.php';

$userRepository = \HotspotMap\dal\DALFactory::getRepository('User');
$placeRepository = \HotspotMap\dal\DALFactory::getRepository('Place');
$commentRepository = \HotspotMap\dal\DALFactory::getRepository('Comment');
$connexion = \HotspotMap\dal\DALFactory::getConnexion();

$connexion->beginTransaction();

$user = new \hotspotMap\model\User();
$user->setPrivilege(0);
$user->setDisplayName("Display Name");
$user->setMailAddress("good@address.fr");
$userRepository->save($user);
$id = $user->getUserId();

$user = $userRepository->findOneById($id);

print "User test findOneById : " . $user->getUserId();
print " | " . $user->getMailAddress();
print " | " . $user->getPrivilege();
print " | " . $user->getDisplayName();
print "</br></br>";

$place = new \HotspotMap\model\Place();
$place->setName("Starbucks");
$place->setLatitude(2.29791);
$place->setLongitude(48.84951);
$place->setSchedules("07:30 – 21:00");//\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00");
$place->setDescription("Good Starbuks with Wifi");
$place->setCoffee(true);
$place->setInternetAccess(true);
$place->setPlacesNumber(100);
$place->setComfort(4);
$place->setFrequenting(4);
$place->setVisitNumber(0);
$place->setSubmissionDate(new \DateTime());
$place->setValidate(0);
$placeRepository->save($place);
$id = $place->getPlaceId();

$place = $placeRepository->findOneById($id);

print "Place test findOneById : " . $place->getPlaceId();
print " | " . $place->getName();
print " | " . $place->getLatitude();
print " | " . $place->getLongitude();
print " | " . $place->getSchedules();
print " | " . $place->getDescription();
print " | " . $place->getCoffee();
print " | " . $place->getInternetAccess();
print " | " . $place->getPlacesNumber();
print " | " . $place->getComfort();
print " | " . $place->getFrequenting();
print " | " . $place->getVisitNumber();
print " | " . $place->getSubmissionDate()->format('d/m/Y');
print " | " . $place->getValidate();
print "</br></br>";


$place = new \HotspotMap\model\Place();
$place->setName("Starbucks 2");
$place->setLatitude(2.29791);
$place->setLongitude(48.84951);
$place->setSchedules("07:30 – 21:00");//\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00");
$place->setDescription("Good Starbuks with Wifi");
$place->setCoffee(true);
$place->setInternetAccess(true);
$place->setPlacesNumber(100);
$place->setComfort(4);
$place->setFrequenting(4);
$place->setVisitNumber(0);
$place->setSubmissionDate(new \DateTime());
$place->setValidate(0);
$placeRepository->save($place);
$id = $place->getPlaceId();

$places = $placeRepository->findAllNotValidated();
print "Place test findAllNotValidated : </br>";
for( $i = 0 ; $i < sizeof($places) ; ++$i )
{
    $place = $places[$i];
    print $place->getPlaceId();
    print " | " . $place->getName();
    print " | " . $place->getLatitude();
    print " | " . $place->getLongitude();
    print " | " . $place->getSchedules();
    print " | " . $place->getDescription();
    print " | " . $place->getCoffee();
    print " | " . $place->getInternetAccess();
    print " | " . $place->getPlacesNumber();
    print " | " . $place->getComfort();
    print " | " . $place->getFrequenting();
    print " | " . $place->getVisitNumber();
    print " | " . $place->getSubmissionDate()->format('d/m/Y');
    print " | " . $place->getValidate();
    print "</br>";
}

$comment = new \HotspotMap\model\Comment();
$comment->setContent("My content");
$comment->setPlace($place);
$comment->setUser($user);
$commentRepository->save($comment);
$id = $comment->getCommentId();

$comment = $commentRepository->findOneById($id);

print "Comment test findOneById : " . $comment->getCommentId();
print " | " . $comment->getContent();
print " | " . $comment->getPlace()->getName();
print " | " . ((null != $comment->getUser()) ? $comment->getUser()->getDisplayName() : $comment->getAuthorDisplayName());
print "</br></br>";

$connexion->rollBack();