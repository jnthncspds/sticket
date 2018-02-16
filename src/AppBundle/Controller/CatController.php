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

class CatController extends Controller
{

  /**
  * @Route("/admin/cat/", name="categorias")
  */

  public function catAction(Request $request){
    global $ticket;
    $form = $this->CreateFormBuilder()
    ->add('Categoria', ChoiceType::class, array('attr' => array('class' => 'nav-link dropdown-toggle', 'style' => 'margin:10px'),
    'choices' => array('tech' => 'tech', 'Administrativo' => 'admin', 'Servicio' => 'serv'),
    ))
    ->add('Buscar', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')))
    ->getForm();

    $form->handleRequest($request);


    if($form->isSubmitted() && $form->isValid()){
      $cat = $form['Categoria']->getData();


      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery('SELECT t.id, t.titulo, t.fecha, t.estado, t.prioridad
        FROM AppBundle:Ticket t
        WHERE t.categoria = :cat')
      ->setParameter('cat', $cat);

      $ticket = $query->getResult();

    }
    return $this->render('categoria/categoria.html.twig', array('ticket' => $ticket, 'form' => $form->createView(),));

  }
}
