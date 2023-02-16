<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="app_post_index")
     */
    public function index(ManagerRegistry $managerRegistry , PaginatorInterface $paginator ,Request $request): Response
    {
        $posts = $managerRegistry->getRepository(Post::class)->findAll();
        $posts = $paginator->paginate(
            $posts, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );

        return $this->render('posts/index.html.twig', [
            'posts' => $posts
        ]);
    }


    /**
     * @Route("/posts/{id}", name="app_post_delete", methods={"POST"})
     */
    public function delete(Request $request, PostRepository $repository): Response
    {
        $post = $repository->find($request->get('id'));
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $repository->remove($post, true);
        }
        return $this->redirectToRoute('app_post_index', []);
    }
}
