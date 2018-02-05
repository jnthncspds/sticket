<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ticket;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;




class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
    * @Route("/rep", name="reportar")
    */
    public function repAction(Request $request){
      $ticket = new Ticket();

      $form = $this->CreateFormBuilder($ticket)
      ->add('title', TextType::class)
      ->add('date', DateType::class)
      ->add('details', TextareaType::class)
      ->add('cat', ChoiceType::class, array(
    'choices'  => array(
        'Tecnologia' => 'tech',
        'Administrativo' => 'admin',
        'Servicio' => 'serv'),))
      ->add('enviar', SubmitType::class)
      ->getForm();

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $title = $form['title']->getData();
        $estado = 'procesando';
        $fecha = $form['date']->getData();
        $descripcion = $form['details']->getData();
        $categoria = $form['cat']->getData();

        $ticket->setTitle($title);
        $ticket->setEstado($estado);
        $ticket->setFecha($fecha);
        $ticket->setDescripcion($descripcion);
        $ticket->setCategoria($categoria);


        $em = $this->getDoctrine()->getManager();

        $em->persist($ticket);
        $em->flush();

        return $this->redirectToRoute('homepage');

        # code...
      }
      return $this->render('default/reportar.html.twig' ,
      array('form'=>$form->createView(), 'ticket' => $ticket
    ));

    }

    /**
    * @Route("/admin", name="administrador")
    */

    public function adminAction(){
      return $this->render('default/admin.html.twig');
    }
}
