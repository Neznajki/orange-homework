<?php


namespace App\Service;


use JsonRpcAuthorizationBundle\Contract\CredentialsInterface;
use JsonRpcAuthorizationBundle\Contract\CredentialsReceiverInterface;
use JsonRpcAuthorizationBundle\Exception\AuthNotGrantedException;
use JsonRpcAuthorizationBundle\Object\Credentials;
use JsonRpcServerCommon\Contract\PasswordEncryptInterface;
use Symfony\Component\HttpFoundation\Request;

class SimpleCredentialsReceiverService implements CredentialsReceiverInterface
{
    /** @var PasswordEncryptInterface */
    protected $encoder;

    /**
     * SimpleCredentialsReceiverService constructor.
     * @param PasswordEncryptInterface $encoder
     */
    public function __construct(PasswordEncryptInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getCredentials(Request $request): CredentialsInterface
    {
        $userName = $request->headers->get('userName');
        if ($userName === null) {
            throw new AuthNotGrantedException('userName is required');
        }
        $password = $request->headers->get('password');
        if ($password === null) {
            throw new AuthNotGrantedException('password is required');
        }

        $generationTime = time();

        return new Credentials($userName, $this->encoder->encryptPassword($password, $generationTime), $generationTime);
    }
}