<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo, $page)
    {
        $limit = 10;
        $start = $page * $limit - $limit;
        $total = count($repo->findAll());
        $pages = ceil($total / $limit);
        // $repo = $this->getDoctrine()->getRepository(Comment::class);
        


        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findBy([],[], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
        
    }

    /**
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     * 
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, Comment $comment){
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de {$comment->getAuthor()->getFullName()} a bien ete supprimee !"
        );

        return $this->redirectToRoute('admin_comment_index');
         
    }

    
}
