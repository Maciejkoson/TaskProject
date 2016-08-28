<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TaskCategory;
use AppBundle\Form\TaskCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskCategoryController extends Controller

{

    /**
     * @Route("/createCategory/{taskId}")
     * @Template("AppBundle:TaskCategory:createCategory.html.twig")
     */
    public function createCategoryActoion(Request $request, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        if(!$task){
            throw $this->createNotFoundException('Task does not exist');
        }

        $taskCategory = new TaskCategory();

        $taskCategory->addTask($task);     //ustawiamy relacje
        $task->setTaskCategory($taskCategory);     //

        $form = $this->createForm(new TaskCategoryType(), $taskCategory);

        $form->add('submit', 'submit');

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($taskCategory);
            $em->flush();

            return $this->redirectToRoute('app_task_showone', array('taskId' => $taskId));
        }

        return ['form' =>$form->createView()];
    }
}

