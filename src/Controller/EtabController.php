<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\TypeForm;
use App\Repository\MaisonRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

class EtabController extends AbstractController
{
    #[Route('/', name: 'app_etab')]
    public function index(MaisonRepository $maisonRepository, Request $request): Response
    {
        $allEtab = $maisonRepository->findAll();

        $form = $this->createForm(TypeForm::class);

        $search = $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){

            $allEtab = $maisonRepository->search($search->get('chercher')->getData(),
                $search->get('choix')->getData());
        }
        return $this->render('etab/index.html.twig', [
            'etabs' => $allEtab,
            'form' => $form->createView()
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/detail/{slug}', name: 'detail')]
    public function detail($slug, MaisonRepository $maisonRepository, Request $request, MailerInterface $mailer): Response
    {
        $detail = $maisonRepository->findBy(['slug' => $slug]);

        $emailEtab = $detail[0]->getEmail();
        $etab = $detail[0]->getName();

        $formContact = $this->createForm(ContactType::class);

        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {

            $firstName = $formContact->get('firstName')->getData();
            $name = $formContact->get('name')->getData();
            $emailContact = $formContact->get('email')->getData();
            $sujet = $formContact->get('sujet')->getData();
            $message = $formContact->get('message')->getData();

            $email = (new TemplatedEmail())
                ->from($emailContact)
                ->to(new Address($emailEtab, $etab))
                ->subject($sujet)
                ->context([
                    'firstName' => $firstName,
                    'name' => $name,
                    'mail' => $emailContact,
                    'sujet' => $sujet,
                    'contenu' => $message
                ])
                ->htmlTemplate('formulaire/contact.html.twig');
            $mailer->send($email);

            $email2 = (new TemplatedEmail())
                ->from(new Address($emailEtab, $etab))
                ->to($emailContact)
                ->subject('Réponse à votre message de contact')
                ->context([
                    'firstName' => $firstName,
                    'name' => $name,
                    'etab' => $etab,
                ])
                ->htmlTemplate('formulaire/reponseContact.html.twig');
            $mailer->send($email2);
        }
        return $this->render('etab/detail.html.twig', [
            'details' => $detail,
            'formContact' => $formContact

        ]);
    }
}
