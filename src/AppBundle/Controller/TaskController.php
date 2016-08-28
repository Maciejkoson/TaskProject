<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Task;
use AppBundle\Form\CommentType;
use AppBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class TaskController extends Controller
{



    /**
     * @Route("/create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $task = new Task(); //tworze taska

        $form = $this->createForm(new TaskType(), $task); // tworzenie nowego formularza z polami z taskType

        $form->add('submit', 'submit'); // dodanie przycisku submit

        $form->handleRequest($request); // obsluga zapytania czyli przypisanie i sprawdzenie pol

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();


            return $this->redirectToRoute('app_task_showall'); // jesli dane zostana zapisane do bazy przekieruj na / gdzie jest akcja show
        }

        return [
            'task' => $task,
            'form'=> $form->createView()];
    }

    /**
     * @Route("/show")
     * @Template()
     */
    public function showAllAction()
    {
//        $tasks = $this->getDoctrine()->getRepository('AppBundle:Task')->findAll(); // pobranie z bazy wszystkich taskow
//
//        return ['tasks' => $tasks];
        $em = $this->getDoctrine()->getManager();

        $completed = $em->getRepository('AppBundle:Task')->findBy(['completed' => true]);
        $notCompleted = $em->getRepository('AppBundle:Task')->findBy(['completed' => false]);

        return array(
            'completed' => $completed,
            'notCompleted' => $notCompleted
        );
    }

    /**
     * @Route("/edit/{taskId}")
     * @Template()
     * //akcja do edycji taska odrazu w niej generuje formularz do tego
     */
    public function editAction(Request $request, $taskId)
    {
        //pobieramy taska o zadanym id
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        if(!$task){
            throw new $this->createNotFoundException('Task doesnt exist');
        }
        //tworzenie formularza do edycji
        $form = $this->createForm(new TaskType(), $task);
        $form->add('submit', 'submit');

        $form->handleRequest($request); //obsluga formularza edycji

        if ($form->isValid()){

            $this->getDoctrine()->getManager()->flush(); //wrzucamy po update do bazy

            return $this->redirectToRoute('app_task_showall'); //po updute kierujemy do wyswietlenia wszystkich
        }

        return [
            'task' => $task,
            'form'=> $form->createView()];

    }


    /**
     * @Route("/completed/{taskId}")
     *
     */
    public function completedAction(Request $request, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        if(!$task) {
            throw new $this->createNotFoundException('Task does not exist');

        }

        $task->setCompleted(true); //ustawienie zadania na zrobione

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_task_showall', array('taskId' => $task->getId()));
    }

    /**
     * @Route("/showOne/{taskId}")
     * @Template()
     */
    public function showOneAction(Request $request, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        if(!$task){
            throw new $this->createNotFoundException('Task does not exist');
        }
        $form = $this->createForm(new TaskType(), $task);

        return ['form'=> $form->createView(),'task' => $task];
    }


    /**
     * @Route("/delete/{taskId}")
     *
     */
    public function deleteAction(Request $request, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        if(!$task){
            throw new $this->createNotFoundException('Task does not found');
        }

        // usuwanie z bazy danych
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        //redirect do pokaz wszystkie
        return $this->redirectToRoute('app_task_showall');

    }

    /**
     * @Route("/addComment/{taskId}")
     * @Template()
     *
     */
    public function addCommentAction(Request $request, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId); // pobieramy taska z bazy

        if (!$task){
            throw new $this->createNotFoundException('Task does not exist');
        }

        $comment = new Comment(); // tworzymy nowy kommentarz

        $comment->setRecordTime(new \DateTime()); // ustawiamy bierzacy czas przy tworzeniu komentarza

        $comment->setTask($task);    // ustawianie relacji : komentarzowi ustawiamy odpowodniego taska
        $task->addComment($comment); // do taska dodajemy komenatrz

        $form = $this->createForm(new CommentType(), $comment); // tworzymy nowy formularz do tworzenia forma

        $form->add('submit','submit'); // dodajemy przycisk submit

        $form->handleRequest($request); //obsluga forma

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);                           //wrzucamy komenatrz (jesli jest poprawnie wpisany) do bazy
            $em->flush();

            return $this->redirectToRoute('app_task_showone', array('taskId' => $taskId)); //jak wszystko ok kierujmey do showone
        }

        return ['form' => $form->createView()];

    }
}
