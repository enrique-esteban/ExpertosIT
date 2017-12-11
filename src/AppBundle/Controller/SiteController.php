<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\ContactMailType;
use AppBundle\Entity\Receipt;
use AppBundle\Form\RepairDeviceQueryType;

/**
 * Controlador principal de la web.
 *
 * @author Enrique José Esteban Plaza <ense.esteban@gmail.com>
 */
class SiteController extends Controller
{
    /**
     * Muestra la portada del sitio web.
     *
     * @Route("/", name="index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('index.html.twig', array(
            'nav' => 'index',
        ));
    }

    /**
     * Muestra el formulario de contacto y también procesa el envío de emails.
     *
     * @Route("/contacto/", name="contact")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function contactoAction(Request $request)
    {
        //$contactMail = new ContactMail();
        
        $form = $this->createForm(ContactMailType::class, null);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                
                $content = sprintf(" Remitente: %s \n\n Email:\n %s \n\n Mensaje:\n %s \n\n Dirección IP: %s \n",
                    $data->getName(),
                    $data->getEmail(),
                    htmlspecialchars($data->getBody()),
                    $request->server->get('REMOTE_ADDR')
                );
                
                $to = 'info@expertosit.es';
                $subject = $data->getSubject();

                // Debido a una restricción de 1and1 se usa la función mail de php
                $headers = 'From: ' . $data->getEmail() . PHP_EOL;
                $sended = mail( $to, $subject, $content, $headers );

                if ($sended) {
                    $this->addFlash('success', 'El mensaje de <strong class="flash">'.$data->getEmail().'</strong> sido enviado correctamente.');
                }
                else {
                    $this->addFlash('error', 'El mensaje de <strong class="flash">'.$data->getEmail().'</strong> no ha podido ser enviado.');
                }

                return $this->render('contacto.html.twig', array(
                    'nav' => 'contacto',
                    'form' => $form->createView(),
                ));
            }
        }
        
        return $this->render('contacto.html.twig', array(
            'nav' => 'contacto',
            'form' => $form->createView(),
        ));
    }

    /*public function sendAction ()
    {
        $request = $this->container->get('request');

        $name = $request->get('contacMaiil_name');
        $email = $request->get('form_email');
        $subject = $request->get('form_subject');
        $body = $request->get('form_message');

        $view = $this->renderView('email.html.twig', array( 'name' => $name,
            'email' => $email,
            'from' => $email,
            'subject' => $subject,
            'body' => $body
        ));

        $transport = \Swift_SmtpTransport::newInstance('smtp.1and1.com', 587)
         ->setUsername('smtp.1and1.com')
         ->setPassword('courseAction
    ')
        ;
        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance('Nueva peticion de informacion desde adiestramientovalencia.com ')
         ->setFrom(array('info@adiestramientovalencia.com' => 'Adiestramiento valencia' ))
         ->setTo(array($email => $name))
         ->setCc(array('info@adiestramientovalencia.com' => 'Adiestramiento valencia' ))
         ->setBcc(array('antonio.arjona@yavaris.com' => 'Antonio Arjona' ))
         ->setBody($view, 'text/html')
        ;

        $failures ="";
        $result = $mailer->send($message, $failures);

        //echo "Sent result:" . $result . " From ";// . " To " . $message->getTo();
        //var_dump($failures);

        if (!is_null($result)){ //Code error 2, Code success 3 ????
            if ($result == 0) { 
                $form = $this->createForm(ContactMailType::class, $contactMail);

                $form->handleRequest($request);


            }
            else { 
                return new JsonResponse(array("response" => true));
            }
        }
        else {
            return new JsonResponse(array("response" => false));
        }

    }*/

    /** 
     * Muestra las páginas de cursos
     * 
     * @Route(
     *     "/curso/{slug}/",
     *     requirements={ "slug"="reparacion-de-moviles|iniciacion-a-las-nuevas-tecnologias" },
     *     name="courses",
     * )
     *
     * @param $slug string
     * @return Response
     */
    public function curseAction($slug)
    {
        return $this->render('curso-'.sprintf('%s.html.twig', $slug), array('nav' => 'cursos' ));
    }

    /**
     * Muestra las páginas de ventas -> otros
     *
     * @Route(
     *     "/venta/moviles/{slug}/",
     *     requirements={ "slug"="reacondicionados|nuevos" },
     *     name="sales-smartphone",
     * )
     *
     * @param $slug string
     * @return Response
     */
    public function saleSmartphoneAction($slug)
    {
        switch ($slug) {
            case "reacondicionados":
                $type = 'Reacondicionado';
                break;
            case "nuevos":
                $type = 'Nuevo';
                break;
//            case "otros":
//                $type = 'otro';
//                break;
        }

        $em = $this->getDoctrine()->getManager();
        $sales = $em->getRepository('AppBundle:Sale')->findSalesByType($type, 'En venta');

        if (!$sales) {
            $slug = 'error';
        }

        return $this->render('venta-smartphone-'.sprintf('%s.html.twig', $slug), array(
            'nav' => 'smartphone',
            'sales' => $sales,
            'type' => $type,
        ));
    }

    /** 
     * Muestra las páginas de ventas -> otros
     * 
     * @Route(
     *     "/venta/otros/{slug}/",
     *     requirements={ "slug"="recreativas-y-emulacion|repuestos-y-consumibles|desarrollo-web" },
     *     name="sales-others",
     * )
     *
     * @param $slug string
     * @return Response
     */
    public function saleOtherAction($slug)
    {
        return $this->render('venta-'.sprintf('%s.html.twig', $slug), array('nav' => 'otras-ventas' ));
    }

    /** 
     * Muestra las páginas de reparaciones
     *
     * @Route(
     *     "/servicio-tecnico/",
     *     requirements={ "slug"="technical-support" },
     *     name="technical-support",
     * )
     *
     * @return Response
     */
    public function repairAction()
    {
        return $this->render('servicio-tecnico.html.twig', array('nav' => 'reparaciones' ));
    }

    /**
     * Ofrece un formulario para ingresar un codigo de un reparacion
     *
     * @Route(
     *     "/consulta-de-reparacion/",
     *     name="repair-query",
     * )
     *
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function repairQueryAction(Request $request)
    {
        $repair = new Receipt();

        $form = $this->createForm(RepairDeviceQueryType::class, $repair);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $code = $form->getData()->getCode();

                if (!$code) {
                    $this->addFlash('error', 'Ha ocurrido un error desconocido, no se ha podido completar la búsqueda');

                    return $this->redirectToRoute('repair-query');
                }

                return $this->redirectToRoute('repair-show', array(
                    'code' => $code
                ));
            }
        }

        return $this->render('consulta-de-reparacion.html.twig', array(
            'nav' => 'reparaciones',
            'form' => $form->createView(),
        ));
    }

    /**
     * Procesa un código código de reparación y luego muestra los resultados
     *
     * @Route(
     *     "/reparacion/{code}/",
     *     name="repair-show",
     * )
     *
     * @param $code string
     * @return Response|RedirectResponse
     */
    public function repairShowAction($code)
    {
        $em = $this->getDoctrine()->getManager();
        $receipt = $em->getRepository('AppBundle:Receipt')->findOneByCode($code);

        if ($receipt === null) {
            $this->addFlash('error', 'No existe ninguna reparación con el código <strong class="flash">'.$code.'</strong>.');

            return $this->redirectToRoute('repair-query');
        }

        return $this->render('vista-de-reparacion.html.twig', array(
            'nav' => 'reparaciones',
            'receipt' => $receipt
        ));
    }

    /** 
     * Muestra alguna páginas estaticas que están fuera del menú principal, como por ejemplo el aviso legal.
     * 
     * @Route(
     *     "/sitemap.{_format}",
     *     requirements={ "_format" = "xml" },
     *     name="sitemap",
     * )
     *
     * @return Response
     */
    public function siteMapAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repairs = $em->getRepository('Receipt.php')->findAll();

        return $this->render('sitemap.xml.twig', array(
            'nav' => 'index',
            'repairs' => $repairs
        ));
    }

    /**
     * Página de testeo.
     *
     * @Route(
     *     "/test/",
     *     name="test",
     * )
     *
     * @return Response
     */
    public function testAction()
    {
        $invoice = array(
            'code' => '123456',
            'clientName' => 'Pepe',
            'clientPhone' => '600111222',
            'clientAddress' => '',
            'clientEmail' => '',
            'startDate' => new \DateTime('now'),
            'device' => 'BQ Aquaris X5',
            'description' => '<p>lsdjas dfkasd fasdkf asdf.</p><p>djfjsdfjask djfk sd ksdfk asjdflñaksd.</p>',
            'cost' => 25,
            'discount' => 0,
        );


        return $this->render('receipt-a4.html.twig', array(
            'invoice' => $invoice,
        ));
    }

    /**
     * Muestra alguna páginas estaticas que están fuera del menú principal, como por ejemplo el aviso legal.
     * 
     * @Route(
     *     "/{slug}/",
     *     requirements={ "slug" = "aviso-legal|politicas-de-privacidad" },
     *     name="static",
     * )
     *
     * @param $slug string
     * @return Response
     */
    public function staticAction($slug)
    {
        return $this->render(sprintf('%s.html.twig', $slug), array('nav' => 'index' ));
    }

    /**
     *
     * @Route(
     *     "admin2462/receipt/",
     *     name="receipt",
     *  )
     *
     * @param Request $request
     * @return Response
     */
    public function receiptAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->query->get('id');

        $receipt = $em->getRepository('AppBundle:Receipt')->findOneById($id);

        if (!$receipt) {
            $this->createNotFoundException();
        }

        return $this->render('receipt-a4.html.twig', array('receipt' => $receipt ));
    }

    /**
     *
     * @Route(
     *     "admin2462/invoice/",
     *     name="invoice",
     *  )
     *
     * @param Request $request
     * @return Response
     */
    public function invoiceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->query->get('id');

        $invoice = $em->getRepository('AppBundle:Invoice')->findOneById($id);

        if (!$invoice) {
            $this->createNotFoundException();
        }

        return $this->render('invoice-a4.html.twig', array('invoice' => $invoice ));
    }
}
