<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ODM\MongoDB\DocumentRepository;

class AuthTokenUserProvider implements UserProviderInterface
{
    protected $authTokenRepository;
    protected $userRepository;

    public function __construct(DocumentRepository $authTokenRepository, DocumentRepository $userRepository)
    {
        $this->authTokenRepository = $authTokenRepository;
        $this->userRepository = $userRepository;
    }

    public function getAuthToken($authTokenHeader)
    {

        $authToken =  $this->authTokenRepository->findOneByValue($authTokenHeader);
        if($authToken){
          $user = $this->userRepository->findOneById($authToken->getUserId());
          $authToken->user = $user;
        }

        return $authToken;
    }

    public function loadUserByUsername($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'AppBundle\Document\User' === $class;
    }
}
