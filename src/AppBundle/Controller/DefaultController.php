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
        /*return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);*/
        return $this->render('default/home.html.twig');
    }


    /**
    * @Route("/rep", name="reportar")
    */
    public function repAction(Request $request){
      $ticket = new Ticket();



      $form = $this->CreateFormBuilder($ticket)
      ->add('titulo', TextType::class, array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
      #->add('date', DateType::class, array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
      ->add('descripcion', TextareaType::class, array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
      ->add('categoria', ChoiceType::class, array(
        'choices'  => array(
        'Tecnologia' => 'tech',
        'Administrativo' => 'admin',
        'Servicio' => 'serv'),), array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
      ->add('enviar', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary', 'style' => 'margin-bottom:15px')))
      ->getForm();

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $title = $form['titulo']->getData();
        $estado = 'Procesando';
        #$fecha = new\DateTime('now');#$form['date']->getData();
        $descripcion = $form['descripcion']->getData();
        $categoria = $form['categoria']->getData();

        $user = $this->getUser();


      /*  $query = $this->createQueryBuilder('AppBundle:Ticket')
            ->select('username')
            ->where('u.id_usuario = :id')
            ->setParameter('id_usuario', $user->getId())
            ->getQuery()
            ->getOneOrNullResult(); */


        $ticket->setIdUsuario($user->getId());
        $ticket->setTitulo($title);
        $ticket->setEstado($estado);
    #    $ticket->setFecha($fecha);
        $ticket->setDescripcion($descripcion);
        $ticket->setCategoria($categoria);
        $ticket->setPrioridad('Normal');


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
}
