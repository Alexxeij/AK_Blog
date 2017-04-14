<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;

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
        //$id={id}
        return ['id'=>$id];
    }
    /**
     * article test page
     *
     * @Route("/article-test", name="article_test")
     * @Template()
     */
    public function testAction()
    {
        $article= new Article();
        $article->setTitle('some title')->setContent('i^m <b>some</b> article');

        $em=$this->get("doctrine")->getManager();
        $em->persist($article);
        $em->flush();
        return ['article'=>$article];
    }
}