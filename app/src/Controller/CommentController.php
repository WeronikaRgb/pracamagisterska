<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Service\CommentServiceInterface;
use App\Service\PostServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Comment service.
     */
    private CommentServiceInterface $commentService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Post service.
     */
    private PostServiceInterface $postService;

    /**
     * Constructor.
     *
     * @param CommentServiceInterface $commentService Comment service
     * @param TranslatorInterface     $translator     Translator
     * @param PostServiceInterface    $postService  Post service
     */
    public function __construct(CommentServiceInterface $commentService, TranslatorInterface $translator, PostServiceInterface $postService)
    {
        $this->commentService = $commentService;
        $this->translator = $translator;
        $this->postService = $postService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'comment_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->commentService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('comment/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'comment_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Comment $comment): Response
    {
        return $this->render(
            'comment/show.html.twig',
            ['comment' => $comment]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/create', name: 'comment_create', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        $post = $this->postService->getById($request->get('id'));
        if (null === $post) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.post_not_found')
            );

            return $this->redirectToRoute('post_index');
        }

        /** @var User $user */
        $user = $this->getUser();
        $comment = new Comment();
        $comment->setWriter($user);
        $postId = $this->postService->getById($request->get('id'))->getId();
        $form = $this->createForm(CommentType::class, $comment, ['action' => $this->generateUrl('comment_create', ['id' => $postId])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $this->postService->getById($request->get('id'));
            if (null === $post) {
                $this->addFlash(
                    'warning',
                    $this->translator->trans('message.post_not_found')
                );

                return $this->redirectToRoute('post_index');
            }

            $post->addComment($comment);

            $comment->setPost($post);
            $this->commentService->save($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            $postId = $post->getId();

            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        $postId = $this->postService->getById($request->get('id'))->getId();

        return $this->render(
            'comment/create.html.twig',
            [
                'form' => $form->createView(),
                'id' => $postId,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'comment_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(FormType::class, $comment, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->delete($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            $postId = $comment->getPost()->getId();

            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
}
