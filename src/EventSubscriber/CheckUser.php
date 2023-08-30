<?php
namespace App\EventSubscriber;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CheckUser implements EventSubscriberInterface
{
/*
 * Cette class (qui implement un écouteur d'event) permet de check si l'utilisateur a vérif son email.
 * Elle est appelée lorsque l'événement CheckPassportEvent est déclenché (lors d'une tentative d'authentification).
 */
    #[NoReturn]
    public function checkUser(CheckPassportEvent $event):void {
        if (!$event->getPassport()->getUser()->isVerified()) {
            throw new CustomUserMessageAuthenticationException(
                'Email non vérifié'
            );
        }
    }
/*
*  Le nombre -1 indique la priorité de l'écouteur lorsqu'il y a plusieurs écouteurs pour le même événement.
*  Plus le nombre est bas, plus l'écouteur sera prioritaire.
*  -1 est une priorité assez élevée, ce qui signifie que cet écouteur sera exécuté tôt dans le processus.
*/
    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['checkUser', -1]
        ];
    }
}