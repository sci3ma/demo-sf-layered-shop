<?php

declare(strict_types=1);

namespace App\Application\User\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class LoginFormAuthenticatorTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;
    /**
     * @var MockObject|UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var CsrfTokenManagerInterface|MockObject
     */
    private $csrfTokenManager;
    /**
     * @var MockObject|UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
    }

    /**
     * @dataProvider provideSupportsData
     */
    public function testSupports(string $routeReturn, bool $methodReturn, bool $expectedResult): void
    {
        $attributes = $this->createMock(ParameterBagInterface::class);
        $attributes
            ->expects(self::once())
            ->method('get')
            ->with('_route')
            ->willReturn($routeReturn);

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('isMethod')
            ->with(Request::METHOD_POST)
            ->willReturn($methodReturn);
        $request->attributes = $attributes;

        $authenticator = new LoginFormAuthenticator(
            $this->entityManager,
            $this->urlGenerator,
            $this->csrfTokenManager,
            $this->passwordEncoder,
        );
        $result = $authenticator->supports($request);

        self::assertEquals($expectedResult, $result);
    }

    public function provideSupportsData(): array
    {
        return [
            ['security_login', true, true],
            ['security_login', false, false],
        ];
    }

    public function testGetCredentials(): void
    {
        $email = 'test@mail.com';
        $password = 'password';
        $csrfToken = 'token';

        $session = $this->createMock(SessionInterface::class);
        $session
            ->expects(self::once())
            ->method('set')
            ->with(Security::LAST_USERNAME, $email);

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('getSession')
            ->willReturn($session);

        $subRequest = $this->createMock(InputBag::class);
        $subRequest
            ->expects(self::exactly(4))
            ->method('get')
            ->with(self::logicalOr(
                self::equalTo('email'),
                self::equalTo('password'),
                self::equalTo('_csrf_token')
            ))
            ->will(self::returnCallback([$this, 'requestCallback']));

        $request->request = $subRequest;

        $authenticator = new LoginFormAuthenticator(
            $this->entityManager,
            $this->urlGenerator,
            $this->csrfTokenManager,
            $this->passwordEncoder,
        );
        $result = $authenticator->getCredentials($request);

        $expectedResult = [
            'email' => $email,
            'password' => $password,
            'csrf_token' => $csrfToken,
        ];

        self::assertEquals($expectedResult, $result);
    }

    public function requestCallback(string $key): string
    {
        $result = '';

        switch ($key) {
            case 'email':
                $result = 'test@mail.com';

                break;

            case 'password':
                $result = 'password';

                break;

            case '_csrf_token':
                $result = 'token';

                break;
        }

        return $result;
    }

    public function testGetUser(): void
    {
        $credentials = [
            'email' => 'test@mail.com',
            'csrf_token' => 'token',
        ];

        $token = new CsrfToken('authenticate', $credentials['csrf_token']);

        $this->csrfTokenManager
            ->expects(self::once())
            ->method('isTokenValid')
            ->with($token)
            ->willReturn(true);

        $user = $this->createMock(UserInterface::class);

        $userProvider = $this->createMock(UserProviderInterface::class);
        $userProvider
            ->expects(self::once())
            ->method('loadUserByUsername')
            ->with('test@mail.com')
            ->willReturn($user);

        $authenticator = new LoginFormAuthenticator(
            $this->entityManager,
            $this->urlGenerator,
            $this->csrfTokenManager,
            $this->passwordEncoder,
        );
        $result = $authenticator->getUser($credentials, $userProvider);

        self::assertInstanceOf(UserInterface::class, $result);
    }

    public function testGetUserInvalidCsrfToken(): void
    {
        $credentials = [
            'csrf_token' => 'token',
        ];

        $token = new CsrfToken('authenticate', $credentials['csrf_token']);

        $this->csrfTokenManager
            ->expects(self::once())
            ->method('isTokenValid')
            ->with($token)
            ->willReturn(false);

        $userProvider = $this->createMock(UserProviderInterface::class);

        $authenticator = new LoginFormAuthenticator(
            $this->entityManager,
            $this->urlGenerator,
            $this->csrfTokenManager,
            $this->passwordEncoder,
        );

        self::expectException(InvalidCsrfTokenException::class);
        $authenticator->getUser($credentials, $userProvider);
    }

    public function testGetUserNotFound(): void
    {
        $credentials = [
            'email' => 'test@mail.com',
            'csrf_token' => 'token',
        ];

        $token = new CsrfToken('authenticate', $credentials['csrf_token']);

        $this->csrfTokenManager
            ->expects(self::once())
            ->method('isTokenValid')
            ->with($token)
            ->willReturn(true);

        $userProvider = $this->createMock(UserProviderInterface::class);
        $userProvider
            ->expects(self::once())
            ->method('loadUserByUsername')
            ->with('test@mail.com')
            ->willReturn(null);

        $authenticator = new LoginFormAuthenticator(
            $this->entityManager,
            $this->urlGenerator,
            $this->csrfTokenManager,
            $this->passwordEncoder,
        );

        self::expectException(CustomUserMessageAuthenticationException::class);
        $authenticator->getUser($credentials, $userProvider);
    }

    public function testCheckCredentials(): void
    {
        $credentials = [
            'password' => 'password',
        ];

        $user = $this->createMock(UserInterface::class);

        $this->passwordEncoder
            ->expects(self::once())
            ->method('isPasswordValid')
            ->with($user, $credentials['password'])
            ->willReturn(true);

        $authenticator = new LoginFormAuthenticator(
            $this->entityManager,
            $this->urlGenerator,
            $this->csrfTokenManager,
            $this->passwordEncoder,
        );
        $result = $authenticator->checkCredentials($credentials, $user);

        self::assertTrue($result);
    }

    public function testOnAuthenticationSuccess(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('getSession')
            ->willReturn($session);

        $token = $this->createMock(TokenInterface::class);

        $providerKey = 'string';

        $this->urlGenerator
            ->expects(self::once())
            ->method('generate')
            ->with('product_add')
            ->willReturn('string');

        $authenticator = new LoginFormAuthenticator(
            $this->entityManager,
            $this->urlGenerator,
            $this->csrfTokenManager,
            $this->passwordEncoder,
        );
        $result = $authenticator->onAuthenticationSuccess($request, $token, $providerKey);

        self::assertInstanceOf(RedirectResponse::class, $result);
    }
}
