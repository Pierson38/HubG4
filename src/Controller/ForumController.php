<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\PostsCategories;
use App\Entity\PostsComments;
use App\Form\CategoryPostType;
use App\Form\CreatePostType;
use App\Form\PostCommentType;
use App\Form\ReportPostType;
use App\Repository\PostsCategoriesRepository;
use App\Repository\PostsCommentsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    private function getUserFromInterface()
    {
        return $this->userRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
    }


    #[Route('/forum', name: 'app_forum')]
    public function index(PostsCategoriesRepository $postsCategoriesRepository): Response
    {
        $categories = $postsCategoriesRepository->getAllBaseCategories();
        return $this->render('forum/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/forum/{id}', name: 'app_forum_category')]
    public function indexCategory(PostsCategories $postsCategories): Response
    {
        return $this->render('forum/indexCategory.html.twig', [
            'postsCategories' => $postsCategories,
        ]);
    }

    #[Route('/forum/category/create/{id}', name: 'app_forum_create_category')]
    public function createCategory(PostsCategories $postsCategories, Request $request, EntityManagerInterface $manager): Response
    {
        $postsCategory = new PostsCategories();
        $postsCategory->setCategoryParent($postsCategories);


        $form = $this->createForm(CategoryPostType::class, $postsCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postsCategory = $form->getData();

            $manager->persist($postsCategory);
            $manager->flush();

            $this->addFlash('success', 'Votre categorie a bien été créé');
            return $this->redirectToRoute('app_forum_category', ['id' => $postsCategories->getId()]);
        }


        return $this->render('forum/createCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/forum/post/{id}', name: 'app_forum_single_post')]
    public function postSingle(Posts $post, Request $request, EntityManagerInterface $manager, PostsCommentsRepository $postsCommentsRepository): Response
    {

        $commentsForm = $this->createForm(PostCommentType::class);
        $commentsForm->handleRequest($request);

        if ($commentsForm->isSubmitted() && $commentsForm->isValid()) {
            $comment = $commentsForm->getData();
            $comment->setPost($post);
            $comment->setCreatedBy($this->getUserFromInterface());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté');
            return $this->redirectToRoute('app_forum_single_post', ['id' => $post->getId()]);
        }

        $reportForm = $this->createForm(ReportPostType::class);
        $reportForm->handleRequest($request);

        if ($reportForm->isSubmitted() && $reportForm->isValid()) {
            $report = $reportForm->getData();
            $report->setPost($post);
            $report->setReportedBy($this->getUserFromInterface());
            if ($request->request->all()['report_post']["postType"] === 'comment') {
                $report->setPostComment($postsCommentsRepository->find($request->request->all()['report_post']["id"]));
            }

            $manager->persist($report);
            $manager->flush();

            $this->addFlash('success', 'Votre signalement a bien été envoyé');
            return $this->redirectToRoute('app_forum_single_post', ['id' => $post->getId()]);
        }



        return $this->render('forum/singlePost.html.twig', [
            'post' => $post,
            'commentsForm' => $commentsForm->createView(),
            'reportForm' => $reportForm->createView(),
        ]);
    }

    #[Route('/forum/post/create/{id}', name: 'app_forum_create_post')]
    public function createPost(PostsCategories $postsCategories, Request $request, EntityManagerInterface $manager): Response
    {
        $post = new Posts();
        $post->setCategory($postsCategories);

        $form = $this->createForm(CreatePostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreatedBy($this->getUserFromInterface());

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Votre post a bien été créé');
            return $this->redirectToRoute('app_forum_category', ['id' => $postsCategories->getId()]);
        }


        return $this->render('forum/createPost.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/forum/post/edit/{id}', name: 'app_forum_edit_post')]
    public function editPost(Posts $post, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(CreatePostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Votre post a bien été modifié');
            return $this->redirectToRoute('app_forum_single_post', ['id' => $post->getId()]);
        }


        return $this->render('forum/editPost.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }


    #[Route('/forum/post/delete/{id}', name: 'app_forum_delete_post')]
    public function deletePost(Posts $post, EntityManagerInterface $manager): Response
    {

        $manager->remove($post);
        $manager->flush();

        $this->addFlash('success', 'Votre post a bien été supprimé');
        return $this->redirectToRoute('app_forum_category', ['id' => $post->getCategory()->getId()]);
    }

    #[Route('/forum/comments/delete/{id}', name: 'app_forum_delete_comments')]
    public function deleteComments(PostsComments $comment, EntityManagerInterface $manager): Response
    {

        $manager->remove($comment);
        $manager->flush();


        $this->addFlash('success', 'Votre commentaire a bien été supprimé');

        return $this->redirectToRoute('app_forum_single_post', ['id' => $comment->getPost()->getId()]);
    }
}
