<?php


namespace App\Controller;


use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Utils\Markdown;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="page_index")
     */
    public function index(PageRepository $pages)
    {
        $titles = $pages->findAllTitles();

        return $this->render('page/index.html.twig', ['pages' => $titles]);
    }

    /**
     * @Route("/page/create", methods={"GET", "POST"}, name="page_create")
     */
    public function create(Request $request): Response
    {
        $page = new Page();

        $form = $this->createForm(PageType::class, $page)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $this->addFlash('success', 'Created successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('page_create');
            }

            return $this->redirectToRoute('page_index');
        }

        return $this->render('page/create.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/page/{id<\d+>}/edit",methods={"GET", "POST"}, name="page_edit")
     */
    public function edit(Request $request, Page $page): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Updated successfully');
            return $this->redirectToRoute('page_edit', ['id' => $page->getId()]);
        }

        return $this->render('page/edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/page/{id<\d+>}", methods={"GET"}, name="page_view")
     */
    public function view(Page $page)
    {
        $content = $page->getContent();
        $content = Markdown::convert($content);
        $page->setContent($content);

        return $this->render('page/view.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/page/{id}/delete", methods={"GET"}, name="page_delete")
     */
    public function delete(Page $page): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();

        $this->addFlash('success', 'Deleted successfully');

        return $this->redirectToRoute('page_index');
    }

}