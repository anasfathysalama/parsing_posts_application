<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudFormType;
use App\Repository\CrudRepository;
use App\Services\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CRUDController extends AbstractController
{

    private FileUploader $uploader;

    /**
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @Route("/crud", name="app_crud_index")
     */
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $repository = $managerRegistry->getRepository(Crud::class);
        return $this->render('crud/index.html.twig', [
            'data' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/crud/new", name="app_crud_new" , methods={"GET" , "POST"})
     */
    public function new(Request $request, CrudRepository $repository)
    {
        $crud = new Crud();
        $form = $this->createForm(CrudFormType::class, $crud, ['attr' => ['class' => 'm-3']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = $this->uploader->upload($image);
                $crud->setImage($imageName);
            }
            $repository->add($crud, true);
            $this->addFlash('success', 'created successfully');
            return $this->redirectToRoute("app_crud_new");
        }
        return $this->render('crud/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/crud/edit/{id}", name="app_crud_edit" , methods={"GET" , "POST"})
     */
    public function edit(Request $request, CrudRepository $repository)
    {

        $crud = $repository->find($request->get('id'));
        $form = $this->createForm(CrudFormType::class, $crud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = $this->uploader->upload($image);
                $crud->setImage($imageName);
            }
            $repository->add($crud, true);
            $this->addFlash('success', 'updated successfully');
            return $this->redirectToRoute("app_crud_index");
        }

        return $this->render('crud/edit.html.twig', [
            'form' => $form->createView(),
            'item' => $crud
        ]);
    }


    /**
     * @Route("/crud/show/{id}", name="app_crud_show", methods={"GET"})
     */
    public function show(Request $request, CrudRepository $repository): Response
    {
        $crud = $repository->find($request->get('id'));
        return $this->render('crud/show.html.twig', [
            'item' => $crud,
        ]);
    }


    /**
     * @Route("/crud/{id}", name="app_crud_delete", methods={"POST"})
     */
    public function delete(Request $request, CrudRepository $repository): Response
    {
        $crud = $repository->find($request->get('id'));

        if ($this->isCsrfTokenValid('delete' . $crud->getId(), $request->request->get('_token'))) {
            $repository->remove($crud, true);
        }

        return $this->redirectToRoute('app_crud_index', []);
    }

}
