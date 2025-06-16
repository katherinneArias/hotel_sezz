<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/mes-reservations', name: 'app_reservation_client', methods: ['GET'])]
    public function mesReservations(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $user = $this->getUser();

        return $this->render('reservation/client_index.html.twig', [
            'reservations' => $user->getReservations(),
        ]);
    }

    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUtilisateur($this->getUser());

            $dateDebut = $reservation->getDateDebut();
            $dateFin = $reservation->getDateFin();
            $chambre = $reservation->getChambre();

            if ($dateDebut && $dateFin && $chambre) {
                $interval = $dateDebut->diff($dateFin)->days;
                $prixTotal = $interval * $chambre->getPrix();
                $reservation->setPrixTotal($prixTotal);
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation enregistrée avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/voir/{id}', name: 'app_reservation_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        $user = $this->getUser();

        if (
            ($this->isGranted('ROLE_CLIENT') && $reservation->getUtilisateur() !== $user) ||
            (!$this->isGranted('ROLE_CLIENT') && !$this->isGranted('ROLE_ADMIN'))
        ) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (
            ($this->isGranted('ROLE_CLIENT') && $reservation->getUtilisateur() !== $user) ||
            (!$this->isGranted('ROLE_CLIENT') && !$this->isGranted('ROLE_ADMIN'))
        ) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_client');
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_reservation_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (
            ($this->isGranted('ROLE_CLIENT') && $reservation->getUtilisateur() !== $user) ||
            (!$this->isGranted('ROLE_CLIENT') && !$this->isGranted('ROLE_ADMIN'))
        ) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation supprimée.');
        }

        return $this->redirectToRoute('app_reservation_client');
    }
}
