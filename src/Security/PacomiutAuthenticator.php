<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class PacomiutAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        // Dans le cas ou on est entrain de développer
        if ((getenv('APP_ENV') != 'prod') && (getenv('APP_WIFI') != 'eduroam')) {
            // On vérifie si on a déjà crée un utilisateur de dev, si il existe déjà, pas besoin de le recréer
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'dev@dev.fr']);
            // Si l'utilisateur de dev n'existe pas, on le crée
            if (!$user) {
                $user = new User();
                $user->setUsername($credentials['username']);
                $user->setEmail('dev@dev.fr');
                $user->setFirstname('dev');
                $user->setSurname('dev');
                $user->setPassword((new BCryptPasswordEncoder(15))->encodePassword($credentials['password'], true));
                $user->setRoles(['ROLE_ADMIN']);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }

            return $user;
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);

        if (!$user && getenv('APP_WIFI') == 'eduroam') {
            // Création de la connection LDAP sur le serveur de l'université
            $ldap_con = ldap_connect('ldap.univ-pau.fr');
            // Paramétrage de LDAP, uid correspond à l'utilisateur que l'on veut connecté
            $ldap_dn = 'uid=' . $credentials['username'] . ',OU=people,DC=univ-pau,DC=fr';
            try {
                // On tente de connecter l'utilisateur
                ldap_bind($ldap_con, $ldap_dn, $credentials['password']);
            } catch (\Exception $e) {
                throw new CustomUserMessageAuthenticationException('Le nom d\'utilisateur que vous avez saisi ne correspond à aucun compte.');
            }
            // On récupère les données de l'utilisateur
            $result = ldap_search($ldap_con, $ldap_dn, '(cn=*)') or die('Error in search query: ' . ldap_error($ldapconn));
            $data = ldap_get_entries($ldap_con, $result);
            foreach ($data[0]['uppamaillocaladdress'] as $email) {
                if (strpos($email, 'iutbayonne') !== false) {
                    $user = new User();
                    $user->setUsername($credentials['username']);
                    $user->setEmail($data[0]['uppamaillocaladdress'][1]);
                    $user->setFirstname($data[0]['givenname'][0]);
                    $user->setSurname($data[0]['sn'][0]);
                    $user->setPassword((new BCryptPasswordEncoder(15))->encodePassword($credentials['password'], true));
                    // Si le profil de l'utilisateur est un enseignant, alors nous lui donnons le role de professeur
                    if ($data[0]['uppaprofil'][0] == 'PER') {
                        $user->setRoles(['ROLE_PROF']);
                    } else {
                        $user->setRoles(['']);
                    }

                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    return $user;
                }
            }
            throw new CustomUserMessageAuthenticationException('Le nom d\'utilisateur que vous avez saisi ne correspond à aucun compte.');
        }

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Le nom d\'utilisateur que vous avez saisi ne correspond à aucun compte.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Dans le cas ou nous sommes en local
        if ((getenv('APP_ENV') != 'prod') && (getenv('APP_WIFI') != 'eduroam')) {
            return true;
        }

        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
