<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\Cloner\Data;

class PostController extends AbstractController
{

    /**
     * @Route("/post/index", name="posts")
     */
    public function index() {
        return $this->render('post/index.html.twig', [

        ]);
    }

    /**
     * @Route("/post/create", name="post-create")
     */
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = new Posts();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $now = new \DateTime();
            $post->setActive(1);
            $post->setUser($this->getUser());
            $post->setCreatedAt($now);
            $post->setUpdatedAt($now);
            $em->persist($post);
            $this->addFlash('exito', 'Post creado con éxito');
            $em->flush();

            return $this->redirectToRoute('post');
        }

        return $this->render('post/create.html.twig', [
            'controller_name' => 'PostController',
            'formulario' => $form->createView()
        ]);
    }
}
