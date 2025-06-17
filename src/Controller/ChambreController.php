<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Photo;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/chambre')]
class ChambreController extends AbstractController
{
    #[Route('/', name: 'app_chambre_index', methods: ['GET'])]
    public function index(ChambreRepository $chambreRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('chambre/index.html.twig', [
                'chambres' => $chambreRepository->findAll(),
            ]);
        }

        // Vue publique
        return $this->render('chambre/public_index.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    #[Route('/voir/{id}', name: 'app_chambre_show', methods: ['GET'])]
    public function show(Chambre $chambre): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('chambre/admin_show.html.twig', [
                'chambre' => $chambre,
            ]);
        }

        return $this->render('chambre/show.html.twig', [
            'chambre' => $chambre,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_chambre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFiles = $form->get('photos')->getData();

            foreach ($photoFiles as $photoFile) {
                if ($photoFile instanceof UploadedFile) {
                    $filename = uniqid() . '.' . $photoFile->guessExtension();
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $filename
                    );

                    $photo = new Photo();
                    $photo->setUrl($filename);
                    $photo->setChambre($chambre);
                    $entityManager->persist($photo);
                }
            }

            $entityManager->persist($chambre);
            $entityManager->flush();

            $this->addFlash('success', 'Chambre créée avec succès.');
            return $this->redirectToRoute('app_chambre_index');
        }

        return $this->renderForm('chambre/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_chambre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFiles = $form->get('photos')->getData();

            foreach ($photoFiles as $photoFile) {
                if ($photoFile instanceof UploadedFile) {
                    $filename = uniqid() . '.' . $photoFile->guessExtension();
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $filename
                    );

                    $photo = new Photo();
                    $photo->setUrl($filename);
                    $photo->setChambre($chambre);
                    $entityManager->persist($photo);
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Chambre mise à jour.');
            return $this->redirectToRoute('app_chambre_index');
        }

        return $this->renderForm('chambre/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_chambre_delete', methods: ['POST'])]
    public function delete(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chambre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chambre);
            $entityManager->flush();
            $this->addFlash('danger', 'Chambre supprimée.');
        }

        return $this->redirectToRoute('app_chambre_index');
    }
}
