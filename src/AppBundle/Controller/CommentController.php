<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    /**
     * @Route("/createComment/{taskId}")
     * @Template()
     *
     */
    public function createCommentAction(Request $request, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        if(!$task){
            throw new $this->createNotFoundException('Task does not exsist');
        }

        $comment = new Comment();


        $form = $this->createForm(new CommentType(), $comment);

        $form->add('submit', 'submit');

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();


            return $this->redirectToRoute('app_task_showone', array('taskId' => $taskId));
        }

        return ['form'=>$form->createView()];
    }

    /**
     * @Route("/deleteComment/{commentId}")
     */

    public function deleteCommentAction(Request $request, $commentId)
    {
       $comment = $this->getDoctrine()->getRepository('AppBundle:Comment')->find($commentId);

        if(!$comment){
            throw $this->createNotFoundException('Comment does not exist');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('app_task_showone', ['taskId'=> $comment->getTask()->getId()]);
    }


}
