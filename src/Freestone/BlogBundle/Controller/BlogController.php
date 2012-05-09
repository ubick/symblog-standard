<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Freestone\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller {
    
    public function showAction($id, $slug) {
        $em = $this->getDoctrine()->getEntityManager();
        $blog = $em->getRepository('FreestoneBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }
        
        $comments = $em->getRepository('FreestoneBlogBundle:Comment')->getCommentsForBlog($blog->getId());

        return $this->render('FreestoneBlogBundle:Blog:show.html.twig', compact('blog', 'comments'));
    }
    
}