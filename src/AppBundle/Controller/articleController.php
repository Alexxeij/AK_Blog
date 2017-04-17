<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;

class articleController extends Controller
{

    /**
     * article list page
     *
     * @Route("/article", name="article_page")
     * @Template()
     */
    public function indexAction()
    {
        $repo=$this->get('doctrine')->getRepository('AppBundle:Article');
        $article=$repo->findAll();
        return compact('article');
    }
    /**
     * single article page - by id
     * {sl} - for trailing slash if its needed
     *
     * @Route("/article/{id}{sl}", name="article_one_page", defaults={"sl":""}, requirements={"id" : "[1-9][0-9]*","sl" : "/?"})
     * @Template()
     */
    public function showAction($id)
    {
        $article=$this->get('doctrine')->getRepository('AppBundle:Article')->find($id);

        if(!$article){
            throw $this->createNotFoundException('Article not found');
        }
        return ['article'=>$article];
    }

    /**
     * article edit page
     *
     * @Route("/article/edit/{id}", name="article_edit",requirements={"id" : "[1-9][0-9]*","sl" : "/?"})
     * @Template()
     * @param $id
     * @return array
     */
    public function editAction(Request $request)
    {
        $id=$request->get('id');
        $doctrine=$this->get('doctrine');
        $article=$doctrine->getRepository('AppBundle:Article')->find($id);
        $form = $this->createForm(ArticleType::class,$article);

        if(!$article){
            throw $this->createNotFoundException('Article not found');
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $em=$doctrine->getManager();
           $em->persist($article);
           $em->flush();

            $this->addFlash('success','saved');
            return $this->redirectToRoute('article_page');
        }



        return ['article'=>$article,'form'=>$form->createView()];
    }


    /**
     * article new page
     *
     * @Route("/article/new", name="article_new")
     * @Template()
     */
    public function newArticleAction(Request $request)
    {

        $article=new Article();
        $form = $this->createForm(ArticleType::class,$article);

        if(!$article){
            throw $this->createNotFoundException('Article not found');
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em= $em = $this->getDoctrine()->getManager();;
            $em->persist($article);
            $em->flush();

            $this->addFlash('success','saved');
            return $this->redirectToRoute('article_page');
        }



        return ['article'=>$article,'form'=>$form->createView()];
    }

}