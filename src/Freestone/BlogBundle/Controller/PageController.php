<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Freestone\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Freestone\BlogBundle\Entity\Enquiry;
use Freestone\BlogBundle\Form\EnquiryType;

class PageController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $blogs = $em->getRepository('FreestoneBlogBundle:Blog')->getLatestBlogs();

        return $this->render('FreestoneBlogBundle:Page:index.html.twig', compact('blogs'));
    }

    public function aboutAction() {
        return $this->render('FreestoneBlogBundle:Page:about.html.twig');
    }

    public function contactAction() {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                // send email with the enquiry data
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from symblog - liviu')
                    ->setFrom('webdev@freestone.co.uk')
                    ->setTo($this->container->getParameter('freestone_blog.emails.contact_email'))
                    ->setBody($this->renderView('FreestoneBlogBundle:Page:contactEmail.txt.twig', compact('enquiry')));
                $this->get('mailer')->send($message);

                $this->get('session')->setFlash('blogger-notice', 'Your contact enquiry was succesfully sent. Thanks dude!');

                return $this->redirect($this->generateUrl('FreestoneBlogBundle_contact'));
            }
        }

        return $this->render('FreestoneBlogBundle:Page:contact.html.twig', array(
                'form' => $form->createView()
            ));
    }
    
    public function sidebarAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $blog = $em->getRepository('FreestoneBlogBundle:Blog');
        
        $base_tags = $blog->getTags();
        $tags = $blog->getTagWeights($base_tags);
        
        $commentLimit = $this->container->getParameter('freestone_blog.comments.latest_comment_limit');
        $latestComments = $em->getRepository('FreestoneBlogBundle:Comment')->getLatestComments($commentLimit);
        
        return $this->render('FreestoneBlogBundle:Page:sidebar.html.twig', compact('tags', 'latestComments'));
    }

}