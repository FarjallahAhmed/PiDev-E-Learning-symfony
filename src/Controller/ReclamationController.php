<?php

namespace App\Controller;

use App\Entity\Archive;
use App\Entity\Message;
use App\Entity\Reclamation;
use App\Entity\Utilisateurs;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine();
        $tab = $em->getRepository(Reclamation::class)->findAll();
        //dd($tab);

        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
            'reclamation' => $tab,
        ]);
    }

    /**
     * @Route("/reclamation/ajouter", name="ajouterReclamation")
     */
    public function ajouter(Request $request, MailerInterface $mailer): Response
    {
        if($request->isMethod("post")) {
            $em = $this->getDoctrine()->getManager();

            $rec = new Reclamation();

            // app.user.id
            $user = $em->getRepository(Utilisateurs::class)->find('115');
            
            $rec->setIdUser($user);
            
            $rec->setObjet($request->get('objet'));
            $msg = new Message();
            $msg->setContenu($request->get('message'));
            $rec->setIdMessage($msg);
            $rec->setDate(new \DateTime());

            $text = 'Reclamation: '.$rec->getObjet().', Contenu: '.$rec->getIdMessage()->getContenu();

            $email = (new Email())
                ->from('Admin@Gmail.com')
                ->to('Admin@Gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Reclamation Ajouter!')
                ->text($text)
                ->html('<p>User: '.$rec->getIdUser()->getNom().' '.$rec->getIdUser()->getPrenom().'</p> <p>'.$text.'</p>');

            $mailer->send($email);

            $em->persist($msg);
            $em->persist($rec);
            $em->flush();

            return $this->redirectToRoute('formationfront');
        }
        return $this->render('reclamation/ajouter.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /**
     * @Route("/reclamation/modifier/{id}", name="modifierReclamation")
     */
    public function modifier($id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $rec = $em->getRepository(Reclamation::class)->find($id);
        if($request->isMethod("post")) {

            $rec->setObjet($request->get('objet'));
            $rec->getIdMessage()->setContenu($request->get('message'));

            $em->persist($rec->getIdMessage());
            $em->persist($rec);
            $em->flush();

            return $this->redirectToRoute('reclamation');
        }
        return $this->render('reclamation/modifier.html.twig', [
            'controller_name' => 'ReclamationController',
            'rec' => $rec,
        ]);
    }

    /**
     * @Route("/reclamation/supprimer/{id}", name="supprimerReclamation")
     */
    public function supprimer($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $rec = $em->getRepository(Reclamation::class)->find($id);

        $arc = new Archive();
        $arc->setDate($rec->getDate());
        $arc->setIdMessage($rec->getIdMessage());
        $arc->setObjet($rec->getObjet());
        $arc->setIdUser($rec->getIdUser());

        $em->persist($arc);
        $em->remove($rec);
        $em->flush();

        return $this->redirectToRoute('reclamation');
    }

    /**
     * @Route("/reclamation/archive", name="archiveReclamation")
     */
    public function archive(): Response
    {
        $em = $this->getDoctrine();
        $tab = $em->getRepository(Archive::class)->findAll();
        return $this->render('reclamation/archive.html.twig', [
            'controller_name' => 'ReclamationController',
            'reclamation' => $tab,
        ]);
    }

    /**
     * @Route("/rec74ad5a8d4", name="searchReaclamation")
     */
    public function search(Request $request): Response
    {
        $em = $this->getDoctrine();
        $input = $request->get('search');
        $tab = $em->getRepository(Reclamation::class)->search($input);
        $users = $em->getRepository(Utilisateurs::class)->findAll();
        $msgs = $em->getRepository(Message::class)->findAll();
        return $this->render('reclamation/search.html.twig', [
            'controller_name' => 'ReclamationController',
            'reclamation' => $tab,
            'msgs' => $msgs,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/recaz1d98az", name="triReaclamation")
     */
    public function sort(): Response
    {
        $em = $this->getDoctrine();
        $tab = $em->getRepository(Reclamation::class)->sort();
        $users = $em->getRepository(Utilisateurs::class)->findAll();
        $msgs = $em->getRepository(Message::class)->findAll();
        return $this->render('reclamation/search.html.twig', [
            'controller_name' => 'ReclamationController',
            'reclamation' => $tab,
            'msgs' => $msgs,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/reclamation/statistics", name="statisticsReclamation")
     */
    public function statistics(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $archive = $em->getRepository(Archive::class)->findAll();
        $reclamation = $em->getRepository(Reclamation::class)->findAll();
        $count_archive = 0;
        $count_reclamation = 0;
        foreach ($reclamation as $rec) {
            $count_reclamation++;
        }
        foreach ($archive as $arch) {
            $count_archive++;
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [
                ['Reclamation', 'Nombre'],
                ['Reclamation Actuel', $count_reclamation ],
                ['Reclamation Archive', $count_archive]
            ]
        );
        $pieChart->getOptions()->setPieSliceText('label');
        $pieChart->getOptions()->setTitle('Nomber des reclamation');
        $pieChart->getOptions()->setPieStartAngle(100);
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getLegend()->setPosition('none');

        //***********************

        $reclamation = $em->getRepository(Reclamation::class)->findAll();
        $archive = $em->getRepository(Archive::class)->findAll();

        $years = array();
        $years['2019'] = 0;
        $years['2020'] = 0;
        $years['2021'] = 0;
        $years['2022'] = 0;
        $years['2023'] = 0;
        $years['2024'] = 0;
        $years['2025'] = 0;

        $years_arch = array();
        $years_arch['2019'] = 0;
        $years_arch['2020'] = 0;
        $years_arch['2021'] = 0;
        $years_arch['2022'] = 0;
        $years_arch['2023'] = 0;
        $years_arch['2024'] = 0;
        $years_arch['2025'] = 0;


        foreach ($reclamation as $rec) {
            $years[$rec->getDate()->format("Y")]++;
        }
        foreach ($archive as $arch) {
            $years_arch[$arch->getDate()->format("Y")]++;
        }

        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable([
            ['Years', 'Reclamation', 'Reclamation Archive'],
            ['2019', $years['2019'], $years_arch['2019']],
            ['2020', $years['2020'], $years_arch['2020']],
            ['2021', $years['2021'], $years_arch['2021']],
            ['2022', $years['2022'], $years_arch['2022']],
            ['2023', $years['2023'], $years_arch['2023']],
            ['2024', $years['2024'], $years_arch['2024']],
            ['2025', $years['2025'], $years_arch['2025']]

        ]);
        $bar->getOptions()->setTitle('Nomber Reclamation par Annee');
        $bar->getOptions()->getHAxis()->setTitle('Nomber Reclamation');
        $bar->getOptions()->getHAxis()->setMinValue(0);
        $bar->getOptions()->getVAxis()->setTitle('Annee');
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->setHeight(500);

        return $this->render('reclamation/statistics.html.twig', [
            'controller_name' => 'ReclamationController',
            'piechart' => $pieChart,
            'histogram' => $bar,
        ]);
    }

    /**
     * @Route("/reclamation/pdf", name="pdfReclamation")
     */
    public function pdfGenerator()
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $em = $this->getDoctrine();
        $tab = $em->getRepository(Reclamation::class)->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->render('reclamation/pdf.html.twig', [
            'controller_name' => 'ReclamationController',
            'reclamation' => $tab,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
        "Attachment" => false
    ]);
    }
}
